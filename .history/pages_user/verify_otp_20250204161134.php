<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Make sure the PHPMailer files are included

// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$verified = 0;
$otp_error = '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$otp_resend = false;
$otp_in_db = '';
$otp_expiration_time = '';

// Fetch OTP and expiration time
if (!empty($email)) {
    $stmt = $conn->prepare("SELECT otp, otp_expiration_time FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp_in_db, $otp_expiration_time);
    $stmt->fetch();
    $stmt->close();
}

$current_time = time();
$expiration_time = strtotime($otp_expiration_time); // Convert to timestamp

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['resend_otp'])) {
        // Generate new OTP and update expiration time
        $otp = rand(100000, 999999);
        $new_expiration_time = date("Y-m-d H:i:s", time() + 60); // 30 seconds expiration

        // Update the OTP and expiration time in the database
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiration_time = ? WHERE email = ?");
        $stmt->bind_param("sss", $otp, $new_expiration_time, $email);
        if ($stmt->execute()) {
            $otp_resend = true;
            $otp_error = "A new OTP has been sent.";
        }
        $stmt->close();

        // Send OTP via SMTP using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server (e.g., smtp.gmail.com)
            $mail->SMTPAuth = true;
            $mail->Username = 'barangaysilangan64@gmail.com'; // SMTP username
            $mail->Password = 'epststkzqlyltvrg'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('barangaysilangan64@gmail.com', 'OTP Service');
            $mail->addAddress($email); // Add recipient's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';

            // Improved OTP body content with better formatting and structure
            $mail->Body = "
                <html>
                <body>
                    <p style='font-size: 16px; font-family: Arial, sans-serif;'>Dear $firstname,</p>
                    <p style='font-size: 16px; font-family: Arial, sans-serif;'>Your One-Time Password (OTP) is:</p>
                    <p style='font-size: 24px; font-weight: bold; color: #4CAF50;'>$otp</p>
                    <p style='font-size: 16px; font-family: Arial, sans-serif;'>Please enter this code to complete your verification. The OTP will expire in 10 minutes.</p>
                    <p style='font-size: 14px; font-family: Arial, sans-serif; color: #888888;'>If you did not request this, please disregard this email.</p>
                </body>
                </html>
            ";


            $mail->send();
            $otp_resend = true;
            $otp_error = "A new OTP has been sent to your email.";
        } catch (Exception $e) {
            $otp_error = "Failed to send OTP. Error: " . $mail->ErrorInfo;
        }

        // Reload page to reflect the new OTP
        header("Location: verify_otp.php?email=" . urlencode($email));
        exit();
    } else {
        // Check OTP expiration
        if ($current_time > $expiration_time) {
            $otp_error = "OTP has expired. Please request a new one.";
        } else {
            $otp_entered = implode('', $_POST['otp']);
            if ($otp_entered == $otp_in_db) {
                $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $verified = 1;
                    header("Location: login.php");
                    exit();
                }
                $stmt->close();
            } else {
                $otp_error = "Invalid OTP. Please try again.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let expirationTime = new Date(<?php echo json_encode($otp_expiration_time); ?>).getTime();
            let resendBtn = document.getElementById("resend-button");
            let otpError = document.getElementById("otp-error");
            let otpInputs = document.querySelectorAll('.otp-box');

            function checkExpiration() {
                let currentTime = new Date().getTime();
                if (currentTime > expirationTime) {
                    otpError.innerText = "OTP has expired. Please request a new one.";
                    otpInputs.forEach(input => input.disabled = true);
                    resendBtn.style.display = "block";
                }
            }
            setInterval(checkExpiration, 1000);

            // Re-enable OTP inputs after clicking "Resend OTP"
            document.getElementById("resend-form").addEventListener("submit", function() {
                otpInputs.forEach(input => {
                    input.disabled = false;
                    input.value = "";
                });
            });
        });
    </script>
    <style>
        .otp-box {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 8px;
        }

        .otp-box:focus {
            border-color: #4CAF50;
        }
    </style>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <h2 class="text-2xl font-semibold text-gray-700 text-center mb-6">OTP Verification</h2>

        <?php if ($verified === 1): ?>
            <div class="text-center text-green-500 mb-4">Your account is verified!</div>
        <?php elseif ($otp_error): ?>
            <div class="text-center text-red-500 mb-4" id="otp-error"><?php echo $otp_error; ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-6 gap-2">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <input type="text" name="otp[]" maxlength="1" class="otp-box" required>
                <?php endfor; ?>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg mt-4">Verify OTP</button>
        </form>

        <div id="resend-button" style="text-align: center; margin-top: 20px;">
            <form method="POST" id="resend-form">
                <button type="submit" name="resend_otp" class="py-2 px-4 bg-blue-600 text-white rounded-lg">Resend OTP</button>
            </form>
        </div>
    </div>
</body>

</html>
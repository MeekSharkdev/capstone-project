<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "122401";
$database = "dbusers";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include PHPMailer
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = null;
$success_message = null;
$otp_expired = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $current_time = time();
        $expiration_time = $user['reset_token_time'] ?? 0;

        if ($current_time > $expiration_time) {
            $otp_expired = true;
            $otp = rand(100000, 999999);
            $new_expiration_time = $current_time + 30;

            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_time = ? WHERE email = ?");
            $stmt->bind_param("iis", $otp, $new_expiration_time, $email);
            $stmt->execute();

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'barangaysilangan64@gmail.com';
                $mail->Password = 'epststkzqlyltvrg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('barangaysilangan64@gmail.com', 'Barangay Silangan');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body = "<p>Your OTP is: <strong>$otp</strong></p><p>Expires in 30 seconds.</p>";
                $mail->send();

                header("Location: reset-password.php?email=" . urlencode($email));
                exit;
            } catch (Exception $e) {
                $error_message = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $error_message = "Your OTP is still valid. Please wait until it expires.";
        }
    } else {
        $error_message = "No user found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function startTimer(duration) {
            let timer = duration;
            let resendBtn = document.getElementById('resend-btn');
            let countdown = document.getElementById('countdown');
            resendBtn.disabled = true;

            let interval = setInterval(() => {
                countdown.innerText = timer + 's';
                if (timer <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    countdown.innerText = '';
                }
                timer--;
            }, 1000);
        }
    </script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700">Forgot Password</h2>

        <?php if ($error_message): ?>
            <p class="mt-4 text-red-500 text-sm text-center"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="forgot-password.php" method="POST" class="mt-6">
            <label for="email" class="block text-sm font-medium text-gray-700">Enter your email:</label>
            <input type="email" name="email" id="email" required class="mt-2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Submit</button>
        </form>

        <button id="resend-btn" class="mt-4 w-full bg-gray-500 text-white py-2 rounded-lg disabled:opacity-50" onclick="location.reload();" <?php if (!$otp_expired) echo 'disabled'; ?>>Resend OTP</button>
        <p id="countdown" class="text-center text-sm text-gray-500 mt-2"></p>

        <script>
            <?php if (!$otp_expired) echo 'startTimer(30);'; ?>
        </script>
    </div>
</body>

</html>
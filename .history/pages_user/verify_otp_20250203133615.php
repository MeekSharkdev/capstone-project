<?php
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

if (empty($email)) {
    die("Email is required.");
}

// Handle OTP resend request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resend_otp'])) {
    $otp = rand(100000, 999999);
    $new_expiration_time = date("Y-m-d H:i:s", time() + 30); // 30 seconds validity

    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiration_time = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $new_expiration_time, $email);
    $stmt->execute();
    $stmt->close();

    // Reload the page to fetch the new OTP expiration time
    header("Location: otp_verification.php?email=$email");
    exit();
}

// Fetch the latest OTP and expiration time
$stmt = $conn->prepare("SELECT otp, otp_expiration_time FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($otp_in_db, $otp_expiration_time);
$stmt->fetch();
$stmt->close();

$current_time = time();
$expiration_time = strtotime($otp_expiration_time); // Convert to timestamp

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['resend_otp'])) {
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

            function checkExpiration() {
                let currentTime = new Date().getTime();
                if (currentTime > expirationTime) {
                    otpError.innerText = "OTP has expired. Please request a new one.";
                    document.querySelectorAll('.otp-box').forEach(input => input.disabled = true);
                    resendBtn.style.display = "block";
                }
            }
            setInterval(checkExpiration, 1000);
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

        <div id="resend-button" style="display: none; text-align: center; margin-top: 20px;">
            <form method="POST">
                <button type="submit" name="resend_otp" class="py-2 px-4 bg-blue-600 text-white rounded-lg">Resend OTP</button>
            </form>
        </div>
    </div>
</body>

</html>

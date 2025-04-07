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

$error_message = null;
$success_message = null;
$otp_expired = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'] ?? '';
    $otp_input = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT reset_token, reset_token_time FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $current_time = time();
        if ($current_time > $user['reset_token_time']) {
            $otp_expired = true;
            $error_message = "OTP has expired. Please request a new one.";
        } elseif ($otp_input == $user['reset_token']) {
            $success_message = "OTP verified successfully!";
            // Proceed with password reset or login
        } else {
            $error_message = "Invalid OTP. Please try again.";
        }
    } else {
        $error_message = "User not found.";
    }
}

// Resend OTP Logic
if (isset($_POST['resend_otp'])) {
    $otp = rand(100000, 999999);
    $new_expiration_time = time() + 30;

    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_time = ? WHERE email = ?");
    $stmt->bind_param("iis", $otp, $new_expiration_time, $_SESSION['email']);
    $stmt->execute();

    // Send new OTP via email (you already have the email-sending code)
    $success_message = "A new OTP has been sent to your email.";
    $otp_expired = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700">Enter OTP</h2>

        <?php if ($error_message): ?>
            <p class="mt-4 text-red-500 text-sm text-center"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p class="mt-4 text-green-500 text-sm text-center"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="verify-otp.php" method="POST" class="mt-6">
            <label for="otp" class="block text-sm font-medium text-gray-700">Enter the OTP sent to your email:</label>
            <input type="text" name="otp" id="otp" required class="mt-2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Verify OTP</button>
        </form>

        <!-- Resend OTP Button -->
        <form action="verify-otp.php" method="POST" class="mt-4">
            <button id="resend-btn" name="resend_otp" class="w-full bg-gray-500 text-white py-2 rounded-lg disabled:opacity-50" <?php if (!$otp_expired) echo 'disabled'; ?>>Resend OTP</button>
        </form>

        <!-- Countdown Timer -->
        <p id="countdown" class="text-center text-sm text-gray-500 mt-2"></p>

        <script>
            let timeLeft = 30;
            let resendBtn = document.getElementById("resend-btn");
            let countdownDisplay = document.getElementById("countdown");

            function startTimer() {
                let timer = setInterval(() => {
                    if (timeLeft > 0) {
                        countdownDisplay.innerText = `You can resend OTP in ${timeLeft} sec`;
                        timeLeft--;
                    } else {
                        clearInterval(timer);
                        resendBtn.disabled = false;
                        resendBtn.classList.remove("bg-gray-500");
                        resendBtn.classList.add("bg-blue-500", "hover:bg-blue-600");
                        countdownDisplay.innerText = "";
                    }
                }, 1000);
            }

            <?php if (!$otp_expired) echo 'startTimer();'; ?>
        </script>
    </div>
</body>

</html>
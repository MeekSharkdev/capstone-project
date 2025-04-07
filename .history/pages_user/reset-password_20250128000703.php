<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "122401";
$database = "dbusers";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = null;
$success_message = null;
$otp_valid = false;

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $entered_otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
        $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
        $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $otp_expiry_time = $user['reset_token_time'] + (15 * 60);
            if ($entered_otp == $user['reset_token'] && time() <= $otp_expiry_time) {
                $otp_valid = true;
                if ($new_password && $confirm_password && $new_password == $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_time = NULL WHERE email = ?");
                    $stmt->bind_param("ss", $hashed_password, $email);
                    $stmt->execute();

                    $success_message = "Password has been successfully updated!";
                    echo "<script>alert('Password has been successfully updated!'); window.location.href = 'login.php';</script>";
                    exit;
                } elseif ($new_password && $confirm_password && $new_password != $confirm_password) {
                    $error_message = "Passwords do not match.";
                }
            } else {
                $error_message = "Invalid OTP or OTP has expired.";
            }
        } else {
            $error_message = "No user found with this email address.";
        }
    }
} else {
    header("Location: forgot-password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700">Reset Password</h2>

        <?php if ($error_message): ?>
            <p class="text-red-500 text-center mt-2"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="text-green-500 text-center mt-2"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if ($otp_valid): ?>
            <form action="reset-password.php?email=<?php echo urlencode($email); ?>" method="POST" class="mt-4">
                <label class="block mb-2">New Password</label>
                <input type="password" name="new_password" class="w-full p-2 border rounded-md" required>

                <label class="block mt-2 mb-2">Confirm New Password</label>
                <input type="password" name="confirm_password" class="w-full p-2 border rounded-md" required>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md mt-4 hover:bg-blue-600">Submit</button>
            </form>
        <?php else: ?>
            <form action="reset-password.php?email=<?php echo urlencode($email); ?>" method="POST" class="mt-4">
                <label class="block mb-2">Enter OTP sent to your email</label>
                <input type="text" name="otp" class="w-full p-2 border rounded-md" required>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md mt-4 hover:bg-blue-600">Verify OTP</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
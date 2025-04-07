<?php
session_start();
include('../config/db_connection.php');

// Include PHPMailer and its dependencies
require '../vendor/autoload.php';  // Update the path if necessary
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = null;
$success_message = null;
$otp_valid = false;

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Check if OTP is valid
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $entered_otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';  // Prevent Undefined Index
        $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';  // Prevent Undefined Index
        $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';  // Prevent Undefined Index

        // Fetch the user by email to check OTP
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Check if the OTP matches and if it's within 15 minutes
            $otp_expiry_time = $user['reset_token_time'] + (15 * 60); // 15 minutes expiry
            if ($entered_otp == $user['reset_token'] && time() <= $otp_expiry_time) {
                $otp_valid = true;

                // If password fields match, update the password
                if ($new_password && $confirm_password && $new_password == $confirm_password) {
                    // Hash the new password before storing it
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_time = NULL WHERE email = ?");
                    $stmt->execute([$hashed_password, $email]);

                    // Set a success message and redirect to login page
                    $success_message = "Password has been successfully updated!";
                    echo "<script>alert('Password has been successfully updated!'); window.location.href = 'login.php';</script>";
                    exit; // Ensure no further code is executed after the redirect
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
</head>

<body>
    <h2>Reset Password</h2>

    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p style='color:green;'>$success_message</p>";
    }
    ?>

    <?php if ($otp_valid): ?>
        <form action="reset-password.php?email=<?php echo urlencode($email); ?>" method="POST">
            <label for="otp">Enter OTP:</label>
            <input type="text" name="otp" id="otp" required><br><br>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required><br><br>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br><br>

            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <form action="reset-password.php?email=<?php echo urlencode($email); ?>" method="POST">
            <label for="otp">Enter OTP sent to your email:</label>
            <input type="text" name="otp" id="otp" required><br><br>

            <button type="submit">Verify OTP</button>
        </form>
    <?php endif; ?>

</body>

</html>
<?php
session_start();
require '../config/db_connection.php'; // Ensure you have a database connection

if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
    echo "No OTP found. Please request a new one.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];
    $current_time = time();

    if ($current_time > $_SESSION['otp_expiry']) {
        echo "OTP expired. <a href='resend_otp.php'>Resend OTP</a>";
    } elseif ($entered_otp == $_SESSION['otp']) {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        header("Location: login.php");
        exit;
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>

<body>
    <form method="POST" action="">
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" required>
        <button type="submit">Verify</button>
    </form>
    <form action="resend_otp.php" method="POST">
        <button type="submit">Resend OTP</button>
    </form>
</body>

</html>
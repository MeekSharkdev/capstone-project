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

$verified = 0; // Default verification status
$otp_error = '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$otp_resend = false; // Flag to check if the OTP is being resent

// Fetch OTP expiration time for checking when the form is submitted
$stmt = $conn->prepare("SELECT otp, otp_expiration_time FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($otp_in_db

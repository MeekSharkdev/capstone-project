<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT reset_token, reset_token_time FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $reset_token = $user['reset_token'];
        $reset_token_time = $user['reset_token_time'];
        $current_time = time();

        // Check if OTP has expired (30 seconds expiration time)
        if (($current_time - $reset_token_time) > 30) {
            echo "OTP has expired. Please request a new one.";
        } else {
            if ($otp == $reset_token) {
                echo "OTP is valid! You can now reset your password.";
            } else {
                echo "Invalid OTP.";
            }
        }
    } else {
        echo "No user found with this email address.";
    }
}

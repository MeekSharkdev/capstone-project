<?php
session_start();
include("../config");

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];
    $email = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email='$email' AND otp='$otp'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        mysqli_query($conn, "UPDATE users SET otp='' WHERE email='$email'");
        unset($_SESSION['email']);
        header("Location: login.php");
        exit();
    } else {
        echo "<script>alert('Invalid OTP');</script>";
    }
}

if (isset($_POST['resend'])) {
    $email = $_SESSION['email'];
    $new_otp = rand(100000, 999999);
    mysqli_query($conn, "UPDATE users SET otp='$new_otp' WHERE email='$email'");

    // Here, you should send the new OTP via email
    echo "<script>alert('A new OTP has been sent to your email.');</script>";
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
    <form method="POST">
        <label>Enter OTP:</label>
        <input type="text" name="otp" required>
        <button type="submit" name="verify">Verify</button>
    </form>
    <form method="POST">
        <button type="submit" name="resend">Resend OTP</button>
    </form>
</body>

</html>
<?php
// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate OTP and send to user's email
        $otp = rand(100000, 999999);
        $stmt->close();

        // Save OTP in the database
        $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
        $stmt->bind_param("is", $otp, $email);
        $stmt->execute();
        $stmt->close();

        // Send OTP to user's email (using PHP mail function)
        mail($email, "Password Reset OTP", "Your OTP is: $otp");

        echo "OTP sent to your email address!";
    } else {
        echo "Email not found!";
    }
}

$conn->close();
?>

<!-- Forgot Password Form -->
<form method="POST">
    <label for="email">Enter your email address:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Request OTP</button>
</form>
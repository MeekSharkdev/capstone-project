<?php
session_start();

<?php
session_start();

// Database connection with mysqli
$servername = "localhost";
$username = "root";
$password = "122401";
$database = "dbusers";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email and entered OTP
$email = $_GET['email']; // The email is passed as a query parameter
$entered_otp = $_POST['otp']; // The OTP entered by the user in the form

// Fetch the OTP and expiration time from the database
$stmt = $conn->prepare("SELECT reset_token, reset_token_expiration FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $stored_otp = $user['reset_token'];
    $expiration_time = $user['reset_token_expiration'];
    
    // Get the current time
    $current_time = time();  // Unix timestamp of the current time

    // Check if the OTP has expired
    if ($current_time > $expiration_time) {
        echo "The OTP has expired. Please request a new one.";
    } elseif ($entered_otp == $stored_otp) {
        // Proceed with password reset logic
        echo "OTP verified! Proceeding with the reset.";
    } else {
        echo "Invalid OTP.";
    }
} else {
    echo "No user found with this email.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700">Forgot Password</h2>

        <?php if ($error_message): ?>
            <p class="mt-4 text-red-500 text-sm text-center"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p class="mt-4 text-green-500 text-sm text-center"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="forgot-password.php" method="POST" class="mt-6">
            <label for="email" class="block text-sm font-medium text-gray-700">Enter your email address:</label>
            <input type="email" name="email" id="email" required class="mt-2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Submit</button>
        </form>
    </div>
</body>

</html>
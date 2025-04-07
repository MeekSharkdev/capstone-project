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

$error_message = null;
$success_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $entered_otp = trim($_POST['otp']);  // OTP entered by user

    // Retrieve the OTP and expiration time from the database
    $stmt = $conn->prepare("SELECT reset_token, reset_token_time FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $stored_otp = $user['reset_token'];
        $stored_expiration_time = $user['reset_token_time'];

        // Check if OTP is expired (current time > expiration time)
        if (time() > $stored_expiration_time) {
            $error_message = "OTP has expired. Please request a new one.";
        } else {
            // Check if the entered OTP matches the stored OTP
            if ($entered_otp == $stored_otp) {
                $success_message = "OTP verified successfully!";
                // Proceed to reset password logic
            } else {
                $error_message = "Invalid OTP.";
            }
        }
    } else {
        $error_message = "No user found with this email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700">OTP Verification</h2>

        <?php if ($error_message): ?>
            <p class="mt-4 text-red-500 text-sm text-center"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p class="mt-4 text-green-500 text-sm text-center"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="verify-otp.php" method="POST" class="mt-6">
            <input type="hidden" name="email" value="<?php echo urlencode($email); ?>" />
            <label for="otp" class="block text-sm font-medium text-gray-700">Enter the OTP sent to your email:</label>
            <input type="text" name="otp" id="otp" required class="mt-2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Submit</button>
        </form>
    </div>
</body>

</html>
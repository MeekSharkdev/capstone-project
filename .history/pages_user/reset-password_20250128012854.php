<?php
session_start();

// Database connection with mysqli
$servername = "localhost";
$username = "root";
$password = "122401";
$database = "dbusers";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to avoid undefined variable warnings
$error_message = null;
$success_message = null;
$otp_valid = false;

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Fetch the user by email to check OTP
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $entered_otp = isset($_POST['otp']) ? implode('', $_POST['otp']) : '';  // Join OTP array into a string
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
            $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

            // Check if OTP is valid
            $otp_expiry_time = $user['reset_token_time'] + (15 * 60); // 15 minutes expiry
            if ($entered_otp == $user['reset_token'] && time() <= $otp_expiry_time) {
                $otp_valid = true; // OTP is valid
                if ($new_password && $confirm_password && $new_password == $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_time = NULL WHERE email = ?");
                    $stmt->bind_param("ss", $hashed_password, $email);
                    $stmt->execute();

                    // Set success message
                    $success_message = "Password has been successfully updated!";

                    // Redirect to login after success
                    echo "<script>
                            setTimeout(function() {
                                swal('Success', 'Password updated successfully!', 'success').then(function() {
                                    window.location = 'login.php';
                                });
                            }, 500);
                          </script>";
                    exit;  // Prevent further script execution
                } elseif ($new_password && $confirm_password && $new_password != $confirm_password) {
                    $error_message = "Passwords do not match.";
                }
            } else {
                $error_message = "Invalid OTP.";
            }
        }
    } else {
        $error_message = "No user found with this email address.";
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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-xl font-semibold mb-4 text-center">Reset Password</h2>

        <?php if ($error_message): ?>
            <p class="text-red-500 text-center mb-2"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="text-green-500 text-center mb-2"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!$otp_valid): ?>
            <!-- OTP form is shown only if OTP is not valid yet -->
            <form action="reset-password.php?email=<?php echo urlencode($email); ?>" method="POST" class="space-y-4">
                <label class="block">Enter OTP sent to your email:</label>
                <div class="flex space-x-2 justify-center">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" maxlength="1" class="otp-box w-12 h-12 text-center border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" name="otp[]" required />
                    <?php endfor; ?>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Verify OTP</button>
            </form>
        <?php else: ?>
            <!-- Password reset form is shown after OTP is validated -->
            <form action="reset-password.php?email=<?php echo urlencode($email); ?>" method="POST" class="space-y-4">
                <label class="block">New Password:</label>
                <input type="password" name="new_password" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>

                <label class="block">Confirm New Password:</label>
                <input type="password" name="confirm_password" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>

                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Submit</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
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

// Include PHPMailer
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = null;
$success_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $otp = rand(100000, 999999);
        $current_time = time();
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_time = ? WHERE email = ?");
        $stmt->bind_param("iis", $otp, $current_time, $email);
        $stmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'barangaysilangan64@gmail.com';
            $mail->Password = 'epststkzqlyltvrg';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('barangaysilangan64@gmail.com', 'BRGY');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Hello,<br><br>Your OTP for resetting your password is:<br><b>$otp</b>";

            if ($mail->send()) {
                header("Location: reset-password.php?email=" . urlencode($email));
                exit;
            } else {
                $error_message = "Mailer Error: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            $error_message = "Error sending email: {$mail->ErrorInfo}";
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
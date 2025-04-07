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
        // Get the current time and expiration time from the database
        $current_time = time();
        $expiration_time = $user['reset_token_time'] ?? 0;
        $otp = $user['reset_token'] ?? null;

        if ($current_time > $expiration_time) {
            // OTP has expired, generate a new OTP
            $otp = rand(100000, 999999);
            $new_expiration_time = $current_time + 30; // Set new expiration time (30 seconds)

            // Update OTP and expiration time in the database
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_time = ? WHERE email = ?");
            $stmt->bind_param("iis", $otp, $new_expiration_time, $email);
            $stmt->execute();

            // Send the new OTP via email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'barangaysilangan64@gmail.com';
                $mail->Password = 'epststkzqlyltvrg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('barangaysilangan64@gmail.com', 'Barangay Silangan');
                $mail->addAddress($email);

                // Email Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = '
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            padding: 10px;
            background-color: #f4f4f4;
            border: 2px solid #4CAF50;
            display: inline-block;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Password Reset Request</h2>
        </div>
        <div class="content">
            <p>Dear User,</p>
            <p>We received a request to reset your password. To proceed, please use the following OTP:</p>
            <p class="otp-code">' . $otp . '</p>
            <p>This OTP will expire in 30 seconds. If you did not request a password reset, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>Best regards,<br>Barangay Silangan</p>
            <p><a href="mailto:barangaysilangan64@gmail.com" style="color: rgb(158, 221, 160);">Contact us</a> for any inquiries.</p>
        </div>
    </div>
</body>
</html>
';

                if ($mail->send()) {
                    $success_message = "A new OTP has been sent to your email.";
                } else {
                    $error_message = "Mailer Error: " . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                $error_message = "Error sending email: {$mail->ErrorInfo}";
            }
        } else {
            // OTP is still valid, inform the user to wait or resend OTP
            $error_message = "Your OTP is still valid. Please wait until it expires or click the button to resend a new OTP.";
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

        <?php if ($error_message): ?>
            <form action="forgot-password.php" method="POST" class="mt-6">
                <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">Resend OTP</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
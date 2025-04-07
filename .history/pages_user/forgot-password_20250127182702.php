forgot-password.php
<?php
session_start();
include('p');

// Include PHPMailer and its dependencies
require '../vendor/autoload.php';  // Update the path if necessary
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = null;
$success_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate an OTP for password reset
        $otp = rand(100000, 999999);
        $current_time = time();

        // Store the OTP and the timestamp in the database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_time = ? WHERE email = ?");
        $stmt->execute([$otp, $current_time, $email]);

        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'barangaysilangan64@gmail.com';  // Replace with your email
            $mail->Password = 'epststkzqlyltvrg';  // Replace with your email password (use app-specific password if needed)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('barangaysilangan64@gmail.com', 'BRGY');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Hello,<br><br>Your OTP for resetting your password is:<br><b>$otp</b>";

            if ($mail->send()) {
                $success_message = "OTP has been sent to your email. Please check your inbox!";
                // Redirect user to reset-password.php to enter OTP
                header("Location: reset-password.php?email=" . urlencode($email));
                exit;  // Ensure no further code is executed after the redirect
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
</head>

<body>
    <h2>Forgot Password</h2>

    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p style='color:green;'>$success_message</p>";
    }
    ?>

    <form action="forgot-password.php" method="POST">
        <label for="email">Enter your email address:</label>
        <input type="email" name="email" id="email" required><br><br>
        <button type="submit">Submit</button>
    </form>

</body>

</html>
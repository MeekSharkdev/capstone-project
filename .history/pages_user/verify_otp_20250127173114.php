<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // Adjust this path as per your project setup

// Send OTP verification email
function sendOTPVerificationEmail($email, $otp)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use your email service provider's SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';  // Your email address
        $mail->Password = 'your-email-password';  // Your email password (use app password if 2FA enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($email);  // Add recipient's email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body    = 'Your OTP verification code is: ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Database connection settings
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = trim($_POST['otp']);
    $email = trim($_POST['email']);

    // Server-side validation
    if (empty($otp) || empty($email)) {
        $errors[] = "Please enter both OTP and email.";
    }

    // Check OTP in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($dbOtp);
        $stmt->fetch();
        $stmt->close();

        if ($otp == $dbOtp) {
            $success = true;
            // Optionally, you can update the user's OTP field to null or delete it after successful verification
            $stmt = $conn->prepare("UPDATE users SET otp = NULL WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
        } else {
            $errors[] = "Invalid OTP.";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">Verify OTP</h2>
        </div>

        <!-- Display SweetAlert for errors -->
        <?php if (!empty($errors)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo implode('<br>', $errors); ?>',
                });
            </script>
        <?php endif; ?>

        <!-- Display SweetAlert for success -->
        <?php if ($success): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Verified!',
                    text: 'Your OTP has been successfully verified.',
                    confirmButtonText: 'Proceed',
                    willClose: () => {
                        window.location.href = "next-step.php"; // Redirect after successful verification
                    }
                });
            </script>
        <?php endif; ?>

        <!-- OTP Verification Form -->
        <form method="POST" class="space-y-6">
            <div class="relative z-0 w-full group">
                <input type="email" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
            </div>

            <div class="relative z-0 w-full group">
                <input type="text" name="otp" id="floating_otp" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_otp" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">OTP Code</label>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Verify OTP</button>
        </form>
    </div>
</body>

</html>
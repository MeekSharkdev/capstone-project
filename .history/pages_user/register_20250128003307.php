<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // Adjust this path as per your project setup

// Send OTP email function
function sendOTPEmail($email, $otp)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use your email service provider's SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'barangaysilangan64@gmail.com';  // Your email address
        $mail->Password = 'epststkzqlyltvrg';  // Your email password (use app password if 2FA enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('barangaysilangan64@gmail.com', 'BRGY');
        $mail->addAddress($email);  // Add recipient's email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Verification Code';
        $mail->Body    = 'Your OTP code is: ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

// OTP generation function
function generateOTP()
{
    return rand(100000, 999999);  // Generate a 6-digit OTP
}

// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

$errors = [];
$success = false; // Flag to track success

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $phonenumber = trim($_POST['phonenumber']);
    $birthdate = trim($_POST['birthdate']);
    $birthplace = trim($_POST['birthplace']);
    $sex = trim($_POST['sex']);
    $civilstatus = trim($_POST['civilstatus']);
    $citizenship = trim($_POST['citizenship']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Server-side validation
    if (empty($firstname) || empty($middlename) || empty($lastname) || empty($phonenumber) || empty($birthdate) || empty($birthplace) || empty($sex) || empty($civilstatus) || empty($citizenship) || empty($email) || empty($username) || empty($password)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{11}$/', $phonenumber)) {
        $errors[] = "Phone number must be exactly 11 digits.";
    }

    // Insert data if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex, civilstatus, citizenship, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            $stmt->bind_param("ssssssssssss", $username, $hashed_password, $firstname, $middlename, $lastname, $phonenumber, $birthdate, $birthplace, $sex, $civilstatus, $citizenship, $email);

            if ($stmt->execute()) {
                // Generate OTP
                $otp = generateOTP();

                // Store OTP in the database (you should create a table for OTPs or add it to the users table)
                $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
                $stmt->bind_param("ss", $otp, $email);
                $stmt->execute();

                // Send OTP email
                sendOTPEmail($email, $otp);

                $success = true;
            } else {
                $errors[] = "Database error: Unable to execute query.";
            }

            $stmt->close();
        } else {
            $errors[] = "Database error: Unable to prepare statement.";
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
    <title>Register | User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert CDN -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome CDN for Eye Icon -->
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <style>
        .fade-in-slide-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInSlideUpAnimation 1s ease-out forwards;
        }

        @keyframes fadeInSlideUpAnimation {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script>
        function validateForm() {
            // Validate each field individually
            var valid = true;

            var firstname = document.getElementById("floating_firstname").value;
            if (firstname === "") {
                alert("First Name is required");
                valid = false;
            }

            var phonenumber = document.getElementById("floating_phonenumber").value;
            if (!/^[0-9]{11}$/.test(phonenumber)) {
                alert("Phone number must be exactly 11 digits");
                valid = false;
            }

            var email = document.getElementById("floating_email").value;
            if (!/\S+@\S+\.\S+/.test(email)) {
                alert("Invalid email format");
                valid = false;
            }

            var password = document.getElementById("floating_password").value;
            var confirmPassword = document.getElementById("floating_confirm_password").value;
            if (password.length < 6) {
                alert("Password must be at least 6 characters long");
                valid = false;
            } else if (password !== confirmPassword) {
                alert("Passwords do not match");
                valid = false;
            }

            return valid;
        }
    </script>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <img src="../images/BRGY.__3_-removebg-preview.png" alt="Logo" class="mx-auto mb-4 w-20">
            <h2 class="text-2xl font-semibold text-gray-700">Register</h2>
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
                    title: 'Registration successful!',
                    text: 'Check your email for the OTP.',
                    confirmButtonText: 'Go to OTP Verification',
                    willClose: () => {
                        window.location.href = "verify_otp.php?email=<?php echo urlencode($email); ?>"; // Redirect to verify OTP page
                    }
                });
            </script>
        <?php endif; ?>

        <!-- Registration Form -->
        <?php if (!$success) : ?>
            <form method="POST" class="space-y-6 fade-in-slide-up" onsubmit="return validateForm()">
                <!-- Add all input fields here as in the original form, but now the validation will run on form submission -->
            </form>
        <?php endif; ?>
    </div>
</body>

</html>

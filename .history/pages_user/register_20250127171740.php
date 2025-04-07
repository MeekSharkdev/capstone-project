<?php
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
        $otp = rand(100000, 999999); // Generate 6-digit OTP

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex, civilstatus, citizenship, email, otp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            $stmt->bind_param("sssssssssssss", $username, $hashed_password, $firstname, $middlename, $lastname, $phonenumber, $birthdate, $birthplace, $sex, $civilstatus, $citizenship, $email, $otp);

            if ($stmt->execute()) {
                // Send OTP via email
                require 'vendor/autoload.php'; // Load PHPMailer
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\Exception;

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'your_email@gmail.com'; // Replace with your email
                    $mail->Password = 'your_email_password'; // Replace with your email password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('your_email@gmail.com', 'Verification Team');
                    $mail->addAddress($email);

                    $mail->Subject = 'Your OTP Code';
                    $mail->Body = "Your OTP code is: $otp";

                    $mail->send();
                    $_SESSION['email'] = $email; // Store email for verification
                    header("Location: verify.php"); // Redirect to OTP verification page
                    exit();
                } catch (Exception $e) {
                    echo "Email sending failed: {$mail->ErrorInfo}";
                }

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
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert CDN -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome CDN for Eye Icon -->
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
                    text: 'You can now log in.',
                    confirmButtonText: 'Go to Login',
                    willClose: () => {
                        window.location.href = "login.php"; // Redirect after successful registration
                    }
                });
            </script>
        <?php endif; ?>

        <!-- Registration Form -->
        <?php if (!$success) : ?>
            <form method="POST" class="space-y-6 fade-in-slide-up">
                <!-- Form fields here (same as your original form) -->
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

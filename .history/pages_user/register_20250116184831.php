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

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex, civilstatus, citizenship, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            $stmt->bind_param("ssssssssssss", $username, $hashed_password, $firstname, $middlename, $lastname, $phonenumber, $birthdate, $birthplace, $sex, $civilstatus, $citizenship, $email);

            if ($stmt->execute()) {
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
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <img src="images/brgylogo.jpg" alt="Logo" class="mx-auto mb-4 w-20">
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
            V
        <?php endif; ?>

        <script>
            // Toggle password visibility
            function togglePassword() {
                const passwordField = document.getElementById('floating_password');
                const icon = document.getElementById('toggle-password');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            // Toggle confirm password visibility
            function toggleConfirmPassword() {
                const confirmPasswordField = document.getElementById('floating_confirm_password');
                const icon = document.getElementById('toggle-confirm-password');
                if (confirmPasswordField.type === 'password') {
                    confirmPasswordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    confirmPasswordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        </script>
    </div>
</body>
</html>

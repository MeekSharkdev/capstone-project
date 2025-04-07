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
    $lastname = trim($_POST['lastname']);
    $phonenumber = trim($_POST['phonenumber']);
    $birthdate = trim($_POST['birthdate']);
    $sex = trim($_POST['sex']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $attachment = $_FILES['attachment'];

    // Server-side validation
    if (empty($firstname) || empty($lastname) || empty($phonenumber) || empty($birthdate) || empty($sex) || empty($email) || empty($username) || empty($password) || empty($role)) {
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

    if (!preg_match('/^[0-9]+$/', $phonenumber)) {
        $errors[] = "Phone number must only contain numbers.";
    }

    if ($attachment['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf']; // Modify according to your needs
        if (!in_array($attachment['type'], $allowed_types)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and PDF are allowed.";
        }
    }

    // Insert data if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, lastname, phonenumber, birthdate, email, sex, role, attachment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            // Handle file upload
            $attachment_name = '';
            if ($attachment['error'] === 0) {
                $attachment_name = uniqid() . '-' . basename($attachment['name']);
                $upload_dir = 'uploads/';
                move_uploaded_file($attachment['tmp_name'], $upload_dir . $attachment_name);
            }

            $stmt->bind_param("ssssssssss", $username, $hashed_password, $firstname, $lastname, $phonenumber, $birthdate, $email, $sex, $role, $attachment_name);

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <img src="images/brgylogo.jpg" alt="Logo" class="mx-auto mb-4 w-20">
            <h2 class="text-2xl font-semibold text-gray-700">Register</h2>
        </div>

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger text-red-600 text-sm mb-4">
                <?php echo implode('<br>', $errors); ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <?php if (!$success) : ?>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Form fields here -->

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Register</button>
            </form>
        <?php else : ?>
            <div class="text-center text-green-600">Registration successful!</div>
        <?php endif; ?>
    </div>

    <script>
        // SweetAlert for success or error
        <?php if (!empty($errors)) : ?>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo implode('<br>', $errors); ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php elseif ($success) : ?>
            Swal.fire({
                title: 'Success!',
                text: 'Registration successful!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("floating_password");
            var toggleIcon = document.getElementById("toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        function toggleConfirmPassword() {
            var confirmPasswordField = document.getElementById("floating_confirm_password");
            var toggleIcon = document.getElementById("toggle-confirm-password");
            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                confirmPasswordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>


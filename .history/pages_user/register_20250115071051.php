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
    <!-- Font Awesome for the eye icon -->
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
            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
<!-- Inside the registration form, modify the password input fields like this: -->

<div class="relative z-0 w-full group">
    <input type="password" name="password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
    <label for="floating_password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
    <i class="fas fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" id="toggle-password" onclick="togglePassword()"></i>
</div>

<div class="relative z-0 w-full group">
    <input type="password" name="confirm_password" id="floating_confirm_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
    <label for="floating_confirm_password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm Password</label>
    <i class="fas fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" id="toggle-confirm-password" onclick="toggleConfirmPassword()"></i>
</div>

<!-- In the script section -->
<script>
    function togglePassword() {
        var passwordField = document.getElementById('floating_password');
        var icon = document.getElementById('toggle-password');
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

    function toggleConfirmPassword() {
        var confirmPasswordField = document.getElementById('floating_confirm_password');
        var icon = document.getElementById('toggle-confirm-password');
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
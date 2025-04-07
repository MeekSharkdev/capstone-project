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

    // Server-side validation
    if (empty($firstname) || empty($lastname) || empty($phonenumber) || empty($birthdate) || empty($sex) || empty($email) || empty($username) || empty($password)) {
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

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, lastname, phonenumber, birthdate, email, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            $stmt->bind_param("ssssssss", $username, $hashed_password, $firstname, $lastname, $phonenumber, $birthdate, $email, $sex);

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

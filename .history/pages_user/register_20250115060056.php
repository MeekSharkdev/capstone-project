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

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, middlename, lastname, phonenumber, birthdate, birthplace, email, sex, civilstatus, citizenship) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            $stmt->bind_param("ssssssssssss", $username, $hashed_password, $firstname, $middlename, $lastname, $phonenumber, $birthdate, $birthplace, $email, $sex, $civilstatus, $citizenship);

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

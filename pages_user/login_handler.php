<?php
session_start();

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=login_system', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        header('Location: login.php?error=Please enter both username and password');
        exit;
    }

    // Prepare statement to check if the username exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Start session and redirect to dashboard
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        // If login fails, redirect with error message
        header('Location: login.php?error=Invalid username or password');
        exit;
    }
}
?>

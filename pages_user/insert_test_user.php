<?php
// Connection to the database
$pdo = new PDO('mysql:host=localhost;dbname=login_system', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Hash the password
$hashed_password = password_hash('testpassword', PASSWORD_DEFAULT);

// Insert the user into the database
$stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
$stmt->execute(['username' => 'testuser', 'password' => $hashed_password]);

echo "User inserted!";
?>

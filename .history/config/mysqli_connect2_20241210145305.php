<?php
$host = 'localhost'; // Change to your database host if different
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password
$database = 'db'; // Replace with your database name

// Create a database connection
$dbc = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$dbc) {
    die('Database connection failed: ' . mysqli_connect_error());
}
?>

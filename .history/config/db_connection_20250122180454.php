<?php
$servername = "localhost";  // Your server name (usually localhost)
$username = "root";         // Your MySQL username (default is usually root)
$password = "122401";       // Your MySQL password (replace with your actual password)
$database = "dbusers";      // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

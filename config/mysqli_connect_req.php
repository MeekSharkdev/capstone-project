<?php
$servername = "localhost";  // Your server name
$username = "root";         // Your database username
$password = "122401";             // Your database password (usually empty for localhost)
$dbname = "dbusers";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Exit if connection fails
}
?>

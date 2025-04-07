<?php
$servername = "localhost";  // Your server name
$username = "root";         // Your MySQL username
$password = "122401";       // Your MySQL password
$database = "dbusers";      // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

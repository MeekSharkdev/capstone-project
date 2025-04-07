<?php
$servername = "localhost";  
$username = "root";         
$password = "122401";       
$database = "dbusers";      // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

<?php
$servername = "localhost";  
$username = "root";         
$password = "122401";       
$database = "dbusers";      


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

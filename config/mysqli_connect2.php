<?php
$host = 'localhost'; 
$username = 'root'; 
$password = '122401'; 
$database = 'dbusers';

$dbc = mysqli_connect($host, $username, $password, $database);

if (!$dbc) {
    die('Database connection failed: ' . mysqli_connect_error());
}
?>

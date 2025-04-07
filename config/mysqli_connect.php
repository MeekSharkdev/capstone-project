<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '122401';
    $dbname = 'dbusers';

    //create connection
    $conn = mysqli_connect($servername,$username,$password,$dbname);

    if(!$conn)
    {
        die("Connection Failed".mysqli_connect_error());
    }
?>

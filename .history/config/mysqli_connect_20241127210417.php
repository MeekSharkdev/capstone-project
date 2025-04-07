<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '122401';
    $dbname = 'db';

    //create connection
    $dbc = mysqli_connect($servername,$username,$password,$dbname);

    if(!$dbc)
    {
        die("Connection Failed".mysqli_connect_error());
    }
?>
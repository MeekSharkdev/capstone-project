<?php
$dbc = mysqli_connect('localhost', 'username', 'password', 'dbusers');

if ($dbc) {
    echo 'Database connected successfully.';
} else {
    echo 'Failed to connect to database: ' . mysqli_connect_error();
}
?>

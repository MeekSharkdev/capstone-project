<?php
session_start();
include('../config/mysqli_connect.php');

// Check if the ID is passed via POST
if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Update the status to 'done' for the given user
    $query = "UPDATE users SET status = 'done' WHERE id = $userId";
    $result = mysqli_query($dbc, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($dbc)); // Handle query failure
    }
}
?>
    
<?php
session_start();
include('../config/mysqli_connect.php');

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Update the status to 'done'
    $query = "UPDATE users SET status = 'done' WHERE id = $userId";
    $result = mysqli_query($dbc, $query);

    if (!$result) {
        echo 'failure';  // Send failure response if query fails
    } else {
        echo 'success';  // Send success response if update is successful
    }
}

// Close the database connection
mysqli_close($dbc);
?>

<?php
session_start();
include('../config/mysqli_connect.php');

// Check if the ID is provided and is valid
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    
    // Update the status to 'done' in the database
    $query = "UPDATE users SET status = 'done' WHERE id = $userId";
    
    $result = mysqli_query($dbc, $query);
    
    if ($result) {
        // Send success response
        echo 'success';
    } else {
        // Send failure response if query fails
        echo 'error';
    }
} else {
    echo 'Invalid ID';
}

mysqli_close($dbc);
?>

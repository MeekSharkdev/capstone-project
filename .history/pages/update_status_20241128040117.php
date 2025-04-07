<?php
session_start();
include('../config/mysqli_connect.php');

// Check if the ID is set in the POST request
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    
    // Update the status to 'done'
    $query = "UPDATE users SET status = 'done' WHERE id = $userId";
    
    if (mysqli_query($dbc, $query)) {
        echo "Status updated successfully";
    } else {
        echo "Error: " . mysqli_error($dbc);
    }
}

mysqli_close($dbc); // Close the database connection
?>

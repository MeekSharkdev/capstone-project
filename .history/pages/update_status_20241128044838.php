<?php
session_start();
include('../config/mysqli_connect.php');

// Check if the user ID is set
if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Update the status back to "pending" or the default state
    $query = "UPDATE users SET status = 'pending' WHERE id = $userId";  // Assuming "pending" is the default state
    $result = mysqli_query($dbc, $query);

    if ($result) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . mysqli_error($dbc);
    }
} else {
    echo "No ID provided.";
}

mysqli_close($dbc);
?>

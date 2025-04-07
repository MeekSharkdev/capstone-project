<?php
session_start();
include('../config/mysqli_connect.php');

// Check if ID is provided in the request
if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Update the user status to 'done'
    $query = "UPDATE users SET status = 'done' WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        if (mysqli_stmt_execute($stmt)) {
            // Successfully updated
            echo "Status updated successfully.";
        } else {
            echo "Error updating status.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing query.";
    }
} else {
    echo "User ID not provided.";
}

// Close the database connection
mysqli_close($dbc);
?>

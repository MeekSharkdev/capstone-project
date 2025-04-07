<?php
include('../config/db_connection.php');

// Turn on error reporting to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if an ID is passed in the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Prepare the delete query
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the event ID to the query
        mysqli_stmt_bind_param($stmt, 'i', $event_id);
        $result = mysqli_stmt_execute($stmt);

        // Check if the deletion was successful
        if ($result) {
            // Redirect to the manage events page after successful deletion
            header("Location: manage_events.php");
            exit;
        } else {
            echo "Error deleting event.";
        }
    } else {
        echo "Error preparing query.";
    }
} else {
    echo "No ID passed in the URL.";
}

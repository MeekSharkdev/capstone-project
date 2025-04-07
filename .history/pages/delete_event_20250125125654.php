<?php
include('../config/db_connection.php');

// Turn on error reporting to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Delete event
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Delete event from the database
    $query = "DELETE FROM events WHERE event_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $event_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Redirect to manage_events.php after successful deletion
            header("Location: manage_events.php");
            exit;
        } else {
            // Debugging: Error message when delete fails
            echo "Error deleting event.";
            exit;
        }
    } else {
        // Debugging: Error message when query preparation fails
        echo "Error preparing query.";
        exit;
    }
} else {
    // Debugging: If ID is not set, output an error message
    echo "No event ID provided.";
    exit;
}

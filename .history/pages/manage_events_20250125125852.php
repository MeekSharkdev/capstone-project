<?php
include('../config/db_connection.php');

// Turn on error reporting to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debugging: Check if the ID is passed
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    echo "Event ID: $event_id"; // Debugging line to check the passed ID

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
            // Error when deleting
            echo "Error deleting event.";
            exit;
        }
    } else {
        // Error preparing query
        echo "Error preparing query.";
        exit;
    }
} else {
    // If no event ID is provided, show this message
    echo "No event ID provided.";
    exit;
}

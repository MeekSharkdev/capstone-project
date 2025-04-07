<?php
include('../config/db_connection.php');

// Turn on error reporting to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Delete event
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Check if the event exists in the database
    $check_query = "SELECT * FROM events WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, 'i', $event_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Proceed with deletion
        $query = "DELETE FROM events WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $event_id);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                // Redirect after successful deletion
                header("Location: manage_events.php");
                exit;
            } else {
                echo "Error deleting event.";
            }
        } else {
            echo "Error preparing query.";
        }
    } else {
        echo "Event not found.";
    }
}

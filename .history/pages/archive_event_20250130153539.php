<?php
include('../config/db_connection.php');

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update the event status to archived (is_archived = 1)
    $query = "UPDATE events SET archived = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $event_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect back to manage events with a success status
        header("Location: manage_events.php?status=success");
    } else {
        // Handle error
        echo "Error archiving event.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Event ID not specified.";
}

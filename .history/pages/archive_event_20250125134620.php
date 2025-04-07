<?php
include('../config/db_connection.php');

// Archive event
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Mark the event as archived (you can use a boolean column `is_archived` in the database)
    $query = "UPDATE events SET is_archived = 1 WHERE event_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $event_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect with a success status
        header("Location: manage_events.php?status=success");
        exit;
    } else {
        echo "Error archiving event.";
    }
}

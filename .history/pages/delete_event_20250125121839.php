<?php
include('../config/');

// Delete event
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Delete event from the database
    $query = "DELETE FROM events WHERE event_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $event_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: manage_events.php");
        exit;
    } else {
        echo "Error deleting event.";
    }
}

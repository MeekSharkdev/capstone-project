<?php
include('../config/db_connection.php'); // Include your database connection

session_start(); // Start session if needed

// Check if delete_id is provided through POST request
if (isset($_POST['delete_id'])) {
    $event_id = intval($_POST['delete_id']); // Sanitize input

    // Delete event query
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        // Redirect to archive_storage.php with a proper delete status
        header("Location: archive_storage.php?status=deleted");
        exit();
    } else {
        // If delete fails, redirect with error status
        header("Location: archive_storage.php?status=error");
        exit();
    }
} else {
    // Redirect if no delete_id is provided
    header("Location: archive_storage.php");
    exit();
}

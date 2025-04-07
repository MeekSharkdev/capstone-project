<?php
include('../config/db_connection.php'); // Include your database connection

session_start(); // Start session if needed

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']); // Sanitize input

    // Delete event query
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        // Redirect to manage_events.php with a proper delete status
        header("Location: manage_events.php?status=deleted");
        exit();
    } else {
        // If delete fails, redirect with error status
        header("Location: manage_events.php?status=error");
        exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: manage_events.php");
    exit();
}

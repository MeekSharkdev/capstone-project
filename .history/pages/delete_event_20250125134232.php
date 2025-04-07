<?php
include('../config/db_connection.php'); // Include your database connection

// Check if admin is logged in (add your authentication logic here)
session_start();

// Check if event ID is set
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Delete event query
    $query = "DELETE FROM events WHERE id = '$event_id'";

    if (mysqli_query($conn, $query)) {
        // Redirect back to manage_events.php with success status
        header("Location: manage_events.php?status=success");
        exit();
    } else {
        // If delete fails, redirect back with failure status
        header("Location: manage_events.php?status=error");
        exit();
    }
} else {
    // Redirect back to manage_events.php if ID is not provided
    header("Location: manage_events.php");
    exit();
}

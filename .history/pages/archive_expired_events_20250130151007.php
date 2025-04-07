<?php
include('../config/db_connection.php');

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Update expired events to archived
$query = "UPDATE events SET archived = 1 WHERE CONCAT(event_date, ' ', event_time) < NOW() AND archived = 0";

if (mysqli_query($conn, $query)) {
    echo "Expired events archived successfully.";
} else {
    echo "Error archiving expired events: " . mysqli_error($conn);
}

mysqli_close($conn);

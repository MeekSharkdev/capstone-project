<?php
// update_status.php
include('../config/mysqli_connect.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id']; // Get user ID from the request

    // Update status in the database
    $query = "UPDATE users SET status = 'done' WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);

    if (mysqli_stmt_execute($stmt)) {
        echo "success"; // Respond with success
    } else {
        echo "error"; // Respond with error
    }

    mysqli_stmt_close($stmt); // Close statement
}
mysqli_close($dbc); // Close connection
?>

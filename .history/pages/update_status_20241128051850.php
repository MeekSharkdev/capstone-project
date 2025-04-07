<?php
session_start();
include('../config/mysqli_connect.php');

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Update the status to 'done'
    $query = "UPDATE users SET status = 'done' WHERE id = $userId";
    $result = mysqli_query($dbc, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($dbc)); // Handle query failure
    } else {
        // Send a response to indicate the status update
        echo json_encode(['status' => 'done', 'userId' => $userId]);
    }
}
?>

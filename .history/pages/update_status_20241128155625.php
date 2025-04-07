<?php
session_start();
include('../config/mysqli_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = intval($_POST['id']);
    
    // Update status to 'done' in the database
    $query = "UPDATE users SET status = 'done' WHERE id = $userId";
    $result = mysqli_query($dbc, $query);

    if ($result) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($dbc);
    }
} else {
    echo "invalid_request";
}
mysqli_close($dbc);
?>

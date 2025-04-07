<?php
session_start();
include('../config/mysqli_connect.php');

if (isset($_POST['id']) && isset($_POST['action'])) {
    $userId = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'done') {
        // Update the status to 'done'
        $query = "UPDATE users SET status = 'done' WHERE id = $userId";
        $result = mysqli_query($dbc, $query);
        
        if ($result) {
            echo "done"; // Send back 'done' as response
        } else {
            echo "error"; // Handle error case
        }
    } elseif ($action == 'undo') {
        // Update the status back to 'not done'
        $query = "UPDATE users SET status = 'not_done' WHERE id = $userId";
        $result = mysqli_query($dbc, $query);
        
        if ($result) {
            echo "not_done"; // Send back 'not_done' as response
        } else {
            echo "error"; // Handle error case
        }
    }
} else {
    echo "Invalid request";
}

mysqli_close($dbc);
?>

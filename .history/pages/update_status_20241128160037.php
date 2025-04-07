<?php
session_start();
include('../config/mysqli_connect.php');

// Check if `id` is sent via POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Update the user's status to "done"
    $query = "UPDATE users SET status = 'done' WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "success";
        } else {
            echo "error";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "error";
    }
} else {
    echo "invalid_request";
}

mysqli_close($dbc);
?>

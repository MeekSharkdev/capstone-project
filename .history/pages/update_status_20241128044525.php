<?php
include('../config/mysqli_connect.php');

if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    // Update the status to 'done'
    $query = "UPDATE users SET status = 'done' WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Status updated";
    } else {
        echo "Error updating status";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($dbc);
?>

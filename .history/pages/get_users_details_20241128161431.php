<?php
include('../config/mysqli_connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    $query = "SELECT id, firstname, lastname, phonenumber, birthdate, email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'user' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided.']);
}

mysqli_close($dbc);
?>

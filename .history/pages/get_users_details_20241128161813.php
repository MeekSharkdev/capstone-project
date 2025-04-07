<?php
include('../config/mysqli_connect.php');

if (isset($_GET['id'])) {
    $userId = (int)$_GET['id'];

    // Fetch user details from the database
    $query = "SELECT id, firstname, lastname, phonenumber, birthdate, email FROM users WHERE id = $userId";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Return the user details as JSON
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
}

mysqli_close($dbc);
?>

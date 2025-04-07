<?php
include('../config/mysqli_connect.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id']) && isset($input['date'])) {
    $id = $input['id'];
    $date = $input['date'];

    $query = "UPDATE bookings SET booking_date = ? WHERE bookings_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('si', $date, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
}

$conn->close();
?>
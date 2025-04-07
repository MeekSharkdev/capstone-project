<?php
include('../config/mysqli_connect.php');

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['date'])) {
    $id = intval($data['id']);
    $newDate = $data['date'];

    $query = "UPDATE bookings SET booking_date = ? WHERE bookings_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $newDate, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>

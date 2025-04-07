<?php
// update_booking_date.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['newDate'])) {
    $bookingId = $data['id'];
    $newDate = $data['newDate'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '122401', 'dbusers');

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    // Update the booking date
    $stmt = $conn->prepare("UPDATE bookings SET booking_date = ? WHERE bookings_id = ?");
    $stmt->bind_param('si', $newDate, $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking date updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking date']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>

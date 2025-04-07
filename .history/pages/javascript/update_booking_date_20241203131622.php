<?php
include('../config/mysqli_connect.php'); // Include the database connection

// Get the JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id']) && isset($input['date'])) {
    $id = $input['id'];
    $date = $input['date'];

    // Update the booking date in the database
    $query = "UPDATE bookings SET booking_date = ? WHERE bookings_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $date, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
}

$conn->close();
?>

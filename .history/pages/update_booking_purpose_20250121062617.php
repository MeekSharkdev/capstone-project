<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (isset($data['id'], $data['date'], $data['purpose'])) {
    $bookings_id = $data['id'];
    $new_date = $data['date'];
    $new_purpose = $data['purpose'];

    // Prepare the update query
    $query = "UPDATE bookings SET booking_date = ?, purpose = ? WHERE bookings_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ssi', $new_date, $new_purpose, $bookings_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error executing query: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare query']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
}

$conn->close();
?>

<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Get the raw POST data (from the fetch request)
$data = json_decode(file_get_contents('php://input'), true);

// Extract the values sent
$bookings_id = $data['id'];
$new_date = $data['date'];
$new_purpose = $data['purpose'];

// Update the booking date and purpose in the database
$query = "UPDATE bookings 
          SET booking_date = ?, purpose = ? 
          WHERE bookings_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ssi', $new_date, $new_purpose, $bookings_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating booking']);
}

$stmt->close();
$conn->close();
?>

<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($input['id']) || !isset($input['date'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$id = intval($input['id']); // Sanitize ID
$date = mysqli_real_escape_string($conn, $input['date']); // Sanitize date

// Update the booking date in the database
$query = "UPDATE bookings SET booking_date = '$date' WHERE bookings_id = $id";
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update booking: ' . mysqli_error($conn)]);
}

// Close the database connection
mysqli_close($conn);
?>

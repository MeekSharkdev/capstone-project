<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Get the data from the request
$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->date)) {
    $bookingId = $data->id;
    $newDate = $data->date;

    // Update the booking date in the database
    $query = "UPDATE bookings SET booking_date = '$newDate' WHERE bookings_id = '$bookingId'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating date: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>

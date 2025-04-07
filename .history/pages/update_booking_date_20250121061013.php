<?php
// Assuming you're receiving the data as JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['date']) && isset($data['purpose'])) {
    $id = $data['id'];
    $date = $data['date'];
    $purpose = $data['purpose'];

    // Your database connection
    include('../config/mysqli_connect.php');

    $query = "UPDATE bookings SET booking_date = ?, purpose = ? WHERE bookings_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $date, $purpose, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>

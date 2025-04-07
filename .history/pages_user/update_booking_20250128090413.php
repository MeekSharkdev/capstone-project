<?php
include('../config/mysqli_connect.php');

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['booking_date'])) {
    $bookingId = $data['id'];
    $newDate = $data['booking_date'];

    $query = "UPDATE bookings SET booking_date = ? WHERE bookings_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $newDate, $bookingId);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

mysqli_close($conn);

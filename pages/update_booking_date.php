<?php
header('Content-Type: application/json');
session_start();
include('../config/mysqli_connect.php');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['date'])) {
    echo json_encode(["success" => false, "message" => "Missing parameters"]);
    exit();
}

$id = intval($data['id']);
$new_date = $data['date'];

// Debugging output
if (!$id || !$new_date) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit();
}

// Ensure the date is formatted correctly (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $new_date)) {
    echo json_encode(["success" => false, "message" => "Invalid date format"]);
    exit();
}

// Ensure the new date is not in the past
if (strtotime($new_date) < strtotime(date('Y-m-d'))) {
    echo json_encode(["success" => false, "message" => "Cannot select a past date"]);
    exit();
}

// Update the database
$query = "UPDATE bookings SET booking_date = ? WHERE bookings_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $new_date, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

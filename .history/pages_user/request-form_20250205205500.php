<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $middle_initial = $_POST['middle_initial'];
    $lastname = $_POST['lastname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $request_date = $_POST['request_date'];
    $reason = $_POST['reason'];

    // Fetch the latest scheduled date from the bookings table
    $query = "SELECT booking_date FROM bookings ORDER BY booking_date DESC LIMIT 1";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $original_date = $row['booking_date'] ?? null; // Store the original scheduled date

    if ($original_date) {
        // Insert into request_reschedule with the original scheduled date
        $insert_query = "INSERT INTO request_reschedule (firstname, middle_initial, lastname, phonenumber, email, original_date, request_date, reason) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssssss", $firstname, $middle_initial, $lastname, $phonenumber, $email, $original_date, $request_date, $reason);

        if ($stmt->execute()) {
            echo "Request submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "No previous booking found.";
    }
}

$conn->close();

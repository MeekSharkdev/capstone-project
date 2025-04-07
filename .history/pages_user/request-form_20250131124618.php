<?php
// Start session
session_start();

// Include database connection
require('../config/db_connection.php');

// Check if bookings_id exists in the session
if (isset($_SESSION['bookings_id']) && !empty($_SESSION['bookings_id'])) {
    $bookings_id = $_SESSION['bookings_id'];  // Get the booking ID from the session
} else {
    // Display a more informative error message and exit the script if bookings_id is missing
    die("Bookings ID is missing. Please select a booking.");
}

// Prepare and execute the query to fetch booking details
$query = "SELECT firstname, lastname, middle_initial, phonenumber, email, purpose, booking_date FROM bookings WHERE bookings_id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $bookings_id);  // Bind bookings_id to the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if booking exists
    if ($result->num_rows == 0) {
        die("Booking with the provided ID does not exist.");
    }

    // Fetch booking details
    $booking = $result->fetch_assoc();

    // Assigning variables
    $firstname = $booking['firstname'];
    $lastname = $booking['lastname'];
    $middle_initial = $booking['middle_initial'] ?? ''; // Use null coalescing for middle_initial
    $phonenumber = $booking['phonenumber'];
    $email = $booking['email'];
    $purpose = $booking['purpose'];
    $booking_date = $booking['booking_date'];

    // Close statement
    $stmt->close();
} else {
    die("Error preparing the query: " . $conn->error);
}

// Close the database connection
$conn->close();

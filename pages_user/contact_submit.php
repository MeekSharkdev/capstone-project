<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $contact_method = $_POST['contact_method'];
    $submission_date = $_POST['submission_date'];

    // Database connection
    $servername = 'localhost';
    $username = 'root';
    $password = '122401';
    $dbname = 'dbusers';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the INSERT query (since user_id is removed)
    $stmt = $conn->prepare("INSERT INTO contact_us (first_name, middle_name, last_name, phone_number, email, subject, message, contact_method, submission_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $phone_number, $email, $subject, $message, $contact_method, $submission_date);

    // Execute the query
    if ($stmt->execute()) {
        echo "Message submitted successfully.";
    } else {
        echo "Error submitting message: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}

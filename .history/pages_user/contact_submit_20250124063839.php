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

    // Handle file upload (optional)
    $file = $_FILES['file'];

    if ($file['error'] == UPLOAD_ERR_OK) {
        // Specify the directory where the file will be uploaded
        $upload_dir = 'uploads/';
        $file_name = basename($file['name']);
        $file_path = $upload_dir . $file_name;

        // Move the file to the upload directory
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading the file.";
        }
    }

    // Optionally, save the data to a database or send an email
    // For example, sending the form data via email:
    $to = "your-email@example.com";
    $subject_email = "Contact Form Submission: $subject";
    $message_email = "Name: $first_name $middle_name $last_name\nPhone: $phone_number\nEmail: $email\nMessage: $message";
    mail($to, $subject_email, $message_email);

    // Or save data to a database (MySQL example)
    // Assuming you have a connection to your database
    // $conn = new mysqli('localhost', 'username', 'password', 'database_name');
    // $sql = "INSERT INTO contacts (first_name, middle_name, last_name, phone_number, email, subject, message, contact_method, submission_date, file_path) VALUES ('$first_name', '$middle_name', '$last_name', '$phone_number', '$email', '$subject', '$message', '$contact_method', '$submission_date', '$file_path')";
    // $conn->query($sql);

    echo "Your message has been submitted successfully!";
} else {
    echo "Invalid request method.";
}

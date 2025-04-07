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
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $upload_dir = 'uploads/';
        $file_name = basename($file['name']);
        $file_path = $upload_dir . $file_name;

        // Check if the file is an image
        $check = getimagesize($file['tmp_name']);
        if ($check !== false) {
            // Proceed with the file upload
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                echo "File uploaded successfully.";
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "The uploaded file is not a valid image.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }

    // Optionally, send the email (if necessary)
    $to = "your-email@example.com";
    $subject_email = "Contact Form Submission: $subject";
    $message_email = "Name: $first_name $middle_name $last_name\nPhone: $phone_number\nEmail: $email\nMessage: $message";

    // Uncomment the line below once SMTP is configured correctly
    // mail($to, $subject_email, $message_email);

    echo "Your message has been submitted successfully!";
} else {
    echo "Invalid request method.";
}

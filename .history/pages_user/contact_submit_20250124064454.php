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
    $user_id = $_POST['user_id']; // Assuming you have the user ID for the update

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

    // Prepare and bind the UPDATE query
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, phone_number = ?, email = ?, subject = ?, message = ?, contact_method = ?, submission_date = ? WHERE id = ?");
    $stmt->bind_param("sssssssssi", $first_name, $middle_name, $last_name, $phone_number, $email, $subject, $message, $contact_method, $submission_date, $user_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "User details updated successfully.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

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
                // Optionally, you can update the file path in the database if needed
                $file_update_stmt = $conn->prepare("UPDATE users SET file_path = ? WHERE id = ?");
                $file_update_stmt->bind_param("si", $file_path, $user_id);
                $file_update_stmt->execute();
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "The uploaded file is not a valid image.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}

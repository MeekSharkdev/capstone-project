<?php
$servername = "localhost";
$username = "root";
$password = "122401";
$dbname = "dbusers";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Handle the uploaded file securely
    if (isset($_FILES['attachment_form_id']) && $_FILES['attachment_form_id']['error'] === UPLOAD_ERR_OK) {
        $attachment_form_id = $_FILES['attachment_form_id']['name'];
        $upload_dir = "uploads/";
        $file_path = $upload_dir . basename($_FILES['attachment_form_id']['name']);

        // Move file safely
        if (move_uploaded_file($_FILES['attachment_form_id']['tmp_name'], $file_path)) {
            // Insert data into the database only after the file upload succeeds
            $stmt = $conn->prepare("INSERT INTO admintbl (firstname, lastname, phonenumber, email, birthdate, age, sex, role, username, password, attachment_form_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssssssssss", 
                $firstname, 
                $lastname, 
                $phonenumber, 
                $email, 
                $birthdate, 
                $age, 
                $sex, 
                $role, 
                $username, 
                $password_hashed, 
                $attachment_form_id
            );

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href='./login-admin.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Failed to upload the file!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No valid file uploaded.'); window.history.back();</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Registration</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="mb-3">
                <label for="attachment_form_id" class="form-label">Upload ID/Verification</label>
                <input type="file" class="form-control" id="attachment_form_id" name="attachment_form_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>

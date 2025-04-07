<?php
session_start();

// Database connection
$servername = 'localhost';
$db_username = 'root';
$db_password = '122401';
$dbname = 'dbusers';

$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];
    $reason = $_POST['reason'];

    // Insert form data into database
    $stmt = $conn->prepare("INSERT INTO requests (firstname, middlename, lastname, email, date, purpose, reason) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $email, $date, $purpose, $reason);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Request submitted successfully!";
        header("Location: request_form.php");  // Redirect to the form page or another page after success
        exit();
    } else {
        $_SESSION['error'] = "Error submitting request!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Form</title>
    <link rel="stylesheet" href="path_to_tailwind.css">
</head>
<body>
    <!-- Your form HTML here -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <!-- Form fields (Firstname, Middlename, Lastname, etc.) -->

        <button type="submit">Submit</button>
    </form>
</body>
</html>

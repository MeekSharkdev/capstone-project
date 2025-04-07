<?php
include('../config/mysqli_connect_req.php');  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];
    $reason = $_POST['reason'];

    // SQL query to insert data into the table
    $sql = "INSERT INTO request_reschedule (firstname, middlename, lastname,  date, purpose, reason) 
            VALUES ('$firstname', '$middlename', '$lastname', '$date', '$purpose', '$reason')";

    if ($conn->query($sql) === TRUE) {
        // If insertion is successful, trigger a notification and redirect
        echo "
            <script>
                alert('Request Submitted Successfully!');
                window.location.href = 'dashboard.php';  // Redirect to dashboard
            </script>
        ";
    } else {
        // If there's an error in inserting data
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>

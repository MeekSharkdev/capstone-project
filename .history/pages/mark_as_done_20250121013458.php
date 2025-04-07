<?php
// mark_as_done.php
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update the status in the database to 'done'
    $conn = new mysqli('localhost', 'root', '122401', 'dbusers');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE request_reschedule SET done = 1 WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record marked as done successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
    header("Location: rescheduling-request.php"); // Redirect back to the reschedule page
}
?>

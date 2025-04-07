<?php
session_start(); // Start the session
require_once '../config/db_connection.php';

$error_message = '';
$success_message = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['form_submitted'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    $event_image = '';
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../uploads/';
        $image_name = $_FILES['event_image']['name'];
        $image_tmp = $_FILES['event_image']['tmp_name'];
        $image_size = $_FILES['event_image']['size'];
        $image_type = $_FILES['event_image']['type'];

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image_type, $allowed_types) && $image_size < 5000000) {
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $new_image_name = uniqid() . '.' . $image_ext;
            $image_path = $upload_dir . $new_image_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                $event_image = $new_image_name;
            } else {
                $error_message = "Error uploading image.";
            }
        } else {
            $error_message = "Invalid image file or file too large.";
        }
    }

    if (empty($error_message)) {
        $sql = "INSERT INTO events (event_name, event_date, event_time, event_location, event_description, event_image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $event_name, $event_date, $event_time, $event_location, $event_description, $event_image);

        if ($stmt->execute()) {
            $_SESSION['form_submitted'] = true;
            header("Location: create_event.php?success=1");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}

if (isset($_GET['success'])) {
    $success_message = "Event created successfully!";
    unset($_SESSION['form_submitted']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include('sidebar.php'); ?>

    <div class="container mx-auto p-4 bg-white rounded-lg shadow-lg w-1/2 mt-10">
        <h1 class="text-xl font-bold mb-2">Create New Event</h1>

        <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-3 mb-2 rounded">
                <?php echo $success_message; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div class="bg-red-500 text-white p-3 mb-2 rounded">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="event_name">Event Name</label>
                    <input type="text" name="event_name" id="event_name" required>
                </div>
                <div>
                    <label for="event_date">Event Date</label>
                    <input type="date" name="event_date" id="event_date" required>
                </div>
                <div>
                    <label for="event_time">Event Time</label>
                    <input type="time" name="event_time" id="event_time" required>
                </div>
                <div>
                    <label for="event_location">Event Location</label>
                    <input type="text" name="event_location" id="event_location" required>
                </div>
                <div>
                    <label for="event_description">Event Description</label>
                    <textarea name="event_description" id="event_description" required></textarea>
                </div>
                <div>
                    <label for="event_image">Event Image</label>
                    <input type="file" name="event_image" id="event_image" accept="image/*" required>
                </div>
                <div>
                    <button type="submit">Create Event</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

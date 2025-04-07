<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize success and error messages
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_image = $_FILES['event_image'];

    // Validate image upload
    if ($event_image['error'] === UPLOAD_ERR_OK) {
        $image_name = $event_image['name'];
        $image_tmp_name = $event_image['tmp_name'];
        $image_size = $event_image['size'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid('event_', true) . '.' . $image_ext;

        // Check file size (5MB max)
        if ($image_size > 5 * 1024 * 1024) {
            $error_message = 'Image size should not exceed 5MB.';
        } else {
            // Move the uploaded image to the desired directory
            move_uploaded_file($image_tmp_name, 'uploads/events/' . $image_new_name);

            // Insert data into the database
            $sql = "INSERT INTO events (event_name, event_date, event_time, event_location, event_description, event_image)
                    VALUES ('$event_name', '$event_date', '$event_time', '$event_location', '$event_description', '$image_new_name')";

            if ($conn->query($sql) === TRUE) {
                $success_message = 'Event created successfully!';
            } else {
                $error_message = 'Error: ' . $conn->error;
            }
        }
    } else {
        $error_message = 'Please upload an image.';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 flex">

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 flex justify-center items-center ml-64 p-6">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <h2 class="text-2xl font-bold text-center mb-6">Create New Event</h2>

            <!-- Display success or error message -->
            <?php if ($success_message): ?>
                <div class="bg-green-500 text-white p-4 mb-4 rounded">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif ($error_message): ?>
                <div class="bg-red-500 text-white p-4 mb-4 rounded">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="create_event.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold">Event Name</label>
                    <input type="text" name="event_name" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block font-semibold">Event Date</label>
                    <input type="date" name="event_date" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block font-semibold">Event Time</label>
                    <input type="time" name="event_time" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block font-semibold">Event Location</label>
                    <input type="text" name="event_location" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block font-semibold">Event Description</label>
                    <textarea name="event_description" rows="4" required class="w-full px-3 py-2 border rounded"></textarea>
                </div>
                <div>
                    <label class="block font-semibold">Event Image</label>
                    <input type="file" name="event_image" accept="image/*" required class="w-full px-3 py-2 border rounded">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 col-span-2 mt-4">Create Event</button>
            </form>
        </div>
    </div>

</body>

</html>
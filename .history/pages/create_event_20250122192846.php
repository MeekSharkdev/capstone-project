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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    // Handle file upload
    $targetDir = 'uploads/events/';
    $imageName = basename($_FILES["event_image"]["name"]);
    $targetFile = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image is valid (for simplicity, we're only checking the extension here)
   // Handle file upload
$targetDir = 'uploads/events/';
$imageName = basename($_FILES["event_image"]["name"]);
$targetFile = $targetDir . $imageName;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if image is valid (for simplicity, we're only checking the extension here)
if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
    // Check if the file was successfully uploaded
    if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $targetFile)) {
        // Insert event data into the database
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_time, event_location, event_description, event_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $event_name, $event_date, $event_time, $event_location, $event_description, $imageName);

        if ($stmt->execute()) {
            $success_message = "Event created successfully!";
        } else {
            $error_message = "Error creating event: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Sorry, there was an error uploading your file.";
    }
} else {
    $error_message = "Only image files are allowed (JPG, JPEG, PNG, GIF).";
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- JavaScript to hide alert after a few seconds -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');

            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 5000); // Hide after 5 seconds
            }

            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>
</head>

<body class="bg-gray-100 flex">

    <!-- Include Sidebar from Main Dashboard -->
    <?php include('sidebar.php'); ?>
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <i class="fas fa-bars w-6 h-6"></i>
    </button>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-6">
        <h1 class="text-2xl font-bold mb-4">Create New Event</h1>

        <!-- Display success or error message -->
        <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-4 mb-4 rounded success-message">
                <?php echo $success_message; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div class="bg-red-500 text-white p-4 mb-4 rounded error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" id="event_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date</label>
                    <input type="date" name="event_date" id="event_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="event_time" class="block text-sm font-medium text-gray-700">Event Time</label>
                    <input type="time" name="event_time" id="event_time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="event_location" class="block text-sm font-medium text-gray-700">Event Location</label>
                    <input type="text" name="event_location" id="event_location" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="event_description" class="block text-sm font-medium text-gray-700">Event Description</label>
                    <textarea name="event_description" id="event_description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="event_image" class="block text-sm font-medium text-gray-700">Event Image</label>
                    <input type="file" name="event_image" id="event_image" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" accept="image/*" required>
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">Create Event</button>
        </form>
    </div>

</body>

</html>
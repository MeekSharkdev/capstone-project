<?php
require_once '../config/db_connection.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    // Check for duplicate events (same name and date)
    $check_sql = "SELECT * FROM events WHERE event_name = ? AND event_date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $event_name, $event_date);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "This event already exists.";
    } else {
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
            $sql = "INSERT INTO events (event_name, event_date, event_time, event_location, event_description, event_image)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $event_name, $event_date, $event_time, $event_location, $event_description, $event_image);

            if ($stmt->execute()) {
                $success_message = "Event created successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | Admin</title>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitButton = form.querySelector("button[type='submit']");

            form.addEventListener("submit", function() {
                submitButton.disabled = true; // Disable button to prevent multiple submissions
                submitButton.textContent = "Submitting...";
            });
        });
    </script>
</head>

<body class="bg-gray-200 flex items-center justify-center min-h-screen ml-32 mt-6">

    <?php include('sidebar.php'); ?>

    <div class="container mx-auto p-4 bg-white rounded-lg shadow-lg w-1/2 mt-10">
        <h1 class="text-xl font-bold mb-2">Create New Event</h1>

        <!-- Display success or error message -->
        <?php if ($success_message): ?>
            <div id="alert-success" class="bg-green-500 text-white p-3 mb-2 rounded opacity-0 transition-opacity duration-1000">
                <?php echo $success_message; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div id="alert-error" class="bg-red-500 text-white p-3 mb-2 rounded opacity-0 transition-opacity duration-1000">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>


        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="event_name" class="block text-md font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" id="event_name" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                </div>

                <div>
                    <label for="event_date" class="block text-md font-medium text-gray-700">Event Date</label>
                    <input type="date" name="event_date" id="event_date" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                </div>

                <div>
                    <label for="event_time" class="block text-md font-medium text-gray-700">Event Time</label>
                    <input type="time" name="event_time" id="event_time" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                </div>

                <div>
                    <label for="event_location" class="block text-md font-medium text-gray-700">Event Location</label>
                    <input type="text" name="event_location" id="event_location" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                </div>

                <div>
                    <label for="event_description" class="block text-md font-medium text-gray-700">Event Description</label>
                    <textarea name="event_description" id="event_description" rows="2" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required></textarea>
                </div>

                <div>
                    <label for="event_image" class="block text-md font-medium text-gray-700">Event Image</label>
                    <input type="file" name="event_image" id="event_image" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" accept="image/*" required>
                </div>

                <div>
                    <button type="submit" class="w-full px-3 py-1.5 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:ring-2 focus:ring-blue-300 text-md">Create Event</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the alert elements
            const successAlert = document.getElementById("alert-success");
            const errorAlert = document.getElementById("alert-error");

            // Function to fade in and fade out alert
            function fadeAlert(alert) {
                if (alert) {
                    alert.classList.remove('opacity-0');
                    alert.classList.add('opacity-100');

                    // Wait for 3 seconds and fade out
                    setTimeout(function() {
                        alert.classList.remove('opacity-100');
                        alert.classList.add('opacity-0');
                    }, 3000); // The alert will fade out after 3 seconds

                    // After fading out, hide it completely after 1 more second
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 4000);
                }
            }

            // Trigger fade effect for success or error message
            if (successAlert) fadeAlert(successAlert);
            if (errorAlert) fadeAlert(errorAlert);
        });
    </script>


</body>

</html>
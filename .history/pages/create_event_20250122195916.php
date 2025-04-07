    <?php
    // Connect to your database
    require_once '../config/db_connection.php';

    // Variable to store any error messages
    $error_message = '';
    $success_message = '';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $event_location = $_POST['event_location'];
        $event_description = $_POST['event_description'];

        // Handle the file upload
        $event_image = '';
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
            $upload_dir = __DIR__ . '/../uploads/';  // Absolute path to the uploads directory
            $image_name = $_FILES['event_image']['name'];
            $image_tmp = $_FILES['event_image']['tmp_name'];
            $image_size = $_FILES['event_image']['size'];
            $image_type = $_FILES['event_image']['type'];

            // Check if the uploaded file is an image
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image_type, $allowed_types) && $image_size < 5000000) { // Limit to 5MB
                $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $new_image_name = uniqid() . '.' . $image_ext;
                $image_path = $upload_dir . $new_image_name;

                if (move_uploaded_file($image_tmp, $image_path)) {
                    $event_image = $new_image_name; // Save the image name to the database
                } else {
                    $error_message = "Error uploading image.";
                    exit();
                }
            } else {
                $error_message = "Invalid image file or file too large.";
                exit();
            }
        }

        // Insert event data into the database
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
        $conn->close();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Event</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">

        <!-- CSS for fade-in and fade-out animation -->
        <style>
            .fade {
                opacity: 0;
                transition: opacity 1s ease-out;
            }

            .fade.show {
                opacity: 1;
            }
        </style>

        <!-- JavaScript to show and hide alerts with fade effect -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const successMessage = document.querySelector('.success-message');
                const errorMessage = document.querySelector('.error-message');

                // Function to fade out the alert after a few seconds
                function fadeOut(element) {
                    setTimeout(function() {
                        element.classList.remove('show');
                    }, 5000); // Hide after 5 seconds
                }

                if (successMessage) {
                    successMessage.classList.add('fade', 'show'); // Fade in
                    fadeOut(successMessage); // Fade out after 5 seconds
                }

                if (errorMessage) {
                    errorMessage.classList.add('fade', 'show'); // Fade in
                    fadeOut(errorMessage); // Fade out after 5 seconds
                }
            });
        </script>
    </head>

    <body class="bg-gray-100">

        <!-- Include Sidebar from Main Dashboard -->
        <?php include('sidebar.php'); ?>
        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Open sidebar</span>
            <i class="fas fa-bars w-6 h-6"></i>
        </button>

        <div class="container mx-auto p-4 bg-white rounded-lg shadow-lg w-1/2 mt-10">
            <h1 class="text-xl font-bold mb-2">Create New Event</h1>

            <!-- Display success or error message -->
            <?php if ($success_message): ?>
                <div class="bg-green-500 text-white p-3 mb-2 rounded success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif ($error_message): ?>
                <div class="bg-red-500 text-white p-3 mb-2 rounded error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow">
                <form action="create_event.php" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="event_name" class="block text-xs font-medium text-gray-700">Event Name</label>
                            <input type="text" name="event_name" id="event_name" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="event_date" class="block text-xs font-medium text-gray-700">Event Date</label>
                            <input type="date" name="event_date" id="event_date" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="event_time" class="block text-xs font-medium text-gray-700">Event Time</label>
                            <input type="time" name="event_time" id="event_time" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="event_location" class="block text-xs font-medium text-gray-700">Event Location</label>
                            <input type="text" name="event_location" id="event_location" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        </div>

                        <div>
                            <label for="event_description" class="block text-xs font-medium text-gray-700">Event Description</label>
                            <textarea name="event_description" id="event_description" rows="2" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required></textarea>
                        </div>

                        <div>
                            <label for="event_image" class="block text-xs font-medium text-gray-700">Event Image</label>
                            <input type="file" name="event_image" id="event_image" class="mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" accept="image/*" required>
                        </div>

                        <div>
                            <button type="submit" class="w-full px-3 py-1.5 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:ring-2 focus:ring-blue-300 text-sm">Create Event</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        </div>

    </body>

    </html>
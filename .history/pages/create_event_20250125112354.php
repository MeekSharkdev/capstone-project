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
                submitButton.disabled = true;
                submitButton.textContent = "Submitting...";
            });
        });
    </script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <?php include('sidebar.php'); ?>

    <div class="bg-white rounded-lg shadow-lg w-1/2 p-6 text-center">
        <h1 class="text-xl font-bold mb-4">Create New Event</h1>

        <!-- Display success or error message -->
        <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                <?php echo $success_message; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-md font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-md font-medium text-gray-700">Event Date</label>
                    <input type="date" name="event_date" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-md font-medium text-gray-700">Event Time</label>
                    <input type="time" name="event_time" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-md font-medium text-gray-700">Event Location</label>
                    <input type="text" name="event_location" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div class="col-span-2">
                    <label class="block text-md font-medium text-gray-700">Event Description</label>
                    <textarea name="event_description" rows="2" class="w-full px-3 py-2 border rounded-md" required></textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-md font-medium text-gray-700">Event Image</label>
                    <input type="file" name="event_image" class="w-full px-3 py-2 border rounded-md" accept="image/*" required>
                </div>

                <div class="col-span-2">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Create Event</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>
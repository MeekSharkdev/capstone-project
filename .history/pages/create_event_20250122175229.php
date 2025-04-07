<?php
session_start();

// Include the database connection
include('../config/mysqli_connect.php');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle event creation form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    // Get form data
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    // Prepare SQL query to insert the event into the database
    $query = "INSERT INTO events (event_name, event_date, event_time, event_location, event_description) 
              VALUES ('$event_name', '$event_date', '$event_time', '$event_location', '$event_description')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Event created successfully!');</script>";
    } else {
        echo "<script>alert('Error creating event: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle event deletion
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];

    // Prepare SQL query to delete the event
    $query = "DELETE FROM events WHERE id = '$event_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Event deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting event: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch events for display
$query = "SELECT * FROM events";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <!-- Admin Dashboard Header -->
    <div class="bg-blue-600 text-white p-4">
        <h1 class="text-2xl font-semibold">Admin Dashboard</h1>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-6">Create New Event</h2>

        <form action="create_event.php" method="POST">
            <!-- Event Name -->
            <div class="mb-4">
                <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" id="event_name" name="event_name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Event Date -->
            <div class="mb-4">
                <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date</label>
                <input type="date" id="event_date" name="event_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Event Time -->
            <div class="mb-4">
                <label for="event_time" class="block text-sm font-medium text-gray-700">Event Time</label>
                <input type="time" id="event_time" name="event_time" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Event Location -->
            <div class="mb-4">
                <label for="event_location" class="block text-sm font-medium text-gray-700">Event Location</label>
                <input type="text" id="event_location" name="event_location" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Event Description -->
            <div class="mb-4">
                <label for="event_description" class="block text-sm font-medium text-gray-700">Event Description</label>
                <textarea id="event_description" name="event_description" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" rows="4" required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button type="submit" name="create_event" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Create Event</button>
            </div>
        </form>
    </div>

    <!-- Event List -->
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-6">Manage Events</h2>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($event = mysqli_fetch_assoc($result)) { ?>
                <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                    <h3 class="text-lg font-semibold"><?php echo $event['event_name']; ?></h3>
                    <p class="text-gray-600"><?php echo $event['event_description']; ?></p>
                    <p class="text-gray-500">Date: <?php echo $event['event_date']; ?> | Time: <?php echo $event['event_time']; ?></p>
                    <p class="text-gray-500">Location: <?php echo $event['event_location']; ?></p>
                    <a href="create_event.php?delete=<?php echo $event['id']; ?>" class="text-red-600 mt-2 inline-block hover:text-red-800">Delete</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No events found.</p>
        <?php } ?>
    </div>

    <!-- Footer -->
    <div class="bg-blue-600 text-white p-4 mt-10 text-center">
        <p>&copy; 2025 Admin Dashboard</p>
    </div>

</body>

</html>
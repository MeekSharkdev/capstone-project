<?php
include('../config/db_connection.php'); // Include your database connection

// Check if admin is logged in (add your authentication logic here)
session_start();

// Fetch all events
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS for alert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200">
    <?php include('sidebar.php'); ?>

    <div class="sm:ml-64 p-6">
        <h1 class="text-center text-2xl font-bold mb-6">Manage Events</h1>

        <!-- Show success alert if status=success in the URL -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> The event was deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                // Fade out the alert after 3 seconds
                setTimeout(function() {
                    var alertElement = document.getElementById('alert');
                    alertElement.classList.remove('show');
                    alertElement.classList.add('fade');
                }, 3000);
            </script>
        <?php endif; ?>

        <div class="mb-4">
            <a href="create_event.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Create New Event</a>
        </div>

        <!-- Events Table -->
        <div class="relative overflow-x-auto shadow-2xl sm:rounded-lg bg-white">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-black uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Event Name</th>
                        <th class="px-6 py-3">Event Date</th>
                        <th class="px-6 py-3">Date Created</th> <!-- New Column -->
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_date']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['created_at']); ?></td> <!-- New Date Created Column -->
                            <td class="px-6 py-4">
                                <a href="archive_event.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-700">Archive</a>
                                <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 ml-2">Delete</a>
                                <!-- Create New Event Button (with left margin) -->
                                <a href="create_event.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 ml-8">Create Event</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS (for alert fade-out functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
include('./config'); // Include your database connection

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
    <title>Manage Events</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Manage Events</h1>

        <div class="mb-4">
            <a href="create_event.php" class="bg-blue-500 text-white px-4 py-2 rounded">Create New Event</a>
        </div>

        <!-- Events Table -->
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Event Name</th>
                    <th class="px-4 py-2 border">Event Date</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['event_name']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['event_date']); ?></td>
                        <td class="px-4 py-2 border">
                            <a href="archive_event.php?id=<?php echo $row['event_id']; ?>" class="text-yellow-500">Archive</a> |
                            <a href="delete_event.php?id=<?php echo $row['event_id']; ?>" class="text-red-500">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
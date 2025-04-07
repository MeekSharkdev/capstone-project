<?php
include('../config/db_connection.php');

// Fetch all archived events
$query = "SELECT * FROM events WHERE is_archived = 1 ORDER BY event_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Events | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200">
    <?php include('sidebar.php'); ?>

    <div class="sm:ml-64 p-6">
        <h1 class="text-center text-2xl font-bold mb-6">Archived Events</h1>

        <!-- Show success alert if status=success in the URL -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> The event was archived successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Archived Events Table -->
        <div class="relative overflow-x-auto shadow-2xl sm:rounded-lg bg-white">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-black uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Event Name</th>
                        <th class="px-6 py-3">Event Date</th>
                        <th class="px-6 py-3">Event Picture</th>
                        <th class="px-6 py-3">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_date']); ?></td>
                            <td class="px-6 py-4">
                                <!-- Display event image -->
                                <?php if (!empty($row['event_image']) && file_exists('../uploads/' . $row['event_image'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($row['event_image']); ?>" alt="Event Image" class="w-32 h-32 object-cover">
                                <?php else: ?>
                                    No image available
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <!-- Link to view more details about the event -->
                                <a href="view_eventDetails.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">View Details</a>
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
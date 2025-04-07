<?php
include('../config/db_connection.php');

// Debugging: Check if delete_id is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST); // Debugging: Check if the delete_id is being posted
    exit(); // Stop execution for debugging purposes
}

// Check if delete ID is passed and delete the event
if (isset($_POST['delete_id']) && !empty($_POST['delete_id'])) {
    $event_id = $_POST['delete_id'];

    // Ensure event_id is numeric before executing the deletion query
    if (is_numeric($event_id)) {
        // Delete the event permanently
        $delete_query = "DELETE FROM events WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $event_id);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect back with success status
            header("Location: archive_event.php?status=deleted");
            exit();
        } else {
            // Handle error
            echo "Error deleting event.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Invalid event ID.";
    }
} else {
    echo "Event ID not specified.";
}

// Fetch all archived events
$query = "SELECT * FROM events WHERE archived = 1 ORDER BY event_date DESC";
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

        <!-- Show success alert if status=deleted in the URL -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div id="alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Success!</strong> The event was deleted permanently.
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
                        <th class="px-6 py-3">Event Location</th>
                        <th class="px-6 py-3">Event Picture</th>
                        <th class="px-6 py-3">Actions</th> <!-- Added Actions column -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_date']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['event_location']); ?></td>
                            <td class="px-3 py-2">
                                <!-- Display event image -->
                                <?php if (!empty($row['event_image']) && file_exists('../uploads/' . $row['event_image'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($row['event_image']); ?>" alt="Event Image" class="w-42 h-32 object-cover">
                                <?php else: ?>
                                    No image available
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <!-- Trigger Modal -->
                                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700" data-bs-toggle="modal" data-bs-target="#eventModal"
                                    data-id="<?php echo $row['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($row['event_name']); ?>"
                                    data-date="<?php echo htmlspecialchars($row['event_date']); ?>"
                                    data-location="<?php echo htmlspecialchars($row['event_location']); ?>"
                                    data-description="<?php echo htmlspecialchars($row['event_description']); ?>"
                                    data-image="<?php echo htmlspecialchars($row['event_image']); ?>"
                                    data-created="<?php echo htmlspecialchars($row['created_at']); ?>">
                                    View Details
                                </button>
                                <!-- Delete Button -->
                                <form method="POST" action="archive_event.php" style="display: inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>" />
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 ml-2" onclick="return confirm('Are you sure you want to permanently delete this event?')">Delete</button>
                                    
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-2xl font-bold text-gray-800 mb-4" id="eventModalLabel">
                        <span class="text-blue-600">Event</span> <span class="text-blue-800">Details</span>
                    </h3>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Event Name:</strong> <span id="event-name"></span></p>
                    <p><strong>Event Date:</strong> <span id="event-date"></span></p>
                    <p><strong>Event Location:</strong> <span id="event-location"></span></p>
                    <p><strong>Event Description:</strong> <span id="event-description"></span></p>
                    <p><strong>Created At:</strong> <span id="event-created"></span></p>
                    <div class="mt-4">
                        <strong>Event Image:</strong><br>
                        <img id="event-image" src="" alt="Event Image" class="w-full h-64 object-cover mt-2 rounded-lg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to populate modal with event details -->
    <script>
        var eventModal = document.getElementById('eventModal')
        eventModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget // Button that triggered the modal
            var id = button.getAttribute('data-id')
            var name = button.getAttribute('data-name')
            var date = button.getAttribute('data-date')
            var location = button.getAttribute('data-location')
            var description = button.getAttribute('data-description')
            var image = button.getAttribute('data-image')
            var created = button.getAttribute('data-created')

            var modalTitle = eventModal.querySelector('.modal-title')
            var eventName = eventModal.querySelector('#event-name')
            var eventDate = eventModal.querySelector('#event-date')
            var eventLocation = eventModal.querySelector('#event-location')
            var eventDescription = eventModal.querySelector('#event-description')
            var eventImage = eventModal.querySelector('#event-image')
            var eventCreated = eventModal.querySelector('#event-created')

            modalTitle.textContent = 'Event Details: ' + name
            eventName.textContent = name
            eventDate.textContent = date
            eventLocation.textContent = location
            eventDescription.textContent = description
            eventCreated.textContent = created
            eventImage.src = (image && image != 'No image available') ? '../uploads/' + image : 'path_to_default_image.jpg'
        })
    </script>
</body>

</html>
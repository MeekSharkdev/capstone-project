<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch booking data from the database
$query = "SELECT bookings.bookings_id, 
                 bookings.firstname, 
                 bookings.lastname, 
                 bookings.phonenumber, 
                 bookings.email, 
                 bookings.purpose, 
                 bookings.booking_date
          FROM bookings";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if there was an error with the query
if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Schedule Records</title>
    <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Include Sidebar from Main Dashboard -->
<?php include('sidebar.php'); ?>

<!-- Main Content -->
<div class="ml-64 p-6">
    <div class="container">
        <h2 class="text-2xl font-bold mb-4">Booking Records</h2>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto text-sm text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">First Name</th>
                        <th class="px-4 py-2">Last Name</th>
                        <th class="px-4 py-2">Phone Number</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Purpose</th>
                        <th class="px-4 py-2">Booking Date</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr bookings_id='row" . $row['bookings_id'] . "' class='hover:bg-gray-100 transition-all'>";
                            echo "<td class='border px-4 py-2'>" . $row['bookings_id'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['firstname'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['lastname'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['phonenumber'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['email'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['purpose'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['booking_date'] . "</td>";
                            echo "<td class='border px-4 py-2'>
                                    <button class='bg-blue-500 text-white py-1 px-4 rounded view-btn' data-bs-toggle='modal' data-bs-target='#viewModal' 
                                    data-id='" . $row['bookings_id'] . "' 
                                    data-firstname='" . $row['firstname'] . "' 
                                    data-lastname='" . $row['lastname'] . "' 
                                    data-phonenumber='" . $row['phonenumber'] . "' 
                                    data-email='" . $row['email'] . "' 
                                    data-purpose='" . $row['purpose'] . "' 
                                    data-date='" . $row['booking_date'] . "'>
                                    View
                                    </button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center py-4'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                <p><strong>Current Date:</strong> <span id="modalDate"></span></p>
                <div class="mt-3">
                    <label for="newDateInput" class="form-label">Reschedule to:</label>
                    <input type="date" id="newDateInput" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirmRescheduleBtn">Confirm Schedule</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let selectedId; // Declare selectedId globally for use in different event handlers

// Event listener to capture the booking data when the "View" button is clicked
document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', () => {
        selectedId = button.getAttribute('data-id');
        document.getElementById('modalName').textContent = button.getAttribute('data-firstname') + " " + button.getAttribute('data-lastname');
        document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
        document.getElementById('modalPurpose').textContent = button.getAttribute('data-purpose');
        document.getElementById('modalDate').textContent = button.getAttribute('data-date');
        document.getElementById('newDateInput').value = button.getAttribute('data-date');
    });
});

// Event listener to handle reschedule confirmation when the "Confirm Schedule" button is clicked
document.getElementById('confirmRescheduleBtn').addEventListener('click', () => {
    if (!selectedId) {
        alert('No booking selected!');
        return;
    }

    const newDate = document.getElementById('newDateInput').value; // Get date from date picker
    if (newDate) {
        fetch('update_booking_date.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: selectedId,
                date: newDate,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Schedule successfully rescheduled to ' + newDate);
                document.getElementById('modalDate').textContent = newDate;
                $('#viewModal').modal('hide'); // Close the modal after rescheduling
                location.reload(); // Reload to update the table
            } else {
                alert('Failed to reschedule: ' + data.message);
            }
        })
    }
});
</script>

</body>
</html>

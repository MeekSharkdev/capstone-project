<?php
session_start();

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

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Schedule Records</title>
    <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php include('sidebar.php'); ?>

<div class="main-content">
    <div class="container">
        <h2 class="text-xl font-semibold text-center text-black">Appointment Records</h2>
        <div class="table-responsive">
            <table class="table table-striped" id="bookingTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Phone number</th>
                        <th>Email</th>
                        <th>Booking Purpose</th>
                        <th>Booking Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr bookings_id="row<?php echo $row['bookings_id']; ?>">
                            <td><?php echo $row['bookings_id']; ?></td>
                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo $row['phonenumber']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['purpose']; ?></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td>
                                <button class="btn btn-info view-btn" 
                                    data-id="<?php echo $row['bookings_id']; ?>" 
                                    data-firstname="<?php echo $row['firstname']; ?>" 
                                    data-lastname="<?php echo $row['lastname']; ?>" 
                                    data-email="<?php echo $row['email']; ?>" 
                                    data-purpose="<?php echo $row['purpose']; ?>" 
                                    data-date="<?php echo $row['booking_date']; ?>">
                                    View
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h3 class="text-lg font-semibold">Booking Details</h3>
        <p><strong>Name:</strong> <span id="modalName"></span></p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <label for="newDateInput">New Date:</label>
        <input type="date" id="newDateInput" class="form-control">
        <label for="newPurposeInput">New Purpose:</label>
        <input type="text" id="newPurposeInput" class="form-control">
        <div class="flex justify-between mt-3">
            <button id="confirmRescheduleBtn" class="btn btn-success">Confirm</button>
            <button id="closeModalBtn" class="btn btn-danger">Close</button>
        </div>
    </div>
</div>

<script>
    let selectedId;

    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', () => {
            selectedId = button.getAttribute('data-id');
            document.getElementById('modalName').textContent = button.getAttribute('data-firstname') + " " + button.getAttribute('data-lastname');
            document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
            document.getElementById('modalPurpose').textContent = button.getAttribute('data-purpose');
            document.getElementById('modalDate').textContent = button.getAttribute('data-date');
            document.getElementById('newDateInput').value = button.getAttribute('data-date');
            document.getElementById('newPurposeInput').value = button.getAttribute('data-purpose');
            document.getElementById('viewModal').classList.remove('hidden');
        });
    });

    document.getElementById('closeModalBtn').addEventListener('click', () => {
        document.getElementById('viewModal').classList.add('hidden');
    });

    document.getElementById('confirmRescheduleBtn').addEventListener('click', () => {
        if (!selectedId) return;
        const newDate = document.getElementById('newDateInput').value;
        const newPurpose = document.getElementById('newPurposeInput').value;
        fetch('update_booking_date_and_purpose.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: selectedId, date: newDate, purpose: newPurpose})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Updated successfully!');
                document.querySelector(`[bookings_id='row${selectedId}'] td:nth-child(6)`).textContent = newPurpose;
                document.querySelector(`[bookings_id='row${selectedId}'] td:nth-child(7)`).textContent = newDate;
                document.getElementById('viewModal').classList.add('hidden');
            } else {
                alert('Update failed: ' + data.message);
            }
        });
    });
</script>
</body>
</html>

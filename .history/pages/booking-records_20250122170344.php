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
    <title>Appointment Schedule Records</title>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/home.css">
    <link rel="stylesheet" href="styles.css?v=1.0.1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #E5E7EB;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            /* Adjust for sidebar width */
            padding: 20px;
        }

        /* Table Styles */
        .table-responsive {
            margin-top: 30px;
            border-radius: 15px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);

        }

        .table th,
        .table td {
            text-align: center;
        }

        .table td {
            font-family: sans-serif;
            color: rgb(105, 105, 105);
            font-size: 14px;
        }

        /* Search Bar Styles */
        .search-bar {
            margin-bottom: 20px;
            width: 100%;
        }
    </style>
</head>

<body>

    <!-- Include Sidebar from Main Dashboard -->
    <?php include('sidebar.php'); ?>
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <i class="fas fa-bars w-6 h-6"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2 class="text-xl font-semibold text-center text-black">Appointment Records</h2>

            <!-- Search Bar -->
            <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search for names, phone numbers, or email...">

            <div class="table-responsive">
                <table class="table table-striped caption-top table-hover" id="bookingTable">
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
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr bookings_id='row" . $row['bookings_id'] . "'>";
                                echo "<td>" . $row['bookings_id'] . "</td>";
                                echo "<td>" . $row['firstname'] . "</td>";
                                echo "<td>" . $row['lastname'] . "</td>";
                                echo "<td>" . $row['phonenumber'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['purpose'] . "</td>";
                                echo "<td>" . $row['booking_date'] . "</td>";
                                echo "<td>
            <button class='btn btn-primary view-btn' 
            data-bs-toggle='modal' 
            data-bs-target='#viewModal' 
            data-id='" . $row['bookings_id'] . "' 
            data-firstname='" . $row['firstname'] . "' 
            data-lastname='" . $row['lastname'] . "' 
            data-phonenumber='" . $row['phonenumber'] . "' 
            data-email='" . $row['email'] . "' 
            data-purpose='" . $row['purpose'] . "' 
            data-date='" . $row['booking_date'] . "'>
            Modify
            </button>
            
            <button class='btn btn-primary new-btn' type='button' data-bs-toggle='modal' data-bs-target='#editEmailModal' 
            data-id='" . $row['bookings_id'] . "' 
            data-firstname='" . $row['firstname'] . "' 
            data-lastname='" . $row['lastname'] . "' 
            data-email='" . $row['email'] . "'>
                Edit
            </button>
        </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No bookings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Email Editing -->
            <div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEmailModalLabel">Edit Confirmation Email</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="emailForm">
                                <input type="hidden" id="bookingId" name="bookingId">
                                <div class="mb-3">
                                    <label for="recipientEmail" class="form-label">Recipient Email</label>
                                    <input type="email" class="form-control" id="recipientEmail" name="recipientEmail" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="emailSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="emailSubject" name="emailSubject">
                                </div>
                                <div class="mb-3">
                                    <label for="emailBody" class="form-label">Body</label>
                                    <textarea class="form-control" id="emailBody" name="emailBody" rows="5"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Scheduling/Rescheduling -->
            <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">View/Modify Booking</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="rescheduleForm">
                                <input type="hidden" id="bookingId" name="bookingId">
                                <div class="mb-3">
                                    <label for="modalName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="modalName" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="modalEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="modalEmail" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="modalPurpose" class="form-label">Purpose</label>
                                    <input type="text" class="form-control" id="modalPurpose" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="modalDate" class="form-label">Current Booking Date</label>
                                    <input type="text" class="form-control" id="modalDate" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="newDateInput" class="form-label">New Date</label>
                                    <input type="date" class="form-control" id="newDateInput" name="newDateInput" required>
                                </div>
                                <button type="submit" id="confirmRescheduleBtn" class="btn btn-primary">Confirm Schedule</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript to Handle Modal and Form -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Populate the modal with the correct data when the "Modify" button is clicked
                document.querySelectorAll('.view-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const bookingId = button.getAttribute('data-id');
                        const firstname = button.getAttribute('data-firstname');
                        const lastname = button.getAttribute('data-lastname');
                        const email = button.getAttribute('data-email');
                        const purpose = button.getAttribute('data-purpose');
                        const bookingDate = button.getAttribute('data-date');

                        // Populate the view modal with booking data
                        document.getElementById('viewModal').querySelector('#modalName').value = `${firstname} ${lastname}`;
                        document.getElementById('viewModal').querySelector('#modalEmail').value = email;
                        document.getElementById('viewModal').querySelector('#modalPurpose').value = purpose;
                        document.getElementById('viewModal').querySelector('#modalDate').value = bookingDate;

                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById('viewModal'));
                        modal.show();
                    });
                });

                // Handle reschedule confirmation when the "Confirm Schedule" button is clicked
                document.getElementById('confirmRescheduleBtn').addEventListener('click', function(event) {
                    event.preventDefault();

                    const bookingId = document.getElementById('bookingId').value; // Get the booking ID
                    const newDate = document.getElementById('newDateInput').value; // Get the new date

                    if (!bookingId || !newDate) {
                        alert("Please fill out all fields.");
                        return;
                    }

                    // Send the new date and booking ID to the server
                    fetch('update_booking_date.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: bookingId,
                                newDate: newDate
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Schedule successfully rescheduled to ' + newDate);
                                document.getElementById('modalDate').value = newDate;
                                const modal = new bootstrap.Modal(document.getElementById('viewModal'));
                                modal.hide();
                                location.reload();
                            } else {
                                alert('Failed to reschedule: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while rescheduling.');
                        });
                });
            </script>



</body>

</html>
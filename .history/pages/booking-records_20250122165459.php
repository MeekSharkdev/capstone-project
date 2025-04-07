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
                <!-- Modify Button to open View Modal -->
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
                
                <!-- Edit Button to open Edit Modal -->
                <button class='btn btn-primary new-btn' 
                        type='button' 
                        data-bs-toggle='modal' 
                        data-bs-target='#editEmailModal' 
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

                    <!-- View Modal to display booking details -->
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
                                    <p><strong>Booking Date:</strong> <span id="modalDate"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript to Handle Modals and Form -->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

                    <script>
                        // Handle Edit Button: Populate the Edit Modal
                        document.querySelectorAll('.new-btn').forEach(button => {
                            button.addEventListener('click', () => {
                                const bookingId = button.getAttribute('data-id');
                                const recipientEmail = button.getAttribute('data-email');
                                const firstname = button.getAttribute('data-firstname');
                                const lastname = button.getAttribute('data-lastname');

                                // Populate the modal form fields
                                document.getElementById('bookingId').value = bookingId;
                                document.getElementById('recipientEmail').value = recipientEmail;
                                document.getElementById('emailSubject').value = `Booking Confirmation for ${firstname} ${lastname}`;
                                document.getElementById('emailBody').value = `Dear ${firstname} ${lastname},\n\nThank you for booking. Your appointment is confirmed.`;
                            });
                        });

                        // Handle Submit Button in Edit Modal
                        document.getElementById('emailForm').addEventListener('submit', function(event) {
                            event.preventDefault();

                            const bookingId = document.getElementById('bookingId').value;
                            const subject = document.getElementById('emailSubject').value;
                            const body = document.getElementById('emailBody').value;
                            const recipientEmail = document.getElementById('recipientEmail').value;

                            // Send email via AJAX
                            fetch('send_confirmation_email.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        bookingId,
                                        subject,
                                        body,
                                        recipientEmail
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Email successfully sent!');
                                        const modal = new bootstrap.Modal(document.getElementById('editEmailModal'));
                                        modal.hide();
                                    } else {
                                        alert('Failed to send email: ' + data.message);
                                    }
                                });
                        });

                        // Handle Modify Button: Populate the View Modal
                        document.querySelectorAll('.view-btn').forEach(button => {
                            button.addEventListener('click', () => {
                                const bookingId = button.getAttribute('data-id');
                                const firstname = button.getAttribute('data-firstname');
                                const lastname = button.getAttribute('data-lastname');
                                const email = button.getAttribute('data-email');
                                const purpose = button.getAttribute('data-purpose');
                                const bookingDate = button.getAttribute('data-date');

                                // Populate the View Modal
                                document.getElementById('modalName').textContent = `${firstname} ${lastname}`;
                                document.getElementById('modalEmail').textContent = email;
                                document.getElementById('modalPurpose').textContent = purpose;
                                document.getElementById('modalDate').textContent = bookingDate;

                                // Show the modal
                                const modal = new bootstrap.Modal(document.getElementById('viewModal'));
                                modal.show();
                            });
                        });
                    </script>



</body>

</html>
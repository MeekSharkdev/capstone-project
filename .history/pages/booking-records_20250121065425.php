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
    <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
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
        }

        /* Main Content */
        .main-content {
            margin-left: 280px; /* Adjust for sidebar width */
            padding: 20px;
        }

        /* Table Styles */
        .table-responsive {
            margin-top: 30px;
        }

        .table th, .table td {
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
                                    <button class='btn btn-info view-btn' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#viewModal' 
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
                        echo "<tr><td colspan='8'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal toggle -->
<button id="viewBtn" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
  View Booking
</button>

<!-- Modal -->
<div id="viewModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-lg"> <!-- Adjusted width to max-w-lg for medium size -->
        <!-- Modal content -->
        <div class="bg-gray-700 rounded-lg shadow-lg dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Booking Details
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" id="closeModalBtn">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                <p><strong>Current Date:</strong> <span id="modalDate"></span></p>
                <div class="mt-3">
                    <label for="newDateInput" class="block text-sm font-medium text-gray-900 dark:text-white">Reschedule to:</label>
                    <input type="date" id="newDateInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-between p-4 border-t dark:border-gray-600">
                <button type="button" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" id="confirmRescheduleBtn">
                    Confirm Schedule
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Tailwind JS for Modal Toggle -->
<script>
    let selectedId; // Declare selectedId globally for use in different event handlers

    // Modal open and close functionality
    const viewBtn = document.getElementById('viewBtn');
    const modal = document.getElementById('viewModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // Open modal when the view button is clicked
    viewBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Close modal when the close button is clicked
  closeModalBtn.addEventListener('click', () => {
    modal.classList.add('hidden');  // Hide modal
});


    // Event listener to capture the booking data when the "View" button is clicked
    document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', () => {
        selectedId = button.getAttribute('data-id');
        document.getElementById('modalName').textContent = button.getAttribute('data-firstname') + " " + button.getAttribute('data-lastname');
        document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
        document.getElementById('modalPurpose').textContent = button.getAttribute('data-purpose');
        document.getElementById('modalDate').textContent = button.getAttribute('data-date');
        document.getElementById('newDateInput').value = button.getAttribute('data-date');
        modal.classList.remove('hidden');  // Show modal
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
                    modal.classList.add('hidden'); // Close the modal after rescheduling
                    location.reload(); // Reload to update the table
                } else {
                    alert('Failed to reschedule: ' + data.message);
                }
            });
        }
    });
</script>


</body>
</html>

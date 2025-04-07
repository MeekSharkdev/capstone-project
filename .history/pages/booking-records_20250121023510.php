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
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">

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
                            echo "<tr class='hover:bg-gray-100 transition-all'>";
                            echo "<td class='border px-4 py-2'>" . $row['bookings_id'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['firstname'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['lastname'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['phonenumber'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['email'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['purpose'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['booking_date'] . "</td>";
                            echo "<td class='border px-4 py-2'>
                                    <button class='view-btn bg-blue-500 text-white py-1 px-4 rounded' data-modal-target='crud-modal' data-modal-toggle='crud-modal' 
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

<!-- Modal (Tailwind version) -->
<div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Booking Details
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                <p><strong>Current Date:</strong> <span id="modalDate"></span></p>
                <div class="mt-3">
                    <label for="newDateInput" class="form-label">Reschedule to:</label>
                    <input type="date" id="newDateInput" class="form-control bg-gray-100 border border-gray-300 p-2 rounded-md">
                </div>
            </div>
            <div class="flex justify-end p-4 md:p-5 border-t rounded-b dark:border-gray-600">
                <button type="button" class="bg-blue-500 text-white py-2 px-4 rounded-md" id="confirmRescheduleBtn">Confirm Schedule</button>
                <button type="button" class="bg-gray-400 text-white py-2 px-4 rounded-md ml-2" data-modal-toggle="crud-modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Event listener to capture the booking data when the "View" button is clicked
document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', () => {
        const bookingData = button.dataset;
        document.getElementById('modalName').textContent = `${bookingData.firstname} ${bookingData.lastname}`;
        document.getElementById('modalEmail').textContent = bookingData.email;
        document.getElementById('modalPurpose').textContent = bookingData.purpose;
        document.getElementById('modalDate').textContent = bookingData.date;
        document.getElementById('newDateInput').value = bookingData.date;
    });
});

// Event listener to handle reschedule confirmation when the "Confirm Schedule" button is clicked
document.getElementById('confirmRescheduleBtn').addEventListener('click', () => {
    const selectedId = document.querySelector('.view-btn').getAttribute('data-id');
    const newDate = document.getElementById('newDateInput').value;
    if (selectedId && newDate) {
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
                document.getElementById('crud-modal').classList.add('hidden'); // Close modal after rescheduling
                location.reload();
            } else {
                alert('Failed to reschedule: ' + data.message);
            }
        });
    }
});
</script>

</body>
</html>

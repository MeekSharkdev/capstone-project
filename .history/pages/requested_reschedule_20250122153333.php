<?php
session_start();
include('../config/mysqli_connect.php');

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT bookings.bookings_id, 
                 bookings.firstname, 
                 bookings.lastname, 
                 bookings.phonenumber, 
                 bookings.email, 
                 bookings.purpose, 
                 bookings.booking_date,
                 request_reschedule.requested_date
          FROM bookings
          LEFT JOIN request_reschedule ON bookings.bookings_id = request_reschedule.booking_id";

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
  <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>

<body class="bg-gray-200">
  <?php include('sidebar.php'); ?>
  <div class="main-content p-6">
    <h2 class="text-xl font-semibold text-center text-black">Appointment Records</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border rounded-lg">
        <thead>
          <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Purpose</th>
            <th>Current Date</th>
            <th>Requested Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?php echo $row['bookings_id']; ?></td>
              <td><?php echo $row['firstname']; ?></td>
              <td><?php echo $row['lastname']; ?></td>
              <td><?php echo $row['phonenumber']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['purpose']; ?></td>
              <td><?php echo $row['booking_date']; ?></td>
              <td><?php echo $row['requested_date'] ? $row['requested_date'] : 'N/A'; ?></td>
              <td>
                <button class="btn btn-primary view-btn"
                  data-id="<?php echo $row['bookings_id']; ?>"
                  data-firstname="<?php echo $row['firstname']; ?>"
                  data-lastname="<?php echo $row['lastname']; ?>"
                  data-email="<?php echo $row['email']; ?>"
                  data-purpose="<?php echo $row['purpose']; ?>"
                  data-date="<?php echo $row['booking_date']; ?>"
                  data-requested-date="<?php echo $row['requested_date']; ?>"
                  data-bs-toggle="modal" data-bs-target="#viewModal">
                  View
                </button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div id="viewModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-lg">
      <div class="bg-white rounded-lg shadow-xl">
        <div class="p-4 border-b">
          <h3 class="text-lg font-semibold">Booking Details</h3>
          <button id="closeModalBtn">✖</button>
        </div>
        <div class="p-4">
          <p><strong>Name:</strong> <span id="modalName"></span></p>
          <p><strong>Email:</strong> <span id="modalEmail"></span></p>
          <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
          <p><strong>Current Date:</strong> <span id="modalDate"></span></p>
          <p><strong>Requested Date:</strong> <span id="modalRequestedDate"></span></p>
          <label>New Request Date:</label>
          <input type="date" id="newDateInput" class="border p-2 rounded w-full">
        </div>
        <div class="p-4 border-t text-center">
          <button id="confirmRescheduleBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Confirm Schedule</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.view-btn').forEach(button => {
      button.addEventListener('click', () => {
        document.getElementById('modalName').textContent = button.getAttribute('data-firstname') + " " + button.getAttribute('data-lastname');
        document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
        document.getElementById('modalPurpose').textContent = button.getAttribute('data-purpose');
        document.getElementById('modalDate').textContent = button.getAttribute('data-date');
        document.getElementById('modalRequestedDate').textContent = button.getAttribute('data-requested-date') || 'N/A';
        document.getElementById('newDateInput').value = button.getAttribute('data-requested-date') || '';
        document.getElementById('viewModal').classList.remove('hidden');
      });
    });

    document.getElementById('closeModalBtn').addEventListener('click', () => {
      document.getElementById('viewModal').classList.add('hidden');
    });

    document.getElementById('confirmRescheduleBtn').addEventListener('click', () => {
      let selectedId = document.querySelector('.view-btn[data-id]').getAttribute('data-id');
      let newDate = document.getElementById('newDateInput').value;
      if (newDate) {
        fetch('update_booking_date.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id: selectedId,
            date: newDate
          })
        }).then(response => response.json()).then(data => {
          if (data.success) {
            alert('Schedule successfully updated');
            location.reload();
          } else {
            alert('Failed to update schedule');
          }
        });
      }
    });
  </script>
</body>

</html>
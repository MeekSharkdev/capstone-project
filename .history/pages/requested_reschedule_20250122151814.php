<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to get reschedule requests
$query = "
    SELECT 
        id, 
        firstname,
        middlename,
        lastname,
        email,
        date,
        purpose,
        reason
    FROM 
        request_reschedule
    ORDER BY id DESC";

$result = $conn->query($query);

// Check for query errors
if (!$result) {
  die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requested Reschedule</title>
  <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-200">

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <div class="sm:ml-64 p-6">
    <h2 class="text-center text-2xl font-bold mb-6">Requested Reschedule</h2>

    <!-- Table Container -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
      <table class="w-full text-sm text-left text-gray-500" id="rescheduleTable">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">First Name</th>
            <th class="px-6 py-3">Middle Name</th>
            <th class="px-6 py-3">Last Name</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Date</th>
            <th class="px-6 py-3">Purpose</th>
            <th class="px-6 py-3">Reason</th>
            <th class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="bg-white border-b hover:bg-gray-50">
              <td class="px-6 py-4"><?= htmlspecialchars($row['id']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['firstname']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['middlename']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['lastname']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['date']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['purpose']); ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($row['reason']); ?></td>
              <td class="px-6 py-4">
                <button class="text-blue-500 hover:underline" onclick="openModal(<?= $row['id']; ?>, '<?= $row['date']; ?>')">View</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Reschedule Modal -->
  <div id="rescheduleModal" class="fixed inset-0 hidden bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
      <h2 class="text-xl font-bold mb-4">Reschedule Booking</h2>
      <form action="update_booking.php" method="POST">
        <input type="hidden" id="bookingId" name="booking_id">
        <label class="block mb-2">New Date:</label>
        <input type="date" id="newDate" name="new_date" required class="w-full border rounded-lg p-2 mb-4">
        <div class="flex justify-end">
          <button type="button" class="mr-2 bg-gray-400 text-white px-4 py-2 rounded" onclick="closeModal()">Cancel</button>
          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(id, date) {
      document.getElementById('bookingId').value = id;
      document.getElementById('newDate').value = date;
      document.getElementById('rescheduleModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('rescheduleModal').classList.add('hidden');
    }
  </script>
</body>

</html>
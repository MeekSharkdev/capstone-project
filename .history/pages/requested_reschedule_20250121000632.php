<?php
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requested Reschedule</title>
  <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.1.8/dist/tailwind.min.js"></script>
</head>

<body class="bg-gray-100">

  <!-- Navbar -->
  <nav class="bg-gray-800 text-white p-4">
    <div class="flex justify-between items-center">
      <a class="text-xl font-bold" href="#">Solehub Admin</a>
      <ul class="flex space-x-6">
        <li><a class="hover:text-gray-400" href="#">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="fixed top-0 left-0 h-full bg-gray-800 text-white w-64 p-6">
    <div class="space-y-6">
      <a href="#" class="block p-2 hover:bg-gray-700">Dashboard</a>
      <a href="./admin_dashboard.php" class="block p-2 hover:bg-gray-700">Booking Schedule Records</a>
      <a href="./manage_users.php" class="block p-2 hover:bg-gray-700">User Management</a>
      <a href="./requested_reschedule.php" class="block p-2 bg-gray-700">Requested Reschedule</a>
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="ml-64 p-6">
    <h2 class="text-2xl font-semibold mb-6 text-center">Requested Reschedule</h2>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-500">
        <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
          <tr>
            <th class="px-6 py-3 border-b">ID</th>
            <th class="px-6 py-3 border-b">First Name</th>
            <th class="px-6 py-3 border-b">Middle Name</th>
            <th class="px-6 py-3 border-b">Last Name</th>
            <th class="px-6 py-3 border-b">Email</th>
            <th class="px-6 py-3 border-b">Date</th>
            <th class="px-6 py-3 border-b">Purpose</th>
            <th class="px-6 py-3 border-b">Reason</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT id, firstname, middlename, lastname, email, date, purpose, reason FROM request_reschedule ORDER BY id DESC";
          $result = $conn->query($sql);

          if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
          ?>
              <tr class="bg-white hover:bg-gray-100 border-b">
                <td class="px-6 py-4"><?= htmlspecialchars($row['id']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['firstname']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['middlename']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['lastname']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['date']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['purpose']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['reason']); ?></td>
              </tr>
          <?php endwhile; ?>
          <?php else: ?>
              <tr>
                <td colspan="8" class="text-center px-6 py-4">No reschedule requests found.</td>
              </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>

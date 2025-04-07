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
  <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-200">

<!-- Sidebar -->
<?php include('sidebar.php'); ?>
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
</button>

  <!-- Main Content Area -->
  <div class="sm:ml-64 p-6">
    <h2 class="text-center text-2xl font-bold mb-6">Requested Reschedule</h2>

    <!-- Search Bar -->
    <div class="mb-4 flex justify-center">
      <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for names or details..." class="px-4 py-2 border rounded-lg w-full md:w-1/2 text-gray-700">
    </div>

    <!-- Table Container -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
      <table class="w-full text-sm text-left text-gray-500" id="rescheduleTable">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3">ID</th>
            <th scope="col" class="px-6 py-3">First Name</th>
            <th scope="col" class="px-6 py-3">Middle Name</th>
            <th scope="col" class="px-6 py-3">Last Name</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" class="px-6 py-3">Date</th>
            <th scope="col" class="px-6 py-3">Purpose</th>
            <th scope="col" class="px-6 py-3">Reason</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
          ?>
              <tr class="bg-white border-b hover:bg-gray-50">
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

  <script>
    function filterTable() {
      const input = document.getElementById("searchInput");
      const filter = input.value.toLowerCase();
      const table = document.getElementById("rescheduleTable");
      const tr = table.getElementsByTagName("tr");

      for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName("td");
        let showRow = false;

        for (let j = 0; j < td.length; j++) {
          if (td[j]) {
            const textValue = td[j].textContent || td[j].innerText;
            if (textValue.toLowerCase().indexOf(filter) > -1) {
              showRow = true;
              break;
            }
          }
        }

        tr[i].style.display = showRow ? "" : "none";
      }
    }
  </script>

</body>

</html>

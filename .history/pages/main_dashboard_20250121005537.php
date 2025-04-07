<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get total registered users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = mysqli_query($conn, $userQuery);
$totalUsers = mysqli_fetch_assoc($userResult)['total_users'];

// Query to get the number of total bookings
$bookingQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
$bookingResult = mysqli_query($conn, $bookingQuery);
$totalBookings = mysqli_fetch_assoc($bookingResult)['total_bookings'];

// Query to get counts of users marked as completed
$statusQuery = "SELECT COUNT(*) AS completed_users FROM users WHERE status = 'completed'";
$statusResult = mysqli_query($conn, $statusQuery);
$completedUsers = mysqli_fetch_assoc($statusResult)['completed_users'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-200">
  <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
  </button>

  <aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-gray-800 text-white">
    <div class="h-full px-3 py-4 overflow-y-auto">
      <h4 class="text-center text-lg font-bold mb-4">Admin Panel</h4>
      <ul class="space-y-2">
        <li><a href="./main_dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a></li>
        <li><a href="./booking-records.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-calendar-alt mr-2"></i> Appointment Management</a></li>
        <li><a href="./rescheduling-request.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-redo-alt mr-2"></i> Rescheduling Request</a></li>
        <li><a href="./manage_users.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-users mr-2"></i> Manage Users</a></li>
        <li><a href="./manage_admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-user-shield mr-2"></i> Manage Admins</a></li>
        <li><a href="./create_admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-user-shield mr-2"></i> Create Admin</a></li>
        <li><a href="../logout-admin.php" class="flex items-center px-4 py-2 hover:bg-red-700 rounded"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
      </ul>
    </div>
  </aside>

  <div class="sm:ml-64 p-6">
    <h2 class="text-center text-2xl font-bold mb-6">Dashboard Overview</h2>
    <div class="w-full max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-2xl">
      <canvas id="combinedBarChart" class="h-96"></canvas>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('combinedBarChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Registered Users', 'Total Bookings', 'Completed Users'],
        datasets: [{
          label: 'Counts',
          data: [<?php echo $totalUsers; ?>, <?php echo $totalBookings; ?>, <?php echo $completedUsers; ?>],
          backgroundColor: ['#007bff', '#28a745', '#ffc107'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: Math.ceil(Math.max(<?php echo $totalUsers; ?>, <?php echo $totalBookings; ?>, <?php echo $completedUsers; ?>) / 5)
            }
          }
        }
      }
    });
  </script>
</body>
</html>

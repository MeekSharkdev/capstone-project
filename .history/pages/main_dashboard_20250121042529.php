<?php
session_start();

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
  <?php include('sidebar.php'); ?>
  <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
  </button>

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

  <div class="text-center mt-6 space-y-4">
    <div class="text-lg font-semibold text-gray-700">
        <span class="text-blue-500">Total Registered Users:</span> 
        <span class="font-bold text-yellow-500"><?php echo $totalUsers; ?></span>
    </div>
    <div class="text-lg font-semibold text-gray-700">
        <span class="text-green-500">Total Bookings:</span> 
        <span class="font-bold text-yellow-500"><?php echo $totalBookings; ?></span>
    </div>
    <div class="text-lg font-semibold text-gray-700">
        <span class="text-purple-500">Completed Users:</span> 
        <span class="font-bold text-yellow-500"><?php echo $completedUsers; ?></span>
    </div>
</div>
</body>
</html>

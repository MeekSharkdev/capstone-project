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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Sidebar Styles */
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      padding-top: 20px;
      color: white;
      z-index: 1000;
    }

    .sidebar a {
      color: white;
      padding: 15px;
      text-decoration: none;
      font-size: 18px;
      display: block;
    }

    .sidebar a:hover {
      background-color: #575d63;
    }

    /* Main Content */
    .main-content {
      margin-left: 270px;
      padding: 20px;
    }

    /* Graph Styling */
    #chart-container {
      max-width: 600px;
      height: 400px;
      margin: auto;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    
    <h4 class="text-center mt-3">Admin Panel</h4>
    <a href="./main_dashboard.php">Dashboard</a>
    <a href="./booking-records.php">Booking Schedule Records</a>
    <a href="./manage_users.php">User Management</a>
    <a href="./verifying-admin.php">Admin Accounts Verifying</a>
    <a href="../logout-admin.php">Logout</a>

  </div>

  <!-- Main Content Area -->
  <div class="main-content">
    <h2 class="text-center mb-4">Dashboard Overview</h2>

    <!-- Combined Bar Chart -->
    <div id="chart-container">
      <canvas id="combinedBarChart"></canvas>
    </div>
  </div>

  <!-- Chart.js Logic -->
  <script>
    const ctx = document.getElementById('combinedBarChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Registered Users', 'Total Bookings', 'Completed Users'],
        datasets: [
          {
            label: 'Counts',
            data: [<?php echo $totalUsers; ?>, <?php echo $totalBookings; ?>, <?php echo $completedUsers; ?>],
            backgroundColor: ['#007bff', '#28a745', '#ffc107'],
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            beginAtZero: true,
          },
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: Math.ceil(Math.max(<?php echo $totalUsers; ?>, <?php echo $totalBookings; ?>, <?php echo $completedUsers; ?>) / 5) // Adjust Y step scale
            }
          }
        }
      }
    });
  </script>
</body>

</html>

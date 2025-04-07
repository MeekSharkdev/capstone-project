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
    <a href="./main_dashboard.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M3 2h18v18H3z"/>
        <path d="M6 14l4-4 2 2 4-4"/>
    </svg> Dashboard
</a>

<a href="./booking-records.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M2 3h20v18H2z"/>
        <path d="M6 8h12v2H6zM6 12h12v2H6zM6 16h12v2H6z"/>
    </svg> Booking Schedule Records
</a>

<a href="./manage_users.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <circle cx="12" cy="7" r="4"/>
        <path d="M5 20c0-2.21 3.58-4 7-4s7 1.79 7 4v2H5v-2z"/>
    </svg> User Management
</a>

<a href="./verifying-admin.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M12 17.27L18.18 21 21 18.18l-6.27-6.18L21 5.82 18.18 3l-6.27 6.18L5.82 3 3 5.82l6.27 6.18L3 18.18l2.82 2.82L12 17.27z"/>
    </svg> Admin Accounts Verifying
</a>

<a href="../logout-admin.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
        <path d="M16 9v4H7v-4h9zM13 2h-2v7H4v2h7v7h2v-7h7V9h-7z"/>
    </svg> Logout
</a>


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

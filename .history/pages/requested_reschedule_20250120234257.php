<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get reschedule requests from the 'requested_reschedule' table
$query = "
    SELECT 
        users.firstname, 
        users.lastname, 
        requested_reschedule.name AS purpose 
    FROM 
        requested_reschedule
    JOIN 
        users 
    ON 
        requested_reschedule.user_id = users.id
    ORDER BY requested_reschedule.id DESC"; // Assuming 'id' is the unique column in 'requested_reschedule'

// Execute the query
$result = $conn->query($query);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
} else {
    // Debugging: Output the number of rows returned
    $numRows = $result->num_rows;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requested Reschedule</title>
  <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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
    .main-content {
      margin-left: 270px;
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table thead th {
      background-color: #007bff;
      color: white;
      text-align: center;
    }
    table tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    table tbody tr:hover {
      background-color: #ddd;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Solehub Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="#">Dashboard</a>
    <a href="./admin_dashboard.php">Booking Schedule Records</a>
    <a href="./manage_users.php">User Management</a>
    <a href="./requested_reschedule.php" class="active">Requested Reschedule</a>
  </div>

  <!-- Main Content Area -->
  <div class="main-content">
    <h2 class="text-center mb-4">Requested Reschedule</h2>
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Purpose of Reschedule</th>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($result) && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['firstname']); ?></td>
              <td><?= htmlspecialchars($row['lastname']); ?></td>
              <td><?= htmlspecialchars($row['purpose']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">
              No reschedule requests found.
              <?php if (isset($numRows) && $numRows == 0): ?>
                <p class="text-danger">Query returned 0 rows. Please check the database and query.</p>
              <?php endif; ?>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

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
          <th>Purpose of Documents</th>
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
            <td colspan="3" class="text-center">No reschedule requests found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

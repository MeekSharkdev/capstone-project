<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solehub Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../asset/css/home.css">
  <link rel="stylesheet" href="styles.css?v=1.0.1">
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      margin: 0;
      padding: 0;
    }

    .navbar {
      margin-bottom: 20px;
    }

    /* Sidebar styles */
    .sidebar {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      padding-top: 20px;
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

    /* Main content */
    .main-content {
      margin-left: 270px;
      padding: 20px;
      overflow-y: auto;
    }

    .table-responsive {
      margin-top: 30px;
    }

    .table th,
    .table td {
      text-align: center;
    }

    .container-img {
      text-align: center;
    }

    /* Ensure the sidebar is fixed while content scrolls */
    .main-content {
      position: relative;
      padding-left: 30px;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Admin</a>
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
  </nav>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="#">Dashboard</a>
    <a href="./admin_dashboard.php">Booking Schedule Records</a>
    <a href="./manage_users.php">User Management</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <h2>Booking Records</h2>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Appointment Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Mariz Israel</td>
              <td>marizrael@gmail.com</td>
              <td>2024-11-25</td>
              <td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="1" data-name="Mariz Israel" data-email="marizrael@gmail.com" data-date="2024-11-25">View</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Jane Smith</td>
              <td>janesmith@example.com</td>
              <td>2024-11-26</td>
              <td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="2" data-name="Jane Smith" data-email="janesmith@example.com" data-date="2024-11-26">View</button></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Mark Lee</td>
              <td>marklee@example.com</td>
              <td>2024-11-27</td>
              <td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="3" data-name="Mark Lee" data-email="marklee@example.com" data-date="2024-11-27">View</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Structure -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Request Booking Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Blank template area -->
          <div class="mb-3">
            <label for="userName" class="form-label">Name</label>
            <input type="text" class="form-control" id="userName" disabled>
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="userEmail" disabled>
          </div>
          <div class="mb-3">
            <label for="appointmentDate" class="form-label">Appointment Date</label>
            <input type="text" class="form-control" id="appointmentDate" disabled>
          </div>

          <!-- Button choices -->
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-success">Confirm Request</button>
            <button type="button" class="btn btn-warning">Reschedule</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS to handle modal data -->
  <script>
    var viewModal = document.getElementById('viewModal');
    viewModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var userName = button.getAttribute('data-name');
      var userEmail = button.getAttribute('data-email');
      var appointmentDate = button.getAttribute('data-date');

      var modalName = viewModal.querySelector('#userName');
      var modalEmail = viewModal.querySelector('#userEmail');
      var modalDate = viewModal.querySelector('#appointmentDate');

      modalName.value = userName;
      modalEmail.value = userEmail;
      modalDate.value = appointmentDate;
    });
  </script>
</body>

</html>

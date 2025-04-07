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
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Unbounded:wght@200..900&display=swap');
    body {
      font-family: 'Outfit', sans-serif;
    }
    .navbar {
      margin-bottom: 20px;
    }
    .sidebar {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      padding-top: 20px;
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
    .table-responsive {
      margin-top: 30px;
    }
    .table th, .table td {
      text-align: center;
    }
    .container-img {
      text-align: center;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">SoleHub Admin</a>
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
    <a href="#">Booking Schedule Records</a>
    <a href="../pages/manage_users.php">Manage Users</a>
    <a href="#">Settings</a>
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
              <td><button class="btn btn-info">View</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Jane Smith</td>
              <td>janesmith@example.com</td>
              <td>2024-11-26</td>
              <td><button class="btn btn-info">View</button></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Mark Lee</td>
              <td>marklee@example.com</td>
              <td>2024-11-27</td>
              <td><button class="btn btn-info">View</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

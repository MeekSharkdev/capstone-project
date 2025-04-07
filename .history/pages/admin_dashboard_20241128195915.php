<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../asset/css/home.css">
  <link rel="stylesheet" href="styles.css?v=1.0.1">
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      margin: 0;
      padding: 0;
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

    .main-content {
      position: relative;
      padding-left: 30px;
    }

    .modal-body .datepicker {
      margin-top: 15px;
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
    <a href="./main_dashboard.php">Dashboard</a>
    <a href="./admin_dashboard.php">Booking Schedule Records</a>
    <a href="./manage_users.php">User Management</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <h2>Booking Records</h2>
      <div class="table-responsive">
        <table class="table table-striped" id="bookingTable">
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
            <tr id="row1">
              <td>1</td>
              <td>Mariz Israel</td>
              <td>marizrael@gmail.com</td>
              <td class="appointmentDate">2024-11-25</td>
              <td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="1" data-name="Mariz Israel" data-email="marizrael@gmail.com" data-date="2024-11-25">View</button></td>
            </tr>
            <tr id="row2">
              <td>2</td>
              <td>Jane Smith</td>
              <td>janesmith@example.com</td>
              <td class="appointmentDate">2024-11-26</td>
              <td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="2" data-name="Jane Smith" data-email="janesmith@example.com" data-date="2024-11-26">View</button></td>
            </tr>
            <tr id="row3">
              <td>3</td>
              <td>Mark Lee</td>
              <td>marklee@example.com</td>
              <td class="appointmentDate">2024-11-27</td>
              <td><button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" data-id="3" data-name="Mark Lee" data-email="marklee@example.com" data-date="2024-11-27">View</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Structure for Confirm Request -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Request Booking Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-success" id="confirmRequestBtn">Confirm Request</button>
            <button type="button" class="btn btn-warning" id="rescheduleBtn">Reschedule</button>
          </div>

          <div id="datepickerDiv" class="datepicker" style="display: none;">
            <label for="newDate" class="form-label">Select New Date</label>
            <input type="text" id="newDate" class="form-control">
          </div>

          <!-- Confirm Reschedule Button -->
          <div id="confirmRescheduleDiv" style="display: none;">
            <button type="button" class="btn btn-primary" id="confirmRescheduleBtn">Confirm Reschedule</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Request Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>The request has been successfully confirmed. The resident has been notified via email with the confirmation details.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="scripts.js?v=1.0.1"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let currentRow = null;

      // Set up event listeners for modal buttons
      const rescheduleBtn = document.getElementById("rescheduleBtn");
      const confirmRequestBtn = document.getElementById("confirmRequestBtn");
      const confirmRescheduleBtn = document.getElementById("confirmRescheduleBtn");
      const datepickerDiv = document.getElementById("datepickerDiv");
      const confirmRescheduleDiv = document.getElementById("confirmRescheduleDiv");
      const newDateInput = document.getElementById("newDate");
      const appointmentDateInput = document.getElementById("appointmentDate");
      const userNameInput = document.getElementById("userName");
      const userEmailInput = document.getElementById("userEmail");
      const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

      // Handle the "View" button click
      document.querySelectorAll("[data-bs-toggle='modal']").forEach(button => {
        button.addEventListener('click', function () {
          // Fill modal with data
          const name = this.getAttribute('data-name');
          const email = this.getAttribute('data-email');
          const date = this.getAttribute('data-date');

          userNameInput.value = name;
          userEmailInput.value = email;
          appointmentDateInput.value = date;

          currentRow = this.closest('tr');
        });
      });

      // Reschedule button click
      rescheduleBtn.addEventListener("click", function () {
        datepickerDiv.style.display = "block";
        confirmRescheduleDiv.style.display = "block";
        flatpickr(newDateInput, {
          minDate: "today"
        });
      });

      // Confirm reschedule button click
      confirmRescheduleBtn.addEventListener("click", function () {
        const newDate = newDateInput.value;

        if (newDate) {
          currentRow.querySelector(".appointmentDate").textContent = newDate;
          confirmationModal.show();
        }
      });

      // Confirm request button click
      confirmRequestBtn.addEventListener("click", function () {
        confirmationModal.show();
      });
    });
  </script>

</body>

</html>

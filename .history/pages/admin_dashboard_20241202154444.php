<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch booking data from the database with a join between bookings and users
$query = "SELECT bookings.id, bookings.booking_date, users.firstname, users.email 
          FROM bookings 
          INNER JOIN users ON bookings.id = users.id"; // Fixed join condition

// Execute the query
$result = mysqli_query($conn, $query);

// Check if there was an error with the query
if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}
?>

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
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
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

        /* Main Content */
        .main-content {
            margin-left: 270px; /* Adjust for sidebar width */
            padding: 20px;
        }

        /* Table Styles */
        .table-responsive {
            margin-top: 30px;
        }

        .table th, .table td {
            text-align: center;
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
                            <th>Booking Purpose</th>
                            <th>Booking Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through the results and display them in the table
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr id='row" . $row['id'] . "'>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['firstname'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['Booking Purpose'] . "</td>";
                                echo "<td>" . $row['booking_date'] . "</td>";
                                echo "<td><button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#viewModal' data-id='" . $row['id'] . "' data-name='" . $row['firstname'] . "' data-email='" . $row['email'] . "' data-date='" . $row['booking_date'] . "'>View</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No bookings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

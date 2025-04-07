<?php
// Include the database connection
include('../config/mysqli_connect.php');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch booking data from the database
$query = "SELECT bookings.bookings_id, 
                 bookings.firstname, 
                 bookings.lastname, 
                 bookings.phonenumber, 
                 bookings.email, 
                 bookings.purpose, 
                 bookings.booking_date
          FROM bookings";

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

        /* Table Styling */
        .table-responsive {
            margin-top: 30px;
        }

        table thead th {
            border-bottom: 3px solid #555; /* Line under the header */
            font-weight: bold;
            text-align: center;
        }

        table tbody tr:hover {
            background-color: #f2f2f2; /* Hover effect for rows */
        }

        /* Center table text */
        .table th,
        .table td {
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin</a>
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
                <table class="table table-striped caption-top table-hover" id="bookingTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Phone number</th>
                            <th>Email</th>
                            <th>Booking Purpose</th>
                            <th>Booking Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr bookings_id='row" . $row['bookings_id'] . "'>";
                                echo "<td>" . htmlspecialchars($row['bookings_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phonenumber']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                                echo "<td>
                                    <button 
                                        class='btn btn-info view-btn' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#viewModal' 
                                        data-id='" . $row['bookings_id'] . "' 
                                        data-firstname='" . htmlspecialchars($row['firstname']) . "' 
                                        data-lastname='" . htmlspecialchars($row['lastname']) . "' 
                                        data-phonenumber='" . htmlspecialchars($row['phonenumber']) . "' 
                                        data-email='" . htmlspecialchars($row['email']) . "' 
                                        data-purpose='" . htmlspecialchars($row['purpose']) . "' 
                                        data-date='" . htmlspecialchars($row['booking_date']) . "'
                                    >
                                        View
                                    </button>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No bookings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="modalName"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                    <p><strong>Booking Date:</strong> <span id="modalDate"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
mysqli_close($conn);
?>

<?php
session_start();
include('../config/mysqli_connect2.php');

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle POST request to mark user as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']); // Sanitize user ID
    $updateQuery = "UPDATE users SET status = 'completed' WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $updateQuery);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
}

// Fetch users from the database
$query = "SELECT id, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex email, username, created_at, status FROM users";
$result = mysqli_query($dbc, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($dbc));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="icon" type="image/x-icon" href="../asset/img/SOLEHUBS.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/home.css">

    <style>
        /* Sidebar Styling */
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

        .sidebar .nav-link {
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            display: block;
            width: 100%;
        }

        .sidebar .nav-link:hover {
            background-color: #575d63;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        /* Search Bar */
        #searchBar {
            margin-bottom: 15px;
        }

        /* Table hover effect */
        table tbody tr:hover {
            background-color: #343a40;
        }

        table thead th {
            border-bottom: 3px solid #555;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mt-3">Admin Panel</h4>
        <a href="./main_dashboard.php" class="nav-link">Dashboard</a>
        <a href="./booking-records.php" class="nav-link">Booking Schedule Records</a>
        <a href="./manage_users.php" class="nav-link active">User Management</a>
        <a href="./verifying-admin.php" class="nav-link active">Admin Accounts Verifying</a>
        <a href="../logout-admin.php" class="nav-link active">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 style="font-weight: 400;">Manage Users</h2>

        <!-- Search Bar -->
        <input type="text" id="searchBar" class="form-control" placeholder="Search users...">

        <!-- User Table -->
        <table class="table table-striped caption-top table-hover" id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Birthdate</th>
                    <th>Email Address</th>
                    <th>Username</th>
                    <th>Created at</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['firstname']); ?></td>
                            <td><?= htmlspecialchars($row['lastname']); ?></td>
                            <td><?= htmlspecialchars($row['phonenumber']); ?></td>
                            <td><?= htmlspecialchars($row['birthdate']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                            <td><?= htmlspecialchars($row['status']); ?></td>
                            <td>
                                <form method="POST" action="manage_users.php">
                                    <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Mark as Completed</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dynamic Search Logic
        document.getElementById('searchBar').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll("#userTable tbody tr");

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>

<?php
mysqli_close($dbc);
?>

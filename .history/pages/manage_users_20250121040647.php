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
$query = "SELECT id, firstname, middlename, lastname, email, phonenumber, birthdate, sex, civilstatus, citizenship, status FROM users ORDER BY id DESC";
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-200">

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
</button>

<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-gray-800 text-white">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <h4 class="text-center text-lg font-bold mb-4 text-gray-100">Admin Panel</h4>
        <ul class="space-y-2">
            <li>
                <a href="./main_dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition duration-300 
                <?php echo basename($_SERVER['PHP_SELF']) == 'main_dashboard.php' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-3 text-gray-300"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="./booking-records.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition duration-300
                <?php echo basename($_SERVER['PHP_SELF']) == 'booking-records.php' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-calendar-alt mr-3 text-gray-300"></i> Appointment Management
                </a>
            </li>
            <li>
                <a href="./rescheduling-request.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition duration-300
                <?php echo basename($_SERVER['PHP_SELF']) == 'rescheduling-request.php' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-redo-alt mr-3 text-gray-300"></i> Request Rescheduling
                </a>
            </li>
            <li>
                <a href="./manage_users.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition duration-300
                <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-users mr-3 text-gray-300"></i> User Management
                </a>
            </li>
            <li>
                <a href="./manage_admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition duration-300
                <?php echo basename($_SERVER['PHP_SELF']) == 'manage_admin.php' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-user-shield mr-3 text-gray-300"></i> Manage Admins
                </a>
            </li>
            <li>
                <a href="../logout-admin.php" class="flex items-center px-4 py-2 hover:bg-red-700 rounded transition duration-300">
                    <i class="fas fa-sign-out-alt mr-3 text-gray-300"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</aside>


    <!-- Main Content -->
    <div class="sm:ml-64 p-6">
        <h2 class="text-center text-2xl font-bold mb-6">Users</h2>

        <!-- User Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-s text-black uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">First Name</th>
                        <th class="px-6 py-3">Middle Name</th>
                        <th class="px-6 py-3">Last Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Phone Number</th>
                        <th class="px-6 py-3">Birthdate</th>
                        <th class="px-6 py-3">Gender</th>
                        <th class="px-6 py-3">Civil Status</th>
                        <th class="px-6 py-3">Citizenship</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4"><?= htmlspecialchars($row['id']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['firstname']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['middlename']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['lastname']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['phonenumber']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['birthdate']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['sex']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['civilstatus']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['citizenship']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['status']); ?></td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="manage_users.php">
                                        <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                                        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-500">Mark as Completed</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center px-6 py-4">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('searchBar').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? "" : "none";
            });
        });
    </script>
</body>
</html>

<?php
mysqli_close($dbc);
?>

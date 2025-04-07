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
$query = "SELECT id, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex, civilstatus, citizenship, email, username, created_at, status FROM users";
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
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar bg-gray-800 text-white w-60 h-full fixed top-0 left-0 p-4">
        <h4 class="text-center text-xl font-semibold">Admin Panel</h4>
        <nav class="mt-8">
            <a href="./main_dashboard.php" class="block py-2 px-4 rounded hover:bg-gray-700">Dashboard</a>
            <a href="./booking-records.php" class="block py-2 px-4 rounded hover:bg-gray-700">Booking Schedule Records</a>
            <a href="./manage_users.php" class="block py-2 px-4 rounded bg-gray-700">User</a>
            <a href="./verifying-admin.php" class="block py-2 px-4 rounded hover:bg-gray-700">Admin Accounts Verifying</a>
            <a href="../logout-admin.php" class="block py-2 px-4 rounded hover:bg-gray-700">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <h2 class="text-2xl font-semibold mb-4">Users</h2>

        <!-- Search Bar -->
        <div class="mb-6">
            <input type="text" id="searchBar" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search users...">
        </div>

        <!-- User Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 bg-white rounded-lg shadow-sm dark:bg-gray-800 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="p-4">
                            <div class="flex items-center">
                                <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">First Name</th>
                        <th class="px-6 py-3">Middle Name</th>
                        <th class="px-6 py-3">Last Name</th>
                        <th class="px-6 py-3">Phone Number</th>
                        <th class="px-6 py-3">Birthdate</th>
                        <th class="px-6 py-3">Birthplace</th>
                        <th class="px-6 py-3">Gender</th>
                        <th class="px-6 py-3">Civil Status</th>
                        <th class="px-6 py-3">Citizenship</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Created At</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['id']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['firstname']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['middlename']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['lastname']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['phonenumber']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['birthdate']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['birthplace']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['sex']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['civilstatus']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['citizenship']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['username']); ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['created_at']); ?></td>
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
                            <td colspan="16" class="text-center px-6 py-4">No users found.</td>
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

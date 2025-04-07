<?php
session_start();
include('../config/mysqli_connect2.php');

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Fetch users from the database
$query = "SELECT id, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex, civilstatus, citizenship, email, username, created_at, status FROM users";
$result = mysqli_query($dbc, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($dbc));
}

// Mark as Completed functionality
if (isset($_POST['mark_completed'])) {
    $user_id = $_POST['user_id'];
    $update_query = "UPDATE users SET status='Completed' WHERE id = $user_id";
    if (mysqli_query($dbc, $update_query)) {
        echo "User status marked as Completed.";
        header("Location: manage_users.php");
        exit;
    } else {
        echo "Error updating status: " . mysqli_error($dbc);
    }
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
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <i class="fas fa-bars w-6 h-6"></i>
    </button>

    <aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-gray-800 text-white">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <h4 class="text-center text-lg font-bold mb-4">Admin Panel</h4>
            <ul class="space-y-2">
                <li><a href="./main_dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a></li>
                <li><a href="./booking-records.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-calendar-check mr-2"></i> Appointment Schedule Records</a></li>
                <li><a href="./manage_users.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-users mr-2"></i> User</a></li>
                <li><a href="./verifying-admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-user-check mr-2"></i> Admin Accounts Verifying</a></li>
                <li><a href="../logout-admin.php" class="flex items-center px-4 py-2 hover:bg-red-700 rounded"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="sm:ml-64 p-6">
        <h2 class="text-center text-2xl font-bold mb-6">Users</h2>

        <!-- Search Bar -->
        <div class="mb-6">
            <input type="text" id="searchBar" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search users...">
        </div>

        <!-- User Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            <div class="flex items-center">
                                <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-all" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">First Name</th>
                        <th scope="col" class="px-6 py-3">Middle Name</th>
                        <th scope="col" class="px-6 py-3">Last Name</th>
                        <th scope="col" class="px-6 py-3">Phone Number</th>
                        <th scope="col" class="px-6 py-3">Birthdate</th>
                        <th scope="col" class="px-6 py-3">Birthplace</th>
                        <th scope="col" class="px-6 py-3">Gender</th>
                        <th scope="col" class="px-6 py-3">Civil Status</th>
                        <th scope="col" class="px-6 py-3">Citizenship</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">Created At</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-<?= $row['id'] ?>" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-<?= $row['id'] ?>" class="sr-only">checkbox</label>
                                    </div>
                                </td>
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
                                    <form method="post" class="inline-block">
                                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="mark_completed" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Mark as Completed</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="text-center px-6 py-4">No users found.</td>
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

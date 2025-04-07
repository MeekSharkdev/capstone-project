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
$query = "SELECT id, firstname, middlename, lastname, email, phonenumber, birthdate, sex, civilstatus, citizenship FROM users ORDER BY id DESC";
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

    <!-- Include Sidebar from Main Dashboard -->
    <?php include('sidebar.php'); ?>
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
    </button>


    <!-- Main Content -->
    <div class="sm:ml-64 p-6">
        <h2 class="text-center text-2xl font-bold mb-6">Admins</h2>

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
                            <td colspan="11" class="text-center px-6 py-4">No users found.</td>
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

<?php
session_start();
include '../config/db_connection.php';

// Fetch contact form submissions
$query = "SELECT * FROM contact_us ORDER BY submission_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <title>Manage Inquiries | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <?php include('sidebar.php'); ?>
    <div class="sm:ml-64 p-6">
        <h2 class="text-center text-2xl font-bold mb-6">Submitted Inquiries</h2>

        <div class="relative overflow-x-auto shadow-2xl sm:rounded-lg bg-white">
            <table class="w-full text-sm text-left text-gray-500" id="contactTable">
                <thead class="text-s text-black uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Subject</th>
                        <th class="px-6 py-3">Message</th>
                        <th class="px-7 py-4">Date</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="px-6 py-4"><?php echo ucfirst(htmlspecialchars($row['subject'])); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['message']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['submission_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4">No contact submissions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
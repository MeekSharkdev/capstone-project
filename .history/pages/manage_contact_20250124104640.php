<?php
session_start();
include '../config/db_connection.php'; // Ensure you have a database connection file



// Fetch contact form submissions
$query = "SELECT * FROM contact_us ORDER BY submission_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contact Submissions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold text-center mb-6">Contact Form Submissions</h2>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Phone</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Subject</th>
                        <th class="border p-2">Message</th>
                        <th class="border p-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="border">
                            <td class="border p-2"><?php echo $row['id']; ?></td>
                            <td class="border p-2"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td class="border p-2"><?php echo $row['phone_number']; ?></td>
                            <td class="border p-2"><?php echo $row['email']; ?></td>
                            <td class="border p-2"><?php echo ucfirst($row['subject']); ?></td>
                            <td class="border p-2"><?php echo $row['message']; ?></td>
                            <td class="border p-2"><?php echo $row['submission_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
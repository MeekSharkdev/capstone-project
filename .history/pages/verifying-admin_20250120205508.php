<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all admins
$result = $conn->query("SELECT * FROM admintbl");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Admins</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200">
  <aside class="fixed top-0 left-0 w-64 h-screen bg-gray-800 text-white p-4">
    <h4 class="text-center text-lg font-bold mb-4">Admin Panel</h4>
    <ul class="space-y-2">
      <li><a href="./main_dashboard.php" class="block px-4 py-2 hover:bg-gray-700 rounded">Dashboard</a></li>
      <li><a href="./manage_admins.php" class="block px-4 py-2 hover:bg-gray-700 rounded">Manage Admins</a></li>
      <li><a href="./create_admin.php" class="block px-4 py-2 hover:bg-gray-700 rounded">Create Admin</a></li>
      <li><a href="../logout-admin.php" class="block px-4 py-2 hover:bg-red-700 rounded">Logout</a></li>
    </ul>
  </aside>

  <div class="ml-64 p-6">
    <h2 class="text-2xl font-bold mb-6">Manage Admins</h2>
    <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-lg">
      <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-700 text-white">
            <th class="border border-gray-300 px-4 py-2">Name</th>
            <th class="border border-gray-300 px-4 py-2">Phone</th>
            <th class="border border-gray-300 px-4 py-2">Email</th>
            <th class="border border-gray-300 px-4 py-2">Role</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
              <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['phonenumber']); ?></td>
              <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
              <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['role']); ?></td>
              <td class="border border-gray-300 px-4 py-2 text-center">
                <button class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="ml-64 p-6 mt-10">
    <h2 class="text-2xl font-bold mb-6">Create Admin</h2>
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <form action="create_admin.php" method="POST" class="space-y-4">
        <div>
          <label class="block font-semibold">First Name</label>
          <input type="text" name="firstname" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Last Name</label>
          <input type="text" name="lastname" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Phone</label>
          <input type="text" name="phonenumber" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Email</label>
          <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Password</label>
          <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Role</label>
          <select name="role" required class="w-full px-3 py-2 border rounded">
            <option value="Admin">Admin</option>
            <option value="Super Admin">Super Admin</option>
          </select>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded">Create Admin</button>
      </form>
    </div>
  </div>
</body>
</html>

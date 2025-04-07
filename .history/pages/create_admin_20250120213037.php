<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Admin</title>
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

  <div class="ml-64 p-6 mt-10">
    <h2 class="text-2xl font-bold mb-6">Create Admin</h2>
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <form action="create_admin.php" method="POST" class="space-y-4">
        <div>
          <label class="block font-semibold">First Name</label>
          <input type="text" name="firstname" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Middle Name</label>
          <input type="text" name="middlename" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Last Name</label>
          <input type="text" name="lastname" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Phone Number</label>
          <input type="text" name="phonenumber" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Email</label>
          <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Birthdate</label>
          <input type="date" name="birthdate" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Age</label>
          <input type="number" name="age" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Sex</label>
          <select name="sex" required class="w-full px-3 py-2 border rounded">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold">Role</label>
          <select name="role" required class="w-full px-3 py-2 border rounded">
            <option value="Admin">Admin</option>
            <option value="Super Admin">Super Admin</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold">Username</label>
          <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Password</label>
          <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded">Create Admin</button>
      </form>
    </div>
  </div>
</body>
</html>

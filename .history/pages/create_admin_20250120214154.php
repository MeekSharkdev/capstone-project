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
<body class="bg-gray-200 flex items-center justify-center min-h-screen">
  <aside class="fixed top-0 left-0 w-64 h-screen bg-gray-800 text-white p-4">
    <h4 class="text-center text-lg font-bold mb-4">Admin Panel</h4>
    <ul class="space-y-2">
      <li><a href="./main_dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a></li>
      <li><a href="./manage_admins.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-user-shield mr-2"></i> Manage Admins</a></li>
      <li><a href="./create_admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-user-plus mr-2"></i> Create Admin</a></li>
      <li><a href="../logout-admin.php" class="flex items-center px-4 py-2 hover:bg-red-700 rounded"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
    </ul>
  </aside>

  <div class="ml-64 p-6 w-full max-w-4xl">
    <h2 class="text-2xl font-bold text-center mb-6">Create Admin</h2>
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <form action="create_admin.php" method="POST" class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold">Full Name</label>
          <input type="text" name="fullname" required class="w-full px-3 py-2 border rounded">
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
        <div class="col-span-2">
          <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Admin</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize alert message
$alertMessage = '';
$alertType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $alertMessage = 'Passwords do not match!';
        $alertType = 'error';
    } else {
        // Check if username or email already exists
        $checkUserSql = "SELECT * FROM admintbl WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($checkUserSql);

        if ($result->num_rows > 0) {
            $alertMessage = 'Username or email already exists!';
            $alertType = 'error';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO admintbl (firstname, middlename, lastname, phonenumber, email, birthdate, age, sex, role, username, password)
                    VALUES ('$firstname', '$middlename', '$lastname', '$phonenumber', '$email', '$birthdate', '$age', '$sex', '$role', '$username', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                $alertMessage = 'New admin created successfully!';
                $alertType = 'success';
            } else {
                $alertMessage = 'Error: ' . $conn->error;
                $alertType = 'error';
            }
        }
    }

    // Output the JavaScript alert
    echo "<script>
            alert('$alertMessage');
          </script>";
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-200 flex h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-gray-800 text-white p-4 h-full fixed">
    <div class="text-center mb-6">
      <img src="../asset/img/SOLEHUBS.png" alt="Logo" class="w-20 mx-auto">
      <h4 class="text-lg font-bold mt-2">Admin Panel</h4>
    </div>
    <ul class="space-y-2">
      <li><a href="./main_dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a></li>
      <li><a href="./manage_admins.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><i class="fas fa-user-shield mr-2"></i> Manage Admins</a></li>
      <li><a href="./create_admin.php" class="flex items-center px-4 py-2 bg-gray-700 rounded"><i class="fas fa-user-plus mr-2"></i> Create Admin</a></li>
      <li><a href="../logout-admin.php" class="flex items-center px-4 py-2 hover:bg-red-700 rounded"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
    </ul>
  </aside>

  <!-- Main Content -->
  <div class="flex-1 flex justify-center items-center ml-64 p-6">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
      <h2 class="text-2xl font-bold text-center mb-6">Create Admin</h2>
      <form action="create_admin.php" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
            <option value="Other">Other</option>
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
        <div>
          <label class="block font-semibold">Confirm Password</label>
          <input type="password" name="confirm_password" required class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 col-span-2">Create Admin</button>
      </form>
    </div>
  </div>
</body>
</html>

<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Initialize alert message
$alertMessage = '';
$alertClass = '';
$alertIcon = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $firstname = $_POST['firstname'];
  $middlename = $_POST['middlename'];
  $lastname = $_POST['lastname'];

  // Trim phone number and ensure it contains only digits
  $phonenumber = preg_replace('/\D/', '', $_POST['phonenumber']);

  $email = $_POST['email'];
  $birthdate = $_POST['birthdate'];
  $age = $_POST['age'];
  $sex = $_POST['sex'];
  $role = $_POST['role'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate phone number length
  if (strlen($phonenumber) !== 11) {
    $alertMessage = 'Phone number must be exactly 11 digits!';
    $alertClass = 'alert-danger';
    $alertIcon = 'exclamation-triangle-fill';
  }
  // Check if passwords match
  else if ($password !== $confirm_password) {
    $alertMessage = 'Passwords do not match!';
    $alertClass = 'alert-danger';
    $alertIcon = 'exclamation-triangle-fill';
  } else {
    // Check if username or email already exists
    $checkUserSql = "SELECT * FROM admintbl WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($checkUserSql);

    if ($result->num_rows > 0) {
      $alertMessage = 'Username or email already exists!';
      $alertClass = 'alert-danger';
      $alertIcon = 'exclamation-triangle-fill';
    } else {
      // Hash the password for security
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Insert data into the database
      $sql = "INSERT INTO admintbl (firstname, middlename, lastname, phonenumber, email, birthdate, age, sex, role, username, password)
                    VALUES ('$firstname', '$middlename', '$lastname', '$phonenumber', '$email', '$birthdate', '$age', '$sex', '$role', '$username', '$hashed_password')";

      if ($conn->query($sql) === TRUE) {
        $alertMessage = 'New admin created successfully!';
        $alertClass = 'alert-success';
        $alertIcon = 'check-circle-fill';
      } else {
        $alertMessage = 'Error: ' . $conn->error;
        $alertClass = 'alert-danger';
        $alertIcon = 'exclamation-triangle-fill';
      }
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Admin</title>
  <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-200 flex h-screen">

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Main Content -->
  <div class="flex-1 flex justify-center items-center ml-64 p-6">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
      <h2 class="text-2xl font-bold text-center mb-6">Create Admin</h2>

      <!-- Tailwind Alert Message with Icon -->
      <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
            <use xlink:href="#<?php echo $alertIcon; ?>" />
          </svg>
          <div>
            <?php echo $alertMessage; ?>
          </div>
        </div>
      <?php endif; ?>

      <form action="create_admin.php" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <div>
          <label class="block font-semibold">First Name</label>
          <input type="text" name="firstname" value="<?php echo isset($firstname) ? $firstname : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Middle Name</label>
          <input type="text" name="middlename" value="<?php echo isset($middlename) ? $middlename : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Last Name</label>
          <input type="text" name="lastname" value="<?php echo isset($lastname) ? $lastname : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Phone Number</label>
          <input type="text" name="phonenumber" value="<?php echo isset($phonenumber) ? $phonenumber : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Email</label>
          <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Birthdate</label>
          <input type="date" name="birthdate" value="<?php echo isset($birthdate) ? $birthdate : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Age</label>
          <input type="number" name="age" value="<?php echo isset($age) ? $age : ''; ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block font-semibold">Sex</label>
          <select name="sex" required class="w-full px-3 py-2 border rounded">
            <option value="" disabled selected>-----</option>
            <option value="Male" <?php echo (isset($sex) && $sex == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($sex) && $sex == 'Female') ? 'selected' : ''; ?>>Female</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold">Role</label>
          <select name="role" required class="w-full px-3 py-2 border rounded">
            <option value="" disabled selected>-----</option>
            <option value="Developer" <?php echo (isset($role) && $role == 'Developer') ? 'selected' : ''; ?>>Developer</option>
            <option value="Admin" <?php echo (isset($role) && $role == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="Super Admin" <?php echo (isset($role) && $role == 'Super Admin') ? 'selected' : ''; ?>>Super Admin</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold">Username</label>
          <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required class="w-full px-3 py-2 border rounded">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
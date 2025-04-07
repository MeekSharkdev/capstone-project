<?php
session_start();

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['username'])) {
    header("Location: /Capstone/Scheduling_Appointment/pages/main_dashboard.php");
    exit();
}

// Database connection credentials
$servername = 'localhost';
$db_username = 'root';
$db_password = '122401';
$dbname = 'dbusers';

// Create a database connection
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

// Handle connection error
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Query the admintbl for user data
    $stmt = $conn->prepare('SELECT * FROM admintbl WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        // Check account status
        if ($user['status'] === 'Verified') {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                header("Location: /Capstone/Scheduling_Appointment/pages/main_dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } elseif ($user['status'] === 'Rejected') {
            $error = "Your account was rejected due to unmet requirements. Review your details and reapply.";
        } else {
            $error = "Your account is pending verification. Please wait for the admin to approve your account.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">

    <div class="bg-white shadow-lg rounded-lg p-8 max-w-sm w-full">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Admin Login</h2>

        <?php if (isset($error)): ?>
            <div class="text-red-600 text-sm mb-4 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login-admin.php" class="space-y-4">
            <!-- Username Field -->
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="username" id="username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                <label for="username" class="absolute text-sm text-gray-500 peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:text-blue-600 peer-focus:text-sm peer-focus:top-1 duration-300 transform">Username</label>
            </div>

            <!-- Password Field with Toggle Eye Icon -->
            <div class="relative z-0 w-full mb-6 group">
                <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                <label for="password" class="absolute text-sm text-gray-500 peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:text-blue-600 peer-focus:text-sm peer-focus:top-1 duration-300 transform">Password</label>
                <i class="fas fa-eye absolute right-3 top-3 text-gray-500 cursor-pointer" id="toggle-password" onclick="togglePassword()"></i>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-2 px-4 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Login</button>
        </form>

        <!-- Register Link -->
        <div class="text-center mt-4 text-sm">
            Don't have an account? <a href="./register-admin.php" class="text-blue-600 hover:underline">Register here</a>
        </div>
    </div>

    <!-- JavaScript to toggle visibility of password field -->
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var icon = document.getElementById('toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

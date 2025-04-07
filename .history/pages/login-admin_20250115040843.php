<?php
session_start();

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Database connection
$servername = 'localhost';
$db_username = 'root';
$db_password = '122401';
$dbname = 'dbusers';

// Create connection
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the user
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];  // Start the session
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Redirecting to dashboard...',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'dashboard.php';
                });
              </script>";
        exit();
    } else {
        // Display error message with SweetAlert
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid credentials',
                    text: 'Please check your username and password.',
                    showConfirmButton: true
                });
              </script>";
    }
}
?>

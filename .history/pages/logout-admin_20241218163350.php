<?php
session_start();

// Admin logout
if (isset($_SESSION['admin_logged_in'])) {
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_username']);
}

// User logout
if (isset($_SESSION['user_logged_in'])) {
    unset($_SESSION['user_logged_in']);
    unset($_SESSION['user_username']);
}

// Destroy session if needed
session_destroy();

header("Location: /Capstone/Scheduling_Appointment/index.php");
exit();
?>

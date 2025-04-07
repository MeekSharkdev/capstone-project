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

// Destroy the entire session to clear everything
session_unset();  // This will clear all session variables
session_destroy(); // This will destroy the session itself

// Redirect to the homepage (or login page as needed)
header("Location: /Capstone/Scheduling_Appointment/index.php");
exit();

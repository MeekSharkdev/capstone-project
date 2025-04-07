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
session_unset();  // Clear session data
session_destroy(); // Destroy session
session_regenerate_id();  // Regenerate session ID


// Redirect to the homepage (or login page as needed)
header("Location: /Capstone/Scheduling_Appointment/index.php");
exit();

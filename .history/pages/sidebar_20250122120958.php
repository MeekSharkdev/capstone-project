<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get total registered users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = mysqli_query($conn, $userQuery);
$totalUsers = mysqli_fetch_assoc($userResult)['total_users'];

// Query to get the number of total bookings
$bookingQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
$bookingResult = mysqli_query($conn, $bookingQuery);
$totalBookings = mysqli_fetch_assoc($bookingResult)['total_bookings'];

// Query to get counts of users marked as completed
$statusQuery = "SELECT COUNT(*) AS completed_users FROM users WHERE status = 'completed'";
$statusResult = mysqli_query($conn, $statusQuery);
$completedUsers = mysqli_fetch_assoc($statusResult)['completed_users'];

$conn->close();
?>

<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform bg-gray-800 text-white">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <!-- Logo at the top with pop-up animation -->
        <div class="flex justify-center mb-4 logo-pop-up">
            <img src="../images/brgy-logo-remove.png" alt="Barangay Logo" class="h-16 w-auto">
        </div>

        <ul class="space-y-2">
            <li class="link-slide-in">
                <a href="./main_dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
                </a>
            </li>
            <li class="link-slide-in">
                <a href="./booking-records.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-calendar-alt mr-4"></i> Appointment Management
                </a>
            </li>
            <li class="link-slide-in">
                <a href="./requested_reschedule.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-redo-alt mr-4"></i> Rescheduling Request
                </a>
            </li>
            <li class="link-slide-in">
                <a href="./manage_users.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-users mr-4"></i> Manage Users
                </a>
            </li>
            <li class="link-slide-in">
                <a href="./manage_admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-user-shield mr-4"></i> Admin Overview
                </a>
            </li>
            <li class="link-slide-in">
                <a href="./create_admin.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-user-shield mr-4"></i> Create Admin
                </a>
            </li>
            <li class="link-slide-in">
                <a href="./create_event.php" class="flex items-center px-4 py-2 hover:bg-gray-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-calendar-plus mr-4"></i> Create Event
                </a>
            </li>
            <li class="link-slide-in">
                <a href="../logout-admin.php" class="flex items-center px-4 py-2 mt-9 hover:bg-red-700 hover:scale-105 rounded transition-all duration-500">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </li>
        </ul>

        <!-- Display admin's first name at the bottom with emphasis -->
        <div class="mt-12 text-center text-sm text-gray-300 py-2 bg-gray-700 rounded-xl">
            <span class="block text-xl font-semibold text-gray-400">
                <?php
                // Check if the session variable 'username' is set before displaying it
                if (isset($_SESSION['username'])) {
                    echo "Hi! Admin, <span class='text-white'>" . htmlspecialchars($_SESSION['username']) . "</span>";
                } else {
                    echo "Error po!";
                }
                ?>
            </span>
        </div>
    </div>
</aside>

<style>
    /* Pop-up animation for the logo */
    .logo-pop-up {
        animation: popUp 1s ease-out;
    }

    @keyframes popUp {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Slide-in animation for sidebar links */
    .link-slide-in {
        animation: slideIn 0.5s ease-out forwards;
        opacity: 0;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Delay each link's animation */
    .link-slide-in:nth-child(1) {
        animation-delay: 0.1s;
    }

    .link-slide-in:nth-child(2) {
        animation-delay: 0.2s;
    }

    .link-slide-in:nth-child(3) {
        animation-delay: 0.3s;
    }

    .link-slide-in:nth-child(4) {
        animation-delay: 0.4s;
    }

    .link-slide-in:nth-child(5) {
        animation-delay: 0.5s;
    }

    .link-slide-in:nth-child(6) {
        animation-delay: 0.6s;
    }

    .link-slide-in:nth-child(7) {
        animation-delay: 0.7s;
    }

    .link-slide-in:nth-child(8) {
        animation-delay: 0.8s;
    }
</style>
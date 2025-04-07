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

        <!-- Display admin's first name at the bottom with typing effect -->
        <div class="mt-12 text-center text-sm text-gray-300 py-2 bg-gray-700 rounded-xl">
            <span class="block text-xl font-semibold text-gray-400">
                <span id="typing-effect" class="text-white"></span>
            </span>
        </div>
</aside>

<script>
    const typingElement = document.getElementById('typing-effect');
    const username = "<?php echo htmlspecialchars($_SESSION['username']); ?>"; // Dynamic username
    const capitalizedUsername = username.charAt(0).toUpperCase() + username.slice(1); // Capitalize only the first letter
    const text = "Hi! Admin, " + capitalizedUsername; // Combine with the rest of the text
    let index = 0;
    let isTyping = true;
    let isPaused = false;

    function typeAndClear() {
        if (isTyping && !isPaused) {
            typingElement.textContent += text.charAt(index);
            index++;
            if (index === text.length) {
                isPaused = true; // Pause after typing is done
                setTimeout(() => {
                    isTyping = false; // Start clearing after 1 second pause
                }, 1500); // Pause for 1 second before clearing
            }
        } else if (!isTyping) {
            typingElement.textContent = typingElement.textContent.slice(0, -1);
            if (typingElement.textContent.length === 0) {
                isTyping = true; // Start typing again once clearing is done
                index = 0; // Reset the typing index to start from the beginning
                isPaused = false; // Allow typing again
            }
        }
    }

    // Run the function continuously to loop the typing and clearing effect
    setInterval(typeAndClear, 210); // Slow typing speed (200ms per character)
</script>


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
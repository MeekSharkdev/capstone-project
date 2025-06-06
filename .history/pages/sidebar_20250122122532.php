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
            <!-- Sidebar links here -->
        </ul>

        <!-- Display admin's first name at the bottom with typing effect -->
        <div class="mt-12 text-center text-sm text-gray-300 py-2 bg-gray-700 rounded-xl">
            <span class="block text-xl font-semibold text-gray-400">
                <span id="typing-effect" class="text-white"></span>
            </span>
        </div>
    </div>
</aside>

<script>
    const typingElement = document.getElementById('typing-effect');
    const text = "Hi! Admin, <?php echo htmlspecialchars($_SESSION['username']); ?>"; // Dynamic username
    let index = 0;
    let isTyping = true;

    function typeAndClear() {
        if (isTyping) {
            typingElement.textContent += text.charAt(index);
            index++;
            if (index === text.length) {
                isTyping = false; // Start clearing once typing is done
            }
        } else {
            typingElement.textContent = typingElement.textContent.slice(0, -1);
            if (typingElement.textContent.length === 0) {
                isTyping = true; // Start typing again once clearing is done
            }
        }
    }

    setInterval(typeAndClear, 200); // Slow typing speed (200ms per character)
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

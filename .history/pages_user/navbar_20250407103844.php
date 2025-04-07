<?php
session_start();
$username = $_SESSION['username'] ?? 'Guest'; // Get the username from the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Dropdown</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        /* Glassmorphism style for the navbar */
        nav {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Mobile Menu Transition */
        #mobile-menu {
            transition: transform 0.3s ease-in-out;
        }

        /* Optional: Add media query for more responsiveness */
        @media (max-width: 768px) {
            #profile-dropdown {
                display: none;
                /* Hide profile dropdown in mobile */
            }

            /* Mobile Menu (Initially Hidden) */
            #mobile-menu {
                display: none;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 1rem;
                z-index: 10;
                /* Ensure it is above other elements */
                margin-top: 4rem;
                /* Adjust this to prevent overlap with navbar */
                max-width: 100%;
                /* Ensure mobile menu doesn't exceed screen width */
            }

            /* Show mobile menu when toggle is clicked */
            #mobile-menu.open {
                display: block;
            }

            /* Mobile Menu Links */
            #mobile-menu a {
                padding: 0.5rem 0;
                text-align: center;
                border-bottom: 1px solid rgba(255, 255, 255, 0.3);
                transition: transform 0.3s ease, text-shadow 0.3s ease;
            }

            #mobile-menu a:last-child {
                border-bottom: none;
            }

            #menu-toggle {
                position: relative;
                z-index: 20;
                /* Make sure it's clickable */
            }
        }
    </style>

</head>

<body>

    <!-- Navbar -->
    <nav class="w-full fixed top-0 left-0 z-50 shadow-lg">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between p-4">
            <!-- Brgy Logo & Title -->
            <a href="./dashboard.php" class="flex items-center space-x-3">
                <img src="../images/brgy-fav.ico" class="h-8" alt="Logo" />
                <span class="text-xl font-semibold text-gray-800">BRGY.SILANGAN</span>
            </a>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex space-x-8">
                <a href="./dashboard.php" class="text-gray-900 hover:text-blue-500 nav-link text-lg">Home</a>
                <a href="#services" class="text-gray-900 hover:text-blue-500 nav-link text-lg">Services</a>
                <a href="#upcoming-events" class="text-gray-900 hover:text-blue-500 nav-link text-lg">Events</a>
                <a href="./contact.php" class="text-gray-900 hover:text-blue-500 nav-link text-lg">Contact</a>
                <a href="#about-section" class="text-gray-900 hover:text-blue-500 nav-link text-lg">About Us</a>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profile-toggle" class="text-gray-800 flex items-center space-x-2 text-lg">
                    <span>Hi, <?php echo htmlspecialchars($username); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <!-- Initially hidden, toggled by JavaScript -->
                <div id="profile-dropdown" class="dropdown-menu absolute right-0 mt-2 w-48 z-10">
                    <a href="./profile.php" class="block text-gray-800 hover:bg-gray-200">Profile</a>
                    <a href="./request-form.php" class="block text-gray-800 hover:bg-gray-200">Reschedule</a>
                    <a href="../logout-admin.php" class="block text-gray-800 hover:bg-gray-200 sign-out">Sign Out</a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="menu-toggle" class="md:hidden text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Initially Hidden) -->
    <div id="mobile-menu">
        <a href="#" class="text-white hover:text-blue-400 block py-2">Home</a>
        <a href="#" class="text-white hover:text-blue-400 block py-2">Services</a>
        <a href="#" class="text-white hover:text-blue-400 block py-2">Events</a>
        <a href="#" class="text-white hover:text-blue-400 block py-2">Contact</a>
        <a href="#" class="text-white hover:text-blue-400 block py-2">About Us</a>
    </div>

    <!-- Profile Dropdown Toggle Script -->
    <script>
        // Wait until the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle the visibility of the dropdown when clicking on the profile button
            document.getElementById("profile-toggle").addEventListener("click", function(event) {
                const dropdown = document.getElementById("profile-dropdown");
                dropdown.classList.toggle("show"); // Toggle visibility
                event.stopPropagation();
            });

            // Close dropdown if clicked outside of it
            window.addEventListener("click", function(event) {
                const dropdown = document.getElementById("profile-dropdown");
                const profileButton = document.getElementById("profile-toggle");

                if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.remove("show"); // Hide the dropdown if clicked outside
                }
            });

            // Toggle mobile menu when the burger button is clicked
            document.getElementById("menu-toggle").addEventListener("click", function() {
                const mobileMenu = document.getElementById("mobile-menu");
                mobileMenu.classList.toggle("open"); // Show or hide the menu
            });
        });
    </script>

</body>

</html>
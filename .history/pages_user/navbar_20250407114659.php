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

        /* Glassmorphism style for the navbar */
        nav {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Glassmorphism style for dropdown */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-height: 0;
            overflow: hidden;
            /* Hide overflow */
            transition: max-height 0.3s ease-out;
            /* Smooth transition */
        }

        .dropdown-menu.show {
            max-height: 300px;
            /* Adjust max-height to suit your content */
        }

        /* Navigation Link Hover Effect */
        .nav-link {
            position: relative;
            transition: transform 0.3s ease, text-shadow 0.3s ease;
        }

        .nav-link:hover {
            transform: scale(1.1);
            /* Zoom effect */
            text-shadow: 0 0 10px rgba(0, 0, 255, 0.8), 0 0 20px rgba(0, 0, 255, 0.6);
            /* Glowing blue effect */
        }

        /* Adjusting mobile menu links */
        #mobile-menu a {
            transition: transform 0.3s ease, text-shadow 0.3s ease;
        }

        #mobile-menu a:hover {
            transform: scale(1.1);
            /* Zoom effect */
            text-shadow: 0 0 10px rgba(0, 0, 255, 0.8), 0 0 20px rgba(0, 0, 255, 0.6);
            /* Glowing blue effect */
        }

        /* Dropdown link hover effect */
        .dropdown-menu a {
            padding: 10px 20px;
            transition: background-color 1s ease, color 0.3s ease;
        }

        .dropdown-menu a:hover {
            background-color: #888888;
            /* Dark gray background */
            color: white;
            /* White text on hover */
        }

        .dropdown-menu .sign-out:hover {
            background-color: #FF6F6F;
            /* Red background for Sign Out */
            color: white;
            /* White text on hover */
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

            #mobile-menu {
                display: block;
                /* Make sure it's visible on mobile */
            }
        }

        #menu-toggle {
            position: relative;
            z-index: 10;
            /* Make sure it's clickable */
        }

        #mobile-menu {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 5;
            /* Make sure it appears above other content when opened */
        }

        /* Initially hide profile dropdown and mobile menu */
        #profile-dropdown {
            display: none;
        }

        #mobile-menu {
            display: none;
        }

        /* Show profile dropdown when toggled */
        #profile-dropdown.show {
            display: block;
        }

        /* Show mobile menu when toggled */
        #mobile-menu.show {
            display: block;
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
                <button id="menu-toggle" class="md:hidden text-gray-800 transform transition-transform duration-200 ease-in-out">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Mobile Menu (Hidden by Default) -->
                <div id="mobile-menu" class="hidden fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 p-8 backdrop-blur-md">
                    <a href="./dashboard.php" class="text-white hover:text-blue-400 block py-2 transition-all duration-300" onclick="closeMenu()">Home</a>
                    <a href="./contact.php" class="text-white hover:text-blue-400 block py-2 transition-all duration-300" onclick="closeMenu()">Contact</a>
                    <a href="./profile.php" class="text-white hover:text-blue-400 block py-2 transition-all duration-300" onclick="closeMenu()">Profile</a>
                    <a href="./request-form.php" class="text-white hover:text-blue-400 block py-2 transition-all duration-300" onclick="closeMenu()">Reschedule</a>
                </div> <!-- Mobile Menu Button -->
                <button id="menu-toggle" class="md:hidden text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu (Hidden by Default) -->
            <div id="mobile-menu" class="hidden bg-gray-800 p-4">
                <a href="./dashboard.php" class="text-white hover:text-blue-400 block py-2">Home</a>
                <a href="./contact.php" class="text-white hover:text-blue-400 block py-2">Contact</a>
                <a href="./profile.php" class="text-white hover:text-blue-400 block py-2">Profile</a>
                <a href="./request-form.php" class="text-white hover:text-blue-400 block py-2">Reschedule</a>
            </div>
        </div>
    </nav>

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
                mobileMenu.classList.toggle("show"); // Show or hide the menu
            });
        });
    </script>


</body>

</html>
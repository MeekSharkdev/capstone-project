<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brgy Silangan Scheduling Appointment</title>
    <link rel="icon" href="./images/brgy-fav.ico" type="image/x-icon">
    <!-- Tailwind CSS link -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Fade-in animation */
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 2;
            }
        }

        /* Smooth pop-up animation for text */
        .pop-up {
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

        /* Slide-up animation for cards */
        .slide-up {
            animation: slideUp 1s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="relative fade-in" style="background: url('./images/bg-brgy-staffs.png') no-repeat center center / cover; width: 100vw; height: 95vh;">


    <!-- Gray background overlay to reduce the emphasis on the background image -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-20 backdrop-blur-md rounded-xl z-0 mx-9"></div>

    <!-- Brgy. Silangan Cubao Quezon City text with smooth pop-up effect -->
    <div class="text-center text-2xl font-bold text-gray-300 mt-8 z-10 pt-12 relative pop-up">
        Barangay Document Appointment Scheduling System
    </div>

    <!-- Logo at the top -->
    <div class="text-center mt-9 z-10 mb-12 relative fade-in">
        <img src="./images/BRGY.__3_-removebg-preview.png" alt="Logo" class="w-28 mx-auto">
    </div>

    <!-- Card container with Tailwind CSS -->
    <div class="flex justify-center mt-12 px-4 z-10 relative">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-24 max-w-xl mx-auto mb-14">
            <!-- User Card -->
            <a href="./pages_user/login.php" class="block slide-up">
                <div class="bg-white bg-opacity-30 backdrop-blur-lg shadow-lg rounded-lg overflow-hidden transform transition duration-500 hover:scale-105 max-w-56 max-h-64 cursor-pointer">
                    <div class="relative">
                        <img src="images/user.png" alt="User Logo" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-40">
                            <i class="fas fa-user text-white text-3xl"></i> <!-- User Icon -->
                        </div>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-md font-medium text-gray-300">User Login</h3>
                    </div>
                </div>
            </a>

            <!-- Admin Card -->
            <a href="./pages/login-admin.php" class="block slide-up">
                <div class="bg-white bg-opacity-30 backdrop-blur-lg shadow-lg rounded-lg overflow-hidden transform transition duration-200 hover:scale-105 max-w-60 cursor-pointer">
                    <div class="relative">
                        <img src="images/adminlogo.png" alt="Admin Logo" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-40">
                            <i class="fas fa-user-shield text-white text-3xl"></i> <!-- Admin Icon -->
                        </div>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-md font-medium text-gray-300">Admin Login</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

</body>

</html>
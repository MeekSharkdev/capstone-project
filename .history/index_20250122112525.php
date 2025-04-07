<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <!-- Tailwind CSS link -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover bg-center bg-no-repeat bg-img relative" style="background-image: url('./images/bg-brgy-staffs.png');">

    <!-- Gray background overlay to reduce the emphasis on the background image -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-70 z-0 mx-9"></div>

    <!-- Brgy. Silangan Cubao Quezon City text -->
    <div class="text-center text-2xl mt-6 font-bold text-gray-300 mt-8 z-10 relative">
        Barangay Appointment Scheduling System
    </div>

    <!-- Logo at the top -->
    <div class="text-center mt-6 z-10 relative">
        <img src="images/brgy-logo-remove.png" alt="Logo" class="w-28 mx-auto">
    </div>

    <!-- Card container with Tailwind CSS -->
    <div class="flex justify-center mt-12 px-4 z-10 relative">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-9 max-w-xl mx-auto mb-14">
            <!-- User Card -->
            <a href="./pages_user/login.php" class="block">
                <div class="bg-white bg-opacity-30 backdrop-blur-lg shadow-lg rounded-lg overflow-hidden transform transition duration-500 hover:scale-105 max-w-56 max-h-64 cursor-pointer">
                    <div class="relative">
                        <img src="images/user.png" alt="User Logo" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-40">
                            <i class="fas fa-user text-white text-3xl"></i> <!-- User Icon -->
                        </div>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-lg font-medium text-gray-300">User Login</h3>
                    </div>
                </div>
            </a>

            <!-- Admin Card -->
            <a href="./pages/login-admin.php" class="block">
                <div class="bg-white bg-opacity-30 backdrop-blur-lg shadow-lg rounded-lg overflow-hidden transform transition duration-200 hover:scale-105 max-w-60 cursor-pointer">
                    <div class="relative">
                        <img src="images/adminlogo.png" alt="Admin Logo" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-40">
                            <i class="fas fa-user-shield text-white text-3xl"></i> <!-- Admin Icon -->
                        </div>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-lg font-medium text-gray-300">Admin Login</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

</body>

</html>
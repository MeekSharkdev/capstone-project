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

<body class="bg-gray-50 bg-img "src="./images/bg-brgy-staffs.png"">

    <!-- Brgy. Silangan Cubao Quezon City text -->
    <div class="text-center text-xl font-bold text-gray-800 mt-8">
        Brgy. Silangan Cubao Quezon City
    </div>

    <!-- Logo at the top -->
    <div class="text-center mt-6">
        <img src="images/brgylogo.jpg" alt="Logo" class="w-24 mx-auto">
    </div>

    <!-- Card container with Tailwind CSS -->
    <div class="flex justify-center mt-12 px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- User Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition duration-500 hover:scale-105">
                <div class="relative">
                    <img src="images/user.png" alt="User Logo" class="w-full h-36 object-cover">
                    <div class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-40">
                        <i class="fas fa-user text-white text-3xl"></i> <!-- User Icon -->
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-700">User Login</h3>
                    <p class="text-gray-500 mt-2">Access your personal dashboard</p>
                    <button onclick="window.location.href='./pages_user/login.php'"
                        class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-200">Login</button>
                </div>
            </div>

            <!-- Admin Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition duration-200 hover:scale-105">
                <div class="relative">
                    <img src="images/adminlogo.png" alt="Admin Logo" class="w-full h-36 object-cover">
                    <div class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-40">
                        <i class="fas fa-user-shield text-white text-3xl"></i> <!-- Admin Icon -->
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-700">Admin Login</h3>
                    <p class="text-gray-500 mt-2">Manage your website or system</p>
                    <button onclick="window.location.href='./pages/login-admin.php'"
                        class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-200">Login</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

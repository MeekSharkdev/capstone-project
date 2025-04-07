<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <!-- Tailwind CSS link -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        .slide-down {
            animation: slideDown 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-cover bg-center bg-no-repeat bg-img relative fade-in" style="background-image: url('./images/bg-brgy-staffs.png');">

    <!-- Gray background overlay to reduce the emphasis on the background image -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-70 z-0 mx-9"></div>

    <!-- Brgy. Silangan Cubao Quezon City text -->
    <div class="text-center text-2xl mt-6 font-bold text-gray-300 mt-8 z-10 relative slide-down">
        Barangay Appointment Scheduling System
    </div>

    <!-- Logo at the top -->
    <div class="text-center mt-6 z-10 relative slide-down">
        <img src="images/brgy-logo-remove.png" alt="Logo" class="w-28 mx-auto">
    </div>

    <!-- Card container with Tailwind CSS -->
    <div class="container mx-auto mt-10 slide-down">
        <!-- Your card content here -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-2">Welcome to the Barangay Appointment Scheduling System</h2>
            <p class="text-gray-700">Please log in to continue.</p>
            <!-- Add more content as needed -->
        </div>
    </div>

</body>

</html>
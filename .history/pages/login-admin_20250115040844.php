<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">

    <div class="bg-white shadow-lg rounded-lg p-8 max-w-sm w-full">
        <div class="text-center">
            <!-- Logo inside the login container -->
            <img src="images/brgylogo.jpg" alt="Logo" class="mx-auto mb-4 w-20">
            <h2 class="text-2xl font-semibold text-gray-700">Admin Login</h2>
        </div>

        <!-- Display Error Message if exists -->
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger text-red-600 text-sm mb-4">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form method="POST" action="" class="space-y-6">
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="username" id="floating_username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_username" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
            </div>

            <!-- Password with Eye Icon for toggle -->
            <div class="relative z-0 w-full mb-5 group">
                <input type="password" name="password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                <i class="fas fa-eye absolute right-3 top-3 text-gray-500 cursor-pointer" id="toggle-password" onclick="togglePassword()"></i>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm">Don't have an account? <a href="./register-admin.php" class="text-blue-600 hover:underline">Register here</a></p>
        </div>
    </div>

    <!-- JavaScript to toggle visibility of password field -->
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('floating_password');
            var icon = document.getElementById('toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

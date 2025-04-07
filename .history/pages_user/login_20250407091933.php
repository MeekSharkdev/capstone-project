<?php
session_start();

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Database connection
$servername = 'localhost';
$db_username = 'root';
$db_password = '122401';
$dbname = 'dbusers';

// Create connection
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the user
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists, verify the password, and ensure the user is verified
    if ($user && password_verify($password, $user['password'])) {
        if ($user['verified'] == 1) {
            // User is verified, log them in
            $_SESSION['username'] = $user['username'];  // Start the session
            $_SESSION['success'] = "Successfully logged in!";
            header("Location: dashboard.php");  // Redirect to dashboard
            exit();
        } else {
            // User is not verified
            $_SESSION['error'] = "Your account is not verified. Please check your email for verification.";
        }
    } else {
        // Invalid login credentials
        $_SESSION['error'] = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | User</title>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <!-- Bootstrap CSS for alert styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add animations styles -->
    <style>
        .slide-up {
            animation: slideUp 1s ease-out;
        }

        @keyframes slideUp {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .pop-in {
            animation: popIn 1s ease-out;
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Fade in and fade out animations for alerts */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        .fade-out {
            animation: fadeOut 2s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-200 min-h-screen flex justify-center items-center px-4 slide-up">
    <div class="bg-white shadow-2xl rounded-lg p-0 w-full max-w-4xl min-h-64 h-auto grid grid-cols-1 md:grid-cols-2 gap-0 overflow-hidden slide-up">
        <!-- Left Column: Image -->
        <div class="flex items-center justify-center slide-up">
            <img src="../images/bg-form.png" alt="Bg-forms Image" class="w-full h-60 md:h-96 object-cover rounded-lg">
        </div>

        <!-- Right Column: Login Form -->
        <div class="flex flex-col justify-center items-center w-full h-full p-6 md:p-10 slide-up">
            <div class="text-center pop-in">
                <img src="../images/BRGY.__3_-removebg-preview.png" alt="Logo" class="mx-auto mb-4 w-20">
                <h2 class="text-2xl font-semibold text-gray-700">Login</h2>
            </div>

            <!-- Error or Success Alert -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mt-3 fade-in" role="alert" id="error-alert">
                    <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php elseif (isset($_SESSION['success'])): ?>
                <div class="alert alert-success mt-3 fade-in" role="alert" id="success-alert">
                    <strong>Success!</strong> <?php echo $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6 w-full slide-up">
                <div class="relative z-0 w-full mb-5 group slide-up">
                    <input type="text" name="username" id="floating_username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="floating_username" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
                </div>

                <div class="relative z-0 w-full mb-5 group slide-up">
                    <input type="password" name="password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="floating_password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                    <i class="fas fa-eye absolute right-3 top-3 text-gray-500 cursor-pointer" id="toggle-password" onclick="togglePassword()"></i>
                </div>

                <div class="flex justify-between items-center slide-up">
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
                </div>
            </form>

            <div class="text-center mt-4 slide-up">
                <p class="text-sm">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a></p>
            </div>
            <div class="text-sm mt-2">
                <a href="../pages_user/forgot-password.php" class="text-blue-600 hover:underline">Forgot Password?</a>
            </div>
        </div>
    </div>
</body>


<!-- Bootstrap 5 JS (for alert functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Automatically fade out the alert after a few seconds
    setTimeout(function() {
        let successAlert = document.getElementById('success-alert');
        let errorAlert = document.getElementById('error-alert');
        if (successAlert) {
            successAlert.classList.add('fade-out');
        }
        if (errorAlert) {
            errorAlert.classList.add('fade-out');
        }
    }, 3000); // 3 seconds

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
<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

// Establish database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the logged-in username from the session
$username = $_SESSION['username'];

// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Debugging - Check if data is fetched correctly
if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <!-- Correct Tailwind CSS CDN inclusion -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<!-- Navigation Bar (Fixed Top) -->

<body class="bg-gray-200 mb-12 font-sans leading-normal tracking-normal">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-2xl mt-10">

        <!-- User Name Section -->
        <div class="text-center mb-6">
            <h3 class="text-2xl font-lg text-gray-900 capitalize">
                Hi, <?php echo htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['middlename']) . " " . htmlspecialchars($user['lastname']); ?>. This is your personal information.
            </h3>
            <p class="mt-2 text-sm text-gray-500 italic">
                Please ensure your details are accurate. You can edit or update your information at any time.
            </p>
        </div>

        <hr class="my-6">

        <!-- User Information Section -->
        <br>
        <div class="inline-flex items-center justify-center w-full">
            <hr class="mx-9 w-64 h-px my-8 bg-gray-400 border-0 dark:bg-gray-700">
            <span class="justify px-3 font-medium text-gray-900 -translate-x-1/2 bg-white left-1/2 dark:text-white dark:bg-gray-900">User Information</span>
            <hr class="mx-9 w-64 h-px my-8 bg-gray-400 border-0 dark:bg-gray-700">
        </div>

        <!-- User Information Cards -->
        <div class="mb-9">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="font-medium text-gray-600 text-right pr-4">First Name:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['firstname']); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Middle Name:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['middlename']); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Last Name:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['lastname']); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Gender:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['sex']); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Civil Status:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['civilstatus']); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Citizenship:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['citizenship']); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Birthdate:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars(date("F j, Y", strtotime($user['birthdate']))); ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Birth Place:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['birthplace']); ?></div>
            </div>
        </div>

        <div class="inline-flex items-center justify-center w-full">
            <hr class="mx-9 w-64 h-px my-8 bg-gray-400 border-0 dark:bg-gray-700">
            <span class="justify px-3 font-medium text-gray-900 -translate-x-1/2 bg-white left-1/2 dark:text-white dark:bg-gray-900">Contact Information</span>
            <hr class="mx-9 w-64 h-px my-8 bg-gray-400 border-0 dark:bg-gray-700">
        </div>

        <!-- Contact Information Section -->
        <div class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="font-medium text-gray-600 text-right pr-4">Phone Number:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo isset($user['phonenumber']) ? htmlspecialchars($user['phonenumber']) : "Not Available"; ?></div>

                <div class="font-medium text-gray-600 text-right pr-4">Email:</div>
                <div class="text-gray-800 pl-4 sm:text-left"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
            <a href="../pages_user/edit_profile.php" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition duration-300">Edit Profile</a>
            <a href="dashboard.php" class="bg-gray-500 text-white px-6 py-2 rounded-full hover:bg-gray-600 transition duration-300">Back to Dashboard</a>
        </div>
    </div>
</body>


</html>
<?php
// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$verified = 0; // Default verification status
$otp_error = '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp_entered = trim($_POST['otp']);

    // Fetch the OTP from the database for the user
    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp_in_db);
    $stmt->fetch();
    $stmt->close();

    // Check if OTP matches
    if ($otp_entered == $otp_in_db) {
        // Update verification status to 1
        $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $verified = 1;
            // Redirect to login page after successful verification
            header("Location: login.php");
            exit(); // Make sure no further code is executed after the redirect
        }
        $stmt->close();
    } else {
        $otp_error = "Invalid OTP. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">OTP Verification</h2>
        </div>

        <!-- Display Success or Error message -->
        <?php if ($verified === 1): ?>
            <div class="text-center text-green-500 mb-4">
                <h3>Your account is verified!</h3>
                <p>Status: <strong>Verified (1)</strong></p>
            </div>
        <?php elseif ($otp_error): ?>
            <div class="text-center text-red-500 mb-4">
                <p><?php echo $otp_error; ?></p>
            </div>
        <?php endif; ?>

        <!-- OTP Form -->
        <form method="POST" class="space-y-6">
            <div class="relative z-0 w-full group">
                <input type="text" name="otp" id="otp" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                <label for="otp" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter OTP</label>
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg">Verify OTP</button>
        </form>

        <!-- Display Verification Status -->
        <div class="text-center mt-4">
            <p>Verification Status: <strong><?php echo $verified == 1 ? 'Verified' : 'Not Verified'; ?></strong></p>
        </div>
    </div>
</body>

</html>
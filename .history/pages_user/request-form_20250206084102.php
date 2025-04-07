<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's email from session
$user_email = $_SESSION['username'];  // Assuming email is stored in the session

// Fetch all booking details for the logged-in user based on email
$query = "SELECT booking_date, firstname, lastname, middle_initial, email, purpose 
          FROM bookings 
          WHERE email = ? 
          ORDER BY booking_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email); // "s" for string (email is a string)
$stmt->execute();
$result = $stmt->get_result();
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;  // Collect all bookings
}
$stmt->close();

// Check if there are no bookings for the user
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <title>Request Reschedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200 h-screen flex items-center justify-center">
    <div class="w-full max-w-3xl bg-white my-12 p-4 rounded-lg shadow-xl">
        <h2 class="text-2xl font-semibold mb-6 text-center">Request Reschedule</h2>

        <!-- Note at the top of the form -->
        <p class="text-gray-500 italic text-center mb-4">
            Please note: You can only reschedule your appointment once. If you encounter any conflicts or need to reschedule more than once, please reach out to us through our contact form for further assistance.
        </p>

        <!-- Loop through all bookings and display each -->
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <form action="" method="POST" class="w-full mx-auto p-6 bg-white rounded-lg shadow-lg mb-6">
                    <div class="grid grid-cols-2 gap-6 mb-4">
                        <!-- First Name -->
                        <div>
                            <label class="block font-medium">First Name</label>
                            <input type="text" name="firstname" value="<?php echo htmlspecialchars($booking['firstname']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <!-- Middle Initial -->
                        <div>
                            <label class="block font-medium">Middle Initial</label>
                            <input type="text" name="middle_initial" value="<?php echo htmlspecialchars($booking['middle_initial']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-4">
                        <!-- Last Name -->
                        <div>
                            <label class="block font-medium">Last Name</label>
                            <input type="text" name="lastname" value="<?php echo htmlspecialchars($booking['lastname']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block font-medium">Email Address</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-4">
                        <!-- Purpose -->
                        <div>
                            <label class="block font-medium">Purpose</label>
                            <input type="text" name="purpose" value="<?php echo htmlspecialchars($booking['purpose']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <!-- Original Appointment Date -->
                        <div>
                            <label class="block font-medium">Original Appointment Date</label>
                            <input type="text" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-4">
                        <!-- Choose New Date -->
                        <div>
                            <label class="block font-medium">Choose New Date</label>
                            <input type="date" name="request_date" required class="w-full px-4 py-2 border rounded-lg" min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <!-- Reason for Reschedule -->
                        <div>
                            <label class="block font-medium">Reason for Reschedule</label>
                            <textarea name="reason" rows="4" required class="w-full px-4 py-2 border rounded-lg"></textarea>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition w-full">
                            Submit Request
                        </button>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-red-500">No bookings found. Please make a booking to proceed.</p>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="dashboard.php" class="bg-gray-500 text-white px-6 py-2 rounded-full hover:bg-gray-600 transition">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>

</html>

<?php
// Close the database connection at the end of the script
$conn->close();
?>
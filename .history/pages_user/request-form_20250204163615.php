<?php
session_start(); // Ensure session is started

// Debugging to check session id
echo "Session ID: " . session_id() . "<br>"; // Show the session ID
var_dump($_SESSION['id']); // This will print the session variable for debugging

function getBookingDetails($conn, $user_id)
{
    // Use user_id from session to fetch the latest booking record for the logged-in user
    $query = "SELECT booking_date, firstname, lastname, middle_initial, phonenumber, email, purpose FROM bookings WHERE user_id = ? ORDER BY booking_date DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // Binding the user_id from session
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // No booking found for the user
    }
}

// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if session variable is set and then fetch booking details
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $user_id = $_SESSION['id']; // Using the session ID as user identifier
    $booking = getBookingDetails($conn, $user_id);
} else {
    echo "User not logged in.";
    // Exit the script to prevent further execution
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200 h-screen flex items-center justify-center">
    <?php include 'navbar.php'; ?>

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl">
        <h2 class="text-2xl font-semibold mb-6 text-center">Request Reschedule</h2>
        <form action="#" method="POST">
            <!-- First Name -->
            <div class="mb-4">
                <label class="block font-medium">First Name</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($booking['firstname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Middle Initial -->
            <div class="mb-4">
                <label class="block font-medium">Middle Initial</label>
                <input type="text" name="middlename" value="<?php echo htmlspecialchars($booking['middle_initial'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label class="block font-medium">Last Name</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($booking['lastname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Phone Number -->
            <div class="mb-4">
                <label class="block font-medium">Phone Number</label>
                <input type="text" name="phonenumber" value="<?php echo htmlspecialchars($booking['phonenumber'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <label class="block font-medium">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- New Date -->
            <div class="mb-4">
                <label class="block font-medium">Choose New Date</label>
                <input type="date" name="request_date" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Reason -->
            <div class="mb-4">
                <label class="block font-medium">Reason for Reschedule</label>
                <textarea name="reason" rows="4" required class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition w-full">Submit Request</button>
        </form>
    </div>

</body>

</html>

<?php
$conn->close();
?>
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

// Fetch the latest booking details for the logged-in user based on email
$query = "SELECT booking_date, firstname, lastname, middle_initial, email, purpose 
          FROM bookings 
          WHERE email = ? 
          ORDER BY booking_date DESC 
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email); // "s" for string (email is a string)
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

// Debug: Check if booking data is fetched
if ($booking) {
    echo "Booking Data: ";
    var_dump($booking); // Output the fetched booking data
} else {
    echo "No booking found for user with email: $user_email";
}

// Check if there are booking details, otherwise show empty form
if (!$booking) {
    // You can also set default values in case no booking is found
    $booking = [
        'firstname' => '',
        'middle_initial' => '',
        'lastname' => '',
        'email' => $user_email,  // Use the logged-in user's email
        'purpose' => '',
        'booking_date' => ''
    ];
}
?>

<!-- Assuming your HTML form looks like this -->
<form method="POST" action="your-form-action.php">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($booking['firstname']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">

    <label for="middle_initial">Middle Initial:</label>
    <input type="text" id="middle_initial" name="middle_initial" value="<?php echo htmlspecialchars($booking['middle_initial']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($booking['lastname']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">

    <label for="purpose">Purpose:</label>
    <input type="text" id="purpose" name="purpose" value="<?php echo htmlspecialchars($booking['purpose']); ?>" readonly class="w-full px-4 py-2 border rounded-lg">

    <label for="booking_date">Booking Date:</label>
    <input type="text" id="booking_date" name="booking_date" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500">

    <input type="submit" value="Submit" class="btn-submit">
</form>
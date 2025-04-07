<?php
// Start session
session_start();

// If the user is not logged in, show an error or handle accordingly
if (!isset($_SESSION['bookings_id'])) {
    die("User is not logged in.");
}

// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['id'];

// Query to join users and bookings tables and get the latest booking details for the logged-in user
$query = "
    SELECT 
        u.firstname, u.lastname, u.middle_initial, u.phonenumber, u.email, 
        b.purpose, b.booking_date
    FROM 
        users u
    INNER JOIN 
        bookings b ON u.id = b.user_id
    WHERE 
        u.id = ? 
    ORDER BY 
        b.booking_date DESC 
    LIMIT 1
";

if ($stmt = $conn->prepare($query)) {
    // Bind the user ID to the query
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any results
    if ($result->num_rows == 0) {
        die("No bookings found for the logged-in user.");
    }

    // Fetch the booking details
    $booking = $result->fetch_assoc();

    // Assign booking details to variables
    $firstname = $booking['firstname'];
    $lastname = $booking['lastname'];
    $middle_initial = $booking['middle_initial'] ?? ''; // Handle missing middle initial
    $phonenumber = $booking['phonenumber'];
    $email = $booking['email'];
    $purpose = $booking['purpose'];
    $booking_date = $booking['booking_date'];

    // Close the statement
    $stmt->close();
} else {
    die("Error preparing the query: " . $conn->error);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200">

    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 grid grid-cols-2 gap-8">

        <!-- Left Side: Form Fields -->
        <div>
            <h2 class="text-2xl font-semibold mb-6">Request Reschedule</h2>
            <form action="submit-request.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">

                <!-- First Name, Middle Name, and Last Name -->
                <div class="mb-4">
                    <label for="firstname" class="block font-medium">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="middlename" class="block font-medium">Middle Initial</label>
                    <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($middle_initial); ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="lastname" class="block font-medium">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <!-- Phone Number and Email -->
                <div class="mb-4">
                    <label for="phonenumber" class="block font-medium">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" value="<?php echo htmlspecialchars($phonenumber); ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <!-- Calendar for Request Date -->
                <div class="mb-4">
                    <label for="request_date" class="block font-medium">Choose New Date</label>
                    <input type="date" id="request_date" name="request_date" required class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <!-- Reason for Request -->
                <div class="mb-4">
                    <label for="reason" class="block font-medium">Reason for Reschedule</label>
                    <textarea id="reason" name="reason" rows="4" required class="w-full px-4 py-2 mt-2 border rounded-lg"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">Submit Request</button>
            </form>
        </div>

        <!-- Right Side: Booking Details -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Booking Details</h3>
            <div class="space-y-2">
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking_date); ?></p>
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstname); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastname); ?></p>
                <p><strong>Middle Initial:</strong> <?php echo htmlspecialchars($middle_initial); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phonenumber); ?></p>
                <p><strong>Email Address:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Purpose:</strong> <?php echo htmlspecialchars($purpose); ?></p>
            </div>
        </div>
    </div>
</body>

</html>
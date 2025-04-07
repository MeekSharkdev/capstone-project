<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if bookings_id is in GET request
if (isset($_GET['bookings_id'])) {
    $_SESSION['bookings_id'] = $_GET['bookings_id']; // Store in session
}

// Ensure we have a bookings_id
if (!isset($_SESSION['bookings_id'])) {
    die("❌ Error: Booking ID not provided. <br> Debug: " . print_r($_GET, true));
}

$bookings_id = intval($_SESSION['bookings_id']); // Safely cast the bookings ID to an integer

// Fetch booking details
$query = "SELECT * FROM bookings WHERE bookings_id = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bookings_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

// Close connection
$stmt->close();

// Assign values or set defaults
$firstname = $booking['firstname'] ?? 'N/A';
$middle_initial = $booking['middle_initial'] ?? 'N/A';
$lastname = $booking['lastname'] ?? 'N/A';
$phonenumber = $booking['phonenumber'] ?? 'N/A';
$email = $booking['email'] ?? 'N/A';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data
    $new_date = $_POST['request_date'];
    $reason = $_POST['reason'];

    // Insert the reschedule request into the database
    $insert_query = "INSERT INTO reschedule_requests (bookings_id, new_date, reason) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iss", $bookings_id, $new_date, $reason);

    if ($stmt->execute()) {
        echo "✅ Reschedule request submitted successfully!";
    } else {
        echo "❌ Error: Unable to submit reschedule request.";
    }

    $stmt->close();
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

    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10">

        <!-- Left Side: Form Fields -->
        <div>
            <h2 class="text-2xl font-semibold mb-6">Request Reschedule</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="firstname" class="block font-medium">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="middlename" class="block font-medium">Middle Initial</label>
                    <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($middle_initial) ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="lastname" class="block font-medium">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="phonenumber" class="block font-medium">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" value="<?= htmlspecialchars($phonenumber) ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="request_date" class="block font-medium">Choose New Date</label>
                    <input type="date" id="request_date" name="request_date" required class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="reason" class="block font-medium">Reason for Reschedule</label>
                    <textarea id="reason" name="reason" rows="4" required class="w-full px-4 py-2 mt-2 border rounded-lg"></textarea>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">Submit Request</button>
            </form>
        </div>
    </div>

</body>

</html>
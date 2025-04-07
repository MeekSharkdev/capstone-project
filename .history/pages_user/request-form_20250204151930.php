<?php
function getBookingDetails($conn, $user_id)
{
    $query = "SELECT booking_date, firstname, lastname, middle_initial, phonenumber, email, purpose FROM bookings WHERE user_id = ? ORDER BY booking_date DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$user_id = $_SESSION['id'] ?? null;
$booking = $user_id ? getBookingDetails($conn, $user_id) : null;
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
            <form action="#" method="POST">
                <div class="mb-4">
                    <label class="block font-medium">First Name</label>
                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($booking['firstname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Middle Initial</label>
                    <input type="text" name="middlename" value="<?php echo htmlspecialchars($booking['middle_initial'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Last Name</label>
                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($booking['lastname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Phone Number</label>
                    <input type="text" name="phonenumber" value="<?php echo htmlspecialchars($booking['phonenumber'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Choose New Date</label>
                    <input type="date" name="request_date" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Reason for Reschedule</label>
                    <textarea name="reason" rows="4" required class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">Submit Request</button>
            </form>
        </div>
    </div>
</body>

</html>

<?php $conn->close(); ?>
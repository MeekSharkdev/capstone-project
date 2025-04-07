<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest booking details
$query = "SELECT booking_date, firstname, lastname, middle_initial, email, purpose FROM bookings ORDER BY booking_date DESC LIMIT 1";
$result = $conn->query($query);
$booking = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $request_date = $_POST['request_date'];
    $reason = $_POST['reason'];

    // Insert into the request_reschedule table, including purpose
    $stmt = $conn->prepare("INSERT INTO request_reschedule (firstname, middle_initial, lastname, email, date, reason, purpose) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $booking['firstname'], $booking['middle_initial'], $booking['lastname'], $booking['email'], $request_date, $reason, $booking['purpose']);

    if ($stmt->execute()) {
        echo "<script>alert('Reschedule request submitted successfully!'); window.location.href='request-form.php';</script>";
    } else {
        echo "<script>alert('Error submitting request.');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200 h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl">
        <h2 class="text-2xl font-semibold mb-6 text-center">Request Reschedule</h2>

        <!-- Note at the top of the form -->
        <p class="text-gray-500 italic text-center mb-4">
            Please note: You can only reschedule your appointment once. If you encounter any conflicts or need to reschedule more than once, please reach out to us through our contact form for further assistance.
        </p>

        <form action="" method="POST" class="max-w-full mx-auto p-6 bg-white rounded-lg shadow-lg">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- First Name -->
                <div>
                    <label class="block font-medium">First Name</label>
                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($booking['firstname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Middle Initial -->
                <div>
                    <label class="block font-medium">Middle Initial</label>
                    <input type="text" name="middle_initial" value="<?php echo htmlspecialchars($booking['middle_initial'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Last Name -->
                <div>
                    <label class="block font-medium">Last Name</label>
                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($booking['lastname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block font-medium">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Purpose -->
                <div>
                    <label class="block font-medium">Purpose</label>
                    <input type="text" name="purpose" value="<?php echo htmlspecialchars($booking['purpose'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Original Appointment Date -->
                <div>
                    <label class="block font-medium">Original Appointment Date</label>
                    <input type="text" value="<?php echo htmlspecialchars($booking['booking_date'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
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


    </div>
</body>

</html>

<?php
// Close the database connection at the end of the script
$conn->close();
?>
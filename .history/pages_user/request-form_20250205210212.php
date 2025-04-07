<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $request_date = $_POST['request_date']; // New requested date
    $reason = $_POST['reason'];

    // Fetch the latest scheduled date from the bookings table
    $query = "SELECT bookings_date FROM bookings ORDER BY bookings_date DESC LIMIT 1";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $bookings_date = $row['bookings_date'] ?? null; // Store the original scheduled date

    if ($bookings_date) {
        // Insert into request_reschedule (renamed columns)
        $insert_query = "INSERT INTO request_reschedule (firstname, lastname, phonenumber, email, bookings_date, date, reason) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssssss", $firstname, $lastname, $phonenumber, $email, $bookings_date, $request_date, $reason);
        
        if ($stmt->execute()) {
            echo "Request submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "No previous booking found.";
    }
}

$conn->close();
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
        <form action="" method="POST">
            <label class="block font-medium">First Name</label>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($booking['firstname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg mb-4">

            <label class="block font-medium">Middle Initial</label>
            <input type="text" name="middle_initial" value="<?php echo htmlspecialchars($booking['middle_initial'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg mb-4">

            <label class="block font-medium">Last Name</label>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($booking['lastname'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg mb-4">

            <label class="block font-medium">Phone Number</label>
            <input type="text" name="phonenumber" value="<?php echo htmlspecialchars($booking['phonenumber'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg mb-4">

            <label class="block font-medium">Email Address</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email'] ?? ''); ?>" readonly class="w-full px-4 py-2 border rounded-lg mb-4">

            <label class="block font-medium">Choose New Date</label>
            <input type="date" name="request_date" required class="w-full px-4 py-2 border rounded-lg mb-4">

            <label class="block font-medium">Reason for Reschedule</label>
            <textarea name="reason" rows="4" required class="w-full px-4 py-2 border rounded-lg mb-4"></textarea>

            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition w-full">Submit Request</button>
        </form>
    </div>
</body>

</html>

<?php $conn->close(); ?>
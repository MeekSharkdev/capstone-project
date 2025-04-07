<?php
// Start session
session_start();

// Include database connection
require('../config/mysqli_connect.php');

// Check if bookings_id exists in the session
if (isset($_SESSION['bookings_id']) && !empty($_SESSION['bookings_id'])) {
    $bookings_id = $_SESSION['bookings_id'];  // Get the booking ID from the session
} else {
    die("Bookings ID is missing. Please select a booking.");
}

// Prepare and execute the query to fetch booking details
$query = "SELECT firstname, lastname, middle_initial, phonenumber, email, purpose, booking_date FROM bookings WHERE bookings_id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $bookings_id);  // Bind bookings_id to the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if booking exists
    if ($result->num_rows == 0) {
        die("Booking with the provided ID does not exist.");
    }

    // Fetch booking details
    $booking = $result->fetch_assoc();

    // Assigning variables
    $firstname = $booking['firstname'];
    $lastname = $booking['lastname'];
    $middle_initial = $booking['middle_initial'] ?? ''; // Use null coalescing for middle_initial
    $phonenumber = $booking['phonenumber'];
    $email = $booking['email'];
    $purpose = $booking['purpose'];
    $booking_date = $booking['booking_date'];

    // Close statement
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
</head>

<body>
    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; width: 80%; max-width: 1200px; padding: 20px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px;">

            <!-- Left Side: Form Fields -->
            <div>
                <h2 style="text-align: center; margin-bottom: 20px;">Request Reschedule</h2>
                <form action="submit-request.php" method="POST">
                    <input type="hidden" name="bookings_id" value="<?php echo $_SESSION['bookings_id']; ?>" />

                    <!-- First Name, Middle Name, and Last Name -->
                    <div style="margin-bottom: 15px;">
                        <label for="firstname">First Name:</label>
                        <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="middlename">Middle Name:</label>
                        <input type="text" name="middlename" id="middlename" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="lastname">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom: 15px;">
                        <label for="email">Email Address:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
                    </div>

                    <!-- Date Picker -->
                    <div style="margin-bottom: 15px;">
                        <label for="date">Choose Date:</label>
                        <input type="date" name="date" id="date" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
                    </div>

                    <!-- Purpose Dropdown -->
                    <div style="margin-bottom: 15px;">
                        <label for="purpose">Purpose (Documents):</label>
                        <select name="purpose" id="purpose" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="brgy_clearance" <?php echo $purpose == 'brgy_clearance' ? 'selected' : ''; ?>>Barangay Clearance</option>
                            <option value="brgy_indigency" <?php echo $purpose == 'brgy_indigency' ? 'selected' : ''; ?>>Barangay Indigency</option>
                            <option value="brgy_permit" <?php echo $purpose == 'brgy_permit' ? 'selected' : ''; ?>>Barangay Permit</option>
                        </select>
                    </div>

                    <!-- Reason for Request -->
                    <div style="margin-bottom: 15px;">
                        <label for="reason">Reason for Request:</label>
                        <textarea name="reason" id="reason" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" style="width: 100%; padding: 12px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer;">Submit Request</button>
                </form>
            </div>

            <!-- Right Side: Booking Details -->
            <div>
                <h2 style="margin-bottom: 20px;">Booking Details</h2>
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
<?php
// Start session
session_start();

// Include database connection
require('../config/db_connection.php');

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .form-container input,
        .form-container select,
        .form-container textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-container button {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .booking-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .booking-details h2 {
            margin-bottom: 20px;
        }

        .booking-details p {
            margin: 5px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <!-- Left Side: Form Fields -->
            <div>
                <h2>Request Reschedule</h2>
                <form id="requestForm" action="submit-request.php" method="POST">
                    <!-- Hidden input to pass bookings_id -->
                    <input type="hidden" name="bookings_id" value="<?php echo $_SESSION['bookings_id']; ?>" />

                    <!-- First Name, Middle Name, and Last Name -->
                    <div>
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required />
                    </div>
                    <div>
                        <label for="middlename">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" required />
                    </div>
                    <div>
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required />
                    </div>

                    <!-- Email (autofilled) -->
                    <div>
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required />
                    </div>

                    <!-- Date Picker -->
                    <div>
                        <label for="date">Choose Date</label>
                        <input type="date" name="date" id="date" required />
                    </div>

                    <!-- Purpose Dropdown (autofilled) -->
                    <div>
                        <label for="purpose">Purpose (Documents)</label>
                        <select name="purpose" id="purpose" required>
                            <option value="">---,---</option>
                            <option value="brgy_clearance" <?php echo $purpose == 'brgy_clearance' ? 'selected' : ''; ?>>Barangay Clearance</option>
                            <option value="brgy_indigency" <?php echo $purpose == 'brgy_indigency' ? 'selected' : ''; ?>>Barangay Indigency</option>
                            <option value="brgy_permit" <?php echo $purpose == 'brgy_permit' ? 'selected' : ''; ?>>Barangay Permit</option>
                        </select>
                    </div>

                    <!-- Reason for Request -->
                    <div>
                        <label for="reason">Reason for Request</label>
                        <textarea name="reason" id="reason" rows="4" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit">Submit Request</button>
                </form>
            </div>

            <!-- Right Side: Booking Details -->
            <div class="booking-details">
                <h2>Booking Details</h2>
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
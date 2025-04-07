<?php
// Start session
session_start();

// Include database connection
require('../config/db_connection.php');

// Ensure the user is logged in (i.e., session has user ID)
if (!isset($_SESSION['id'])) {
    die("Please log in to view and make a booking request.");
}

$user_id = $_SESSION['id'];  // Assuming user_id is stored in session

// Prepare and execute the query to fetch booking details for the logged-in user
$query = "SELECT firstname, middle_initial, lastname, phonenumber, email, booking_date, purpose FROM bookings WHERE user_id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $user_id);  // Bind user_id to the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user has any bookings
    if ($result->num_rows == 0) {
        die("No bookings found for this user.");
    }

    // Fetch the first booking (assuming the user can only have one booking at a time)
    $booking = $result->fetch_assoc();

    // Assign variables for the booking details
    $firstname = $booking['firstname'];
    $middle_initial = $booking['middle_initial'] ?? '';  // Use null coalescing for missing middle_initial
    $lastname = $booking['lastname'];
    $phonenumber = $booking['phonenumber'];
    $email = $booking['email'];
    $booking_date = $booking['booking_date'];
    $purpose = $booking['purpose'];

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
    <title>Request Reschedule</title>
</head>

<body>
    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; width: 80%; max-width: 1200px; padding: 20px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px;">

            <!-- Left Side: Form Fields -->
            <div>
                <h2 style="text-align: center; margin-bottom: 20px;">Request Reschedule</h2>
                <form action="submit-request.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />

                    <!-- First Name, Middle Name, and Last Name -->
                    <div style="margin-bottom: 15px;">
                        <label for="firstname">First Name:</label>
                        <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="middlename">Middle Name:</label>
                        <input type="text" name="middlename" id="middlename" value="<?php echo htmlspecialchars($middle_initial); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" />
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

            <!-- Right Side: User's Booking Details -->
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

</html><?php
        // Start session
        session_start();

        // Include database connection
        require('../config/db_connection.php');

        // Check if user is logged in
        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $user_id = $_SESSION['id'];  // Get the logged-in user's id from the session
        } else {
            die("User is not logged in.");
        }

        // Prepare and execute the query to fetch the latest booking details for the logged-in user
        $query = "SELECT firstname, lastname, middle_initial, phonenumber, email, purpose, booking_date FROM bookings WHERE user_id = ? ORDER BY booking_date DESC LIMIT 1";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $user_id);  // Bind the user_id to the prepared statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if booking exists
            if ($result->num_rows == 0) {
                die("No bookings found for the logged-in user.");
            }

            // Fetch the booking details
            $booking = $result->fetch_assoc();

            // Assign variables for booking details
            $firstname = $booking['firstname'];
            $lastname = $booking['lastname'];
            $middle_initial = $booking['middle_initial'] ?? ''; // Handle missing middle initial
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
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background-color: white;
            padding: 20px;
            width: 80%;
            max-width: 1000px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            padding-right: 20px;
        }

        .booking-details {
            padding-left: 20px;
            border-left: 2px solid #ddd;
        }

        label {
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .booking-details p {
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Left Side: Form Fields -->
        <div class="form-container">
            <h2>Request Reschedule</h2>
            <form action="submit-request.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">

                <!-- First Name, Middle Name, and Last Name -->
                <div>
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" readonly>

                    <label for="middlename">Middle Initial</label>
                    <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($middle_initial); ?>" readonly>

                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" readonly>
                </div>

                <!-- Phone Number and Email -->
                <div>
                    <label for="phonenumber">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" value="<?php echo htmlspecialchars($phonenumber); ?>" readonly>

                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                </div>

                <!-- Calendar for Request Date -->
                <div>
                    <label for="request_date">Choose New Date</label>
                    <input type="date" id="request_date" name="request_date" required>
                </div>

                <!-- Reason for Request -->
                <div>
                    <label for="reason">Reason for Reschedule</label>
                    <textarea id="reason" name="reason" rows="4" required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit">Submit Request</button>
            </form>
        </div>

        <!-- Right Side: Booking Details -->
        <div class="booking-details">
            <h3>Booking Details</h3>
            <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking_date); ?></p>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstname); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastname); ?></p>
            <p><strong>Middle Initial:</strong> <?php echo htmlspecialchars($middle_initial); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phonenumber); ?></p>
            <p><strong>Email Address:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Purpose:</strong> <?php echo htmlspecialchars($purpose); ?></p>
        </div>
    </div>
</body>

</html>
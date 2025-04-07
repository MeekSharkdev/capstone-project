<?php
// Start session
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: request.php");
    exit();
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
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

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
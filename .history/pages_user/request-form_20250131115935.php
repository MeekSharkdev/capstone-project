<?php
// Start session
session_start();

// Include database connection
require('../config/db_connection.php'); // Ensure this file correctly sets up $conn

// Check if bookings_id exists in the session
if (!isset($_SESSION['bookings_id']) || empty($_SESSION['bookings_id'])) {
    die("Bookings ID is missing. Please select a booking.");
}

// Retrieve bookings_id from session
$bookings_id = $_SESSION['bookings_id'];

// Optionally unset the session value after use (to prevent reuse)
unset($_SESSION['bookings_id']);  // You can remove this if you want the session value to persist for later use

// Prepare and execute the query
$query = "SELECT firstname, lastname, middle_initial, phonenumber, email, purpose, booking_date FROM bookings WHERE bookings_id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $bookings_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if booking exists
    if ($result->num_rows == 0) {
        die("Booking with the provided ID does not exist.");
    }

    $booking = $result->fetch_assoc();

    // Assigning variables
    $firstname = $booking['firstname'];
    $lastname = $booking['lastname'];
    $middle_initial = $booking['middle_initial'] ?? ''; // Prevent errors if NULL
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-200">
    <div class="flex justify-center items-center min-h-screen">
        <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Side: Form Fields -->
            <div class="col-span-1">
                <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Request Reschedule</h2>
                <form id="requestForm" action="submit-request.php" method="POST">
                    <!-- First Name, Middle Name, and Last Name -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="firstname" id="floating_first_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo $firstname; ?>" required />
                            <label for="floating_first_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">First Name</label>
                        </div>
                        <div class="relative z-0 w-full group">
                            <input type="text" name="middlename" id="floating_middle_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                            <label for="floating_middle_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Middle Name</label>
                        </div>
                        <div class="relative z-0 w-full group">
                            <input type="text" name="lastname" id="floating_last_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo $lastname; ?>" required />
                            <label for="floating_last_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Last Name</label>
                        </div>
                    </div>

                    <!-- Email (autofilled) -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="email" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo $email; ?>" required />
                        <label for="floating_email" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Email Address</label>
                    </div>

                    <!-- Date Picker -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="date" name="date" id="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                        <label for="date" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Choose Date</label>
                    </div>

                    <!-- Purpose Dropdown (autofilled) -->
                    <div class="relative z-0 w-full mb-5 group">
                        <select name="purpose" id="purpose" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                            <option value="">---,---</option>
                            <option value="brgy_clearance" <?php echo $purpose == 'brgy_clearance' ? 'selected' : ''; ?>>Barangay Clearance</option>
                            <option value="brgy_indigency" <?php echo $purpose == 'brgy_indigency' ? 'selected' : ''; ?>>Barangay Indigency</option>
                            <option value="brgy_permit" <?php echo $purpose == 'brgy_permit' ? 'selected' : ''; ?>>Barangay Permit</option>
                        </select>
                        <label for="purpose" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Purpose (Documents)</label>
                    </div>

                    <!-- Reason for Request -->
                    <div class="relative z-0 w-full mb-5 group">
                        <textarea name="reason" id="reason" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required></textarea>
                        <label for="reason" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Reason for Request</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="text-white mx-9 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit Request</button>
                </form>
            </div>

            <!-- Right Side: Booking Details -->
            <div class="col-span-1">
                <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Booking Details</h2>
                <div class="space-y-4">
                    <div><strong>Booking Date:</strong> <?php echo $booking_date; ?></div>
                    <div><strong>First Name:</strong> <?php echo $firstname; ?></div>
                    <div><strong>Last Name:</strong> <?php echo $lastname; ?></div>
                    <div><strong>Middle Initial:</strong> <?php echo $middle_initial; ?></div>
                    <div><strong>Phone Number:</strong> <?php echo $phonenumber; ?></div>
                    <div><strong>Email Address:</strong> <?php echo $email; ?></div>
                    <div><strong>Purpose:</strong> <?php echo $purpose; ?></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set the min date to today's date
            const dateInput = document.getElementById('date');
            const today = new Date().toISOString().split('T')[0]; // Get the current date in YYYY-MM-DD format
            dateInput.setAttribute('min', today);
        });
    </script>
</body>

</html>
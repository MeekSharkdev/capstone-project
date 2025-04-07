<?php
require 'db_connect.php'; // Ensure your database connection is included

// Initialize variables with empty values to prevent undefined variable warnings
$bookings_id = $firstname = $lastname = $middle_initial = $phonenumber = $email = $purpose = $booking_date = "";

// Check if bookings_id is set in the URL
if (isset($_GET['bookings_id']) && !empty($_GET['bookings_id'])) {
    $bookings_id = $_GET['bookings_id'];

    // Retrieve booking data from the database
    $query = "SELECT firstname, lastname, middle_initial, phonenumber, email, purpose, booking_date FROM bookings WHERE bookings_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $bookings_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $booking = $result->fetch_assoc();
            $firstname = $booking['firstname'];
            $lastname = $booking['lastname'];
            $middle_initial = $booking['middle_initial'];
            $phonenumber = $booking['phonenumber'];
            $email = $booking['email'];
            $purpose = $booking['purpose'];
            $booking_date = $booking['booking_date'];
        } else {
            echo "<script>alert('No booking found for the given ID.'); window.location.href='dashboard.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error preparing the query.'); window.location.href='dashboard.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Bookings ID is missing.'); window.location.href='dashboard.php';</script>";
    exit();
}
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
                    <input type="hidden" name="bookings_id" value="<?php echo htmlspecialchars($bookings_id); ?>" />

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="firstname" class="input-field" value="<?php echo htmlspecialchars($firstname); ?>" required />
                            <label>First Name</label>
                        </div>
                        <div class="relative z-0 w-full group">
                            <input type="text" name="middlename" class="input-field" required />
                            <label>Middle Name</label>
                        </div>
                        <div class="relative z-0 w-full group">
                            <input type="text" name="lastname" class="input-field" value="<?php echo htmlspecialchars($lastname); ?>" required />
                            <label>Last Name</label>
                        </div>
                    </div>

                    <div class="relative z-0 w-full mb-5 group">
                        <input type="email" name="email" class="input-field" value="<?php echo htmlspecialchars($email); ?>" required />
                        <label>Email Address</label>
                    </div>

                    <div class="relative z-0 w-full mb-5 group">
                        <input type="date" name="date" id="date" class="input-field" required />
                        <label>Choose Date</label>
                    </div>

                    <div class="relative z-0 w-full mb-5 group">
                        <select name="purpose" class="input-field" required>
                            <option value="">--- Select Purpose ---</option>
                            <option value="brgy_clearance" <?php echo ($purpose == 'brgy_clearance') ? 'selected' : ''; ?>>Barangay Clearance</option>
                            <option value="brgy_indigency" <?php echo ($purpose == 'brgy_indigency') ? 'selected' : ''; ?>>Barangay Indigency</option>
                            <option value="brgy_permit" <?php echo ($purpose == 'brgy_permit') ? 'selected' : ''; ?>>Barangay Permit</option>
                        </select>
                        <label>Purpose (Documents)</label>
                    </div>

                    <div class="relative z-0 w-full mb-5 group">
                        <textarea name="reason" class="input-field" rows="4" required></textarea>
                        <label>Reason for Request</label>
                    </div>

                    <button type="submit" class="btn-submit">Submit Request</button>
                </form>
            </div>

            <!-- Right Side: Booking Details -->
            <div class="col-span-1">
                <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Booking Details</h2>
                <div class="space-y-4">
                    <div><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking_date); ?></div>
                    <div><strong>First Name:</strong> <?php echo htmlspecialchars($firstname); ?></div>
                    <div><strong>Last Name:</strong> <?php echo htmlspecialchars($lastname); ?></div>
                    <div><strong>Middle Initial:</strong> <?php echo htmlspecialchars($middle_initial); ?></div>
                    <div><strong>Phone Number:</strong> <?php echo htmlspecialchars($phonenumber); ?></div>
                    <div><strong>Email Address:</strong> <?php echo htmlspecialchars($email); ?></div>
                    <div><strong>Purpose:</strong> <?php echo htmlspecialchars($purpose); ?></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        });
    </script>

    <style>
        .input-field {
            block-size: 2.5rem;
            width: 100%;
            border: none;
            border-bottom: 2px solid gray;
            outline: none;
            background: transparent;
        }
        .btn-submit {
            background-color: #1D4ED8;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            text-align: center;
        }
        .btn-submit:hover {
            background-color: #2563EB;
        }
    </style>
</body>

</html>

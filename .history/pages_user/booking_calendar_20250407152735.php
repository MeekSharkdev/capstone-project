<?php
// Include PHPMailer for SMTP-based email notifications
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload the required files (if you installed via Composer)
require '../vendor/autoload.php';

// Database connection
$host = 'localhost'; // Typically 'localhost' for local setups
$dbname = 'dbusers'; // Your database name
$username = 'root'; // Your MySQL username
$password = '122401'; // Your MySQL password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current month and year
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = $_POST['booking_date'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $middle_initial = $_POST['middle_initial'] ?? null; // New middle initial
    $phonenumber = $_POST['phonenumber'] ?? null;
    $email = $_POST['email'] ?? null;
    $purpose = $_POST['purpose'] ?? null;

    // Validate phone number format
    if (!preg_match('/^09\d{9}$/', $phonenumber)) {
        echo "<script>alert('Phone number must start with 09 and be exactly 11 digits.');</script>";
    } elseif (!$booking_date || !$firstname || !$lastname || !$email || !$purpose) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Check if the booking limit for the day is reached
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM bookings WHERE booking_date = ?");
        $stmt->bind_param("s", $booking_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $bookings_count = $row['count'];

        if ($bookings_count < 20) {
            $stmt = $conn->prepare("INSERT INTO bookings (booking_date, firstname, lastname, middle_initial, phonenumber, email, purpose) VALUES (?, ?, ?, ?, ?, ?, ?)"); // Updated query
            $phonenumber = strval($phonenumber); // Convert to string to avoid range issues
            $stmt->bind_param("sssssss", $booking_date, $firstname, $lastname, $middle_initial, $phonenumber, $email, $purpose); // Updated bind parameters

            if ($stmt->execute()) {
                // Send email notification on successful booking
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'barangaysilangan64@gmail.com'; // Your email address
                    $mail->Password = 'epststkzqlyltvrg'; // Your email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipient
                    $mail->setFrom('no-reply@brgysilangan.com', 'Barangay Silangan (Cubao)');
                    $mail->addAddress($email, $firstname . ' ' . $lastname); // Recipient's email

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Booking Confirmation for Barangay Services';
                    $mail->Body = "
                        <html>
                        <body>
                            <p>Dear $firstname,</p>
                            <p>We are pleased to confirm your booking for the following service:</p>
                            <table style='border-collapse: collapse; width: 100%;'>
                                <tr>
                                    <td><strong>Service:</strong></td>
                                    <td>$purpose</td>
                                </tr>
                                <tr>
                                    <td><strong>Booking Date:</strong></td>
                                    <td>$booking_date</td>
                                </tr>
                            </table>
                            <br>
                            <p>Your booking has been successfully confirmed.
                            <br>
                            Please ensure that you bring any valid IDs, both primary and secondary, when attending your scheduled appointment.
                            <br>
                            If you have any questions or need further assistance, please do not hesitate to reach out to us.</p>
                            <br>
                            <p>Best regards,<br>
                            Barangay Silangan (Cubao)<br>
                            <small>For any inquiries, please contact our support team at <a href='mailto:barangaysilangan64@gmail.com'>barangaysilangan64@gmail.com</a>.</small></p>
                        </body>
                        </html>
                    ";

                    $mail->send();
                    echo "<script>alert('Booking successful! A confirmation email has been sent.');</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Booking successful, but email notification failed: {$mail->ErrorInfo}');</script>";
                }
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Booking limit for this date reached!');</script>";
        }
    }
}

// Fetch bookings for the current month
$start_date = "$year-$month-01";
$end_date = "$year-$month-$days_in_month";

// Fetch the booking data for the month
$sql = "SELECT booking_date, COUNT(*) AS count FROM bookings WHERE booking_date BETWEEN ? AND ? GROUP BY booking_date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[$row['booking_date']] = $row['count'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <title>Appointment Calendar | User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .day-header {
            text-align: center;
            font-weight: bold;
            color: black;
            pointer-events: none;
        }

        .day {
            padding: 20px;
            border: 2px solid #ddd;
            text-align: center;
            transition: background 0.4s ease, box-shadow 0.4s ease, transform 0.3s ease, border-color 0.3s ease;
            border-radius: 12px;
            background: linear-gradient(135deg, #f5f5f5, #e1e1e1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Available Slot (All 20 slots left) */
        .day.available {
            color: green;
            /* Success color for available slots */
        }

        /* Booked Day (At least 1 booked) */
        .day.booked {
            background: rgb(185, 241, 185);
            /* Light green */
            border: 2px solid green;
            /* Green border */
        }

        /* Fully Booked Day (0 slots left) */
        .day.limit-reached {
            background: rgba(255, 99, 71, 0.5) !important;
            /* Light red */
            color: white;
            border: 2px solid red;
        }

        /* Disabled (Past Days & Holidays) */
        .day.disabled {
            background-color: #ccc;
            opacity: 0.6;
            pointer-events: none;
        }

        /* Holiday Styling */
        .day.holiday {
            background: rgba(255, 255, 102, 0.5);
            /* Light yellow */
            border: 2px solid yellow;
            /* Yellow border */
        }

        /* Hover Effect */
        .day:hover {
            cursor: pointer;
            background: linear-gradient(135deg, #66BB6A, #388E3C);
            box-shadow: 0 6px 18px rgba(56, 142, 60, 0.6);
            border-color: #388E3C;
            transform: scale(1.05);
        }

        /* Calendar Container */
        .calendar-container {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            transition: border-color 0.3s ease;
        }

        .calendar-container:hover {
            border-color: #888;
        }

        /* Header & Instructions */
        h5 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .instruction {
            color: rgb(71, 75, 78);
            font-size: 14px;
            font-style: italic;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <h1 class="text-3xl font-semibold mb-6">Booking Calendar</h1>

        <div class="flex justify-between items-center mb-6">
            <!-- Previous Button -->
            <a href="?month=<?= $month == 1 ? 12 : $month - 1 ?>&year=<?= $month == 1 ? $year - 1 : $year ?>"
                class="flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <span class="mr-3 text-lg">&laquo; Previous</span>
            </a>

            <!-- Month and Year Header -->
            <h3 class="text-xl font-semibold text-gray-700"><?= date('F Y', strtotime("$year-$month-01")) ?></h3>

            <!-- Next Button -->
            <a href="?month=<?= $month == 12 ? 1 : $month + 1 ?>&year=<?= $month == 12 ? $year + 1 : $year ?>"
                class="flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <span class="ml-3 text-lg">Next &raquo;</span>
            </a>
        </div>



        <div class="calendar grid grid-cols-7 gap-2 p-4">
            <?php
            $today = date('Y-m-d');
            $days_of_week = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            // Print the day headers
            foreach ($days_of_week as $day) {
                echo "<div class='day day-header text-center font-semibold text-gray-800'><strong>$day</strong></div>";
            }

            $first_day_of_month = date('w', strtotime("$year-$month-01"));
            $empty_cells = $first_day_of_month;

            // Print empty cells before the first day of the month
            for ($i = 0; $i < $empty_cells; $i++) {
                echo '<div class="day"></div>';
            }

            // Define Philippine holidays and special non-working days for 2025
            $holidays = [
                '01-01' => "New Year's Day",
                '01-29' => "Chinese New Year",
                '02-25' => "EDSA People Power Revolution Anniversary",
                '04-09' => "Araw ng Kagitingan",
                '04-17' => "Maundy Thursday",
                '04-18' => "Good Friday",
                '04-19' => "Black Saturday",
                '05-01' => "Labor Day",
                '06-12' => "Independence Day",
                '08-21' => "Ninoy Aquino Day",
                '08-25' => "National Heroes Day",
                '10-31' => "All Saints' Day Eve",
                '11-01' => "All Saints' Day",
                '11-30' => "Bonifacio Day",
                '12-08' => "Feast of the Immaculate Conception of Mary",
                '12-24' => "Christmas Eve",
                '12-25' => "Christmas Day",
                '12-30' => "Rizal Day",
                '12-31' => "Last Day of the Year"
            ];

            // Loop through all the days of the month and display them
            for ($day = 1; $day <= $days_in_month; $day++) {
                $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                $month_day = date('m-d', strtotime($date));
                $is_holiday = isset($holidays[$month_day]);
                $holiday_name = $is_holiday ? $holidays[$month_day] : '';

                $is_booked = isset($bookings[$date]) ? $bookings[$date] : 0;
                $slots_left = 20 - $is_booked;
                $is_limit_reached = $slots_left <= 0;
                $is_past = $date < $today;

                // Assign CSS classes based on booking status
                $day_class = "";
                if ($is_past || $is_holiday) {
                    $day_class = "disabled opacity-50";
                } elseif ($is_holiday) {
                    $day_class = "holiday bg-red-200"; // Apply holiday class
                } elseif ($is_limit_reached) {
                    $day_class = "limit-reached bg-gray-400"; // Fully booked
                } elseif ($is_booked > 0) {
                    $day_class = "booked bg-yellow-300"; // At least 1 booked
                } elseif ($slots_left == 20) {
                    $day_class = "available bg-green-300"; // 20 slots left
                }
            ?>
                <div class="day <?= $day_class ?> p-2 text-center rounded-lg cursor-pointer hover:bg-blue-100" data-date="<?= $date ?>">
                    <strong class="block"><?= $day ?></strong>
                    <?php if ($is_holiday): ?>
                        <div class="holiday text-xs text-red-600"><?= $holiday_name ?></div>
                    <?php elseif (!$is_limit_reached): ?>
                        <div class="<?= $slots_left == 20 ? 'text-green-600' : '' ?> text-xs">
                            <?= $slots_left ?> slots left
                        </div>
                    <?php else: ?>
                        <div class="text-xs text-gray-600">Fully booked</div>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>

        <!-- Tailwind's responsive classes -->
        <style>
            /* Tailwind Media Queries for mobile */
            @media (max-width: 768px) {
                .calendar {
                    grid-template-columns: repeat(7, 1fr);
                    gap: 1rem;
                }

                .day {
                    font-size: 0.75rem;
                    /* Smaller text for mobile */
                    padding: 0.5rem;
                    /* Smaller padding */
                }

                .day-header {
                    font-size: 0.875rem;
                    /* Slightly smaller day headers */
                }
            }
        </style>

    </div>


    <!-- Back Button (Centered) -->
    <div class="mt-4 text-center">
        <a href="dashboard.php" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Back to dashboard
        </a>
    </div>

    <!-- Booking Modal (Form Input) -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog max-w-lg mx-auto">
            <form method="POST" class="modal-content" id="bookingForm">
                <div class="modal-header">
                    <h5 class="modal-title">Make a Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="instruction text-sm text-gray-600">
                        Please ensure that the details you provide here match the information you used during registration to avoid any discrepancies.
                    </div>
                    <input type="hidden" name="booking_date" id="bookingDate">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control p-2 border rounded-md w-full" placeholder=" " required oninput="capitalizeFirstLetter(event)">
                    </div>
                    <div class="mb-3">
                        <label for="middle_initial" class="form-label">Middle Initial</label>
                        <input type="text" name="middle_initial" id="middle_initial" class="form-control p-2 border rounded-md w-full" maxlength="1" placeholder=" " required oninput="capitalizeFirstLetter(event)">
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control p-2 border rounded-md w-full" placeholder=" " required oninput="capitalizeFirstLetter(event)">
                    </div>
                    <div class="mb-3">
                        <label for="phonenumber" class="form-label">Phone Number</label>
                        <input type="tel" name="phonenumber" id="phonenumber" class="form-control p-2 border rounded-md w-full" required pattern="09\d{9}" oninput="validatePhoneNumber()" title="Phone number must start with 09 and be 11 digits.">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control p-2 border rounded-md w-full" required>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <select name="purpose" id="purpose" class="form-select p-2 border rounded-md w-full" required>
                            <option value="Barangay Clearance">Barangay Clearance</option>
                            <option value="Barangay Indigency">Barangay Indigency</option>
                            <option value="Barangay Permit">Barangay Permit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" id="confirmBookingBtn">Confirm Booking</button>
                    <button type="button" class="btn btn-secondary bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
            <script>
                function capitalizeFirstLetter(event) {
                    const value = event.target.value;
                    event.target.value = value.replace(/\b\w/g, char => char.toUpperCase());
                }
            </script>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog max-w-lg mx-auto">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Your Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Booking Date:</strong> <span id="confirmBookingDate"></span></p>
                    <p><strong>First Name:</strong> <span id="confirmFirstName"></span></p>
                    <p><strong>Middle Initial:</strong> <span id="confirmMiddleInitial"></span></p>
                    <p><strong>Last Name:</strong> <span id="confirmLastName"></span></p>
                    <p><strong>Phone Number:</strong> <span id="confirmPhoneNumber"></span></p>
                    <p><strong>Email:</strong> <span id="confirmEmail"></span></p>
                    <p><strong>Purpose:</strong> <span id="confirmPurpose"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" id="finalSubmitBtn">Confirm Booking</button>
                    <button type="button" class="btn btn-secondary bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.day').forEach(day => {
            day.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');
                document.getElementById('bookingDate').value = selectedDate; // Set the selected date to hidden input
                new bootstrap.Modal(document.getElementById('bookingModal')).show(); // Show the booking modal
            });
        });

        document.getElementById('confirmBookingBtn').addEventListener('click', () => {
            const bookingDate = document.getElementById('bookingDate').value;
            const firstname = document.getElementById('firstname').value;
            const middleInitial = document.getElementById('middle_initial').value; // Get middle initial
            const lastname = document.getElementById('lastname').value;
            const phonenumber = document.getElementById('phonenumber').value;
            const email = document.getElementById('email').value;
            const purpose = document.getElementById('purpose').value;

            document.getElementById('confirmBookingDate').textContent = bookingDate;
            document.getElementById('confirmFirstName').textContent = firstname;
            document.getElementById('confirmMiddleInitial').textContent = middleInitial;
            document.getElementById('confirmLastName').textContent = lastname;
            document.getElementById('confirmPhoneNumber').textContent = phonenumber;
            document.getElementById('confirmEmail').textContent = email;
            document.getElementById('confirmPurpose').textContent = purpose;

            new bootstrap.Modal(document.getElementById('confirmationModal')).show(); // Show confirmation modal
        });

        // Final submit confirmation
        document.getElementById('finalSubmitBtn').addEventListener('click', () => {
            document.getElementById('bookingForm').submit(); // Submit the form
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- (Your existing modal code here) -->

    <script>
        document.querySelectorAll('.day').forEach(day => {
            day.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');
                document.getElementById('bookingDate').value = selectedDate; // Set the selected date to hidden input
                new bootstrap.Modal(document.getElementById('bookingModal')).show(); // Show the booking modal
            });
        });

        document.getElementById('confirmBookingBtn').addEventListener('click', () => {
            const bookingDate = document.getElementById('bookingDate').value;
            const firstname = document.getElementById('firstname').value;
            const middleInitial = document.getElementById('middle_initial').value; // Get middle initial
            const lastname = document.getElementById('lastname').value;
            const phonenumber = document.getElementById('phonenumber').value;
            const email = document.getElementById('email').value;
            const purpose = document.getElementById('purpose').value;

            document.getElementById('confirmBookingDate').textContent = bookingDate;
            document.getElementById('confirmFirstName').textContent = firstname;
            document.getElementById('confirmMiddleInitial').textContent = middleInitial; // Display middle initial
            document.getElementById('confirmLastName').textContent = lastname;
            document.getElementById('confirmPhoneNumber').textContent = phonenumber;
            document.getElementById('confirmEmail').textContent = email;
            document.getElementById('confirmPurpose').textContent = purpose;
        });
    </script>

    <script>
        document.querySelectorAll('.day').forEach(day => {
            day.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');

                // Check if the clicked day has the 'limit-reached' class
                if (this.classList.contains('limit-reached')) {
                    alert("This date is fully booked. Please find another date.");
                    location.reload(); // This will reload the page
                    return; // Exit the function to prevent the modal from appearing
                }

                // Proceed with opening the booking modal if the date is not fully booked
                document.getElementById('bookingDate').value = selectedDate; // Set the selected date in the hidden input
                new bootstrap.Modal(document.getElementById('bookingModal')).show(); // Show the modal
            });
        });
    </script>
</body>

</html>
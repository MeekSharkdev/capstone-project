<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "122401"; // Replace with your database password
$dbname = "your_database_name"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

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
    $booking_date = $_POST['booking_date'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO bookings (booking_date, name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $booking_date, $name, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch bookings for the current month
$start_date = "$year-$month-01";
$end_date = "$year-$month-$days_in_month";

$sql = "SELECT booking_date, name FROM bookings WHERE booking_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[$row['booking_date']] = $row['name'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Calendar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        .day {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .day.booked {
            background-color: #f8d7da;
        }
        .day:hover {
            cursor: pointer;
            background-color: #e2e6ea;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1>Booking Calendar</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="?month=<?= $month == 1 ? 12 : $month - 1 ?>&year=<?= $month == 1 ? $year - 1 : $year ?>" class="btn btn-secondary">&laquo; Previous</a>
        <h3><?= date('F Y', strtotime("$year-$month-01")) ?></h3>
        <a href="?month=<?= $month == 12 ? 1 : $month + 1 ?>&year=<?= $month == 12 ? $year + 1 : $year ?>" class="btn btn-secondary">Next &raquo;</a>
    </div>
    <div class="calendar">
        <?php
        for ($day = 1; $day <= $days_in_month; $day++) {
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $is_booked = isset($bookings[$date]);
            ?>
            <div class="day <?= $is_booked ? 'booked' : '' ?>" data-date="<?= $date ?>">
                <strong><?= $day ?></strong>
                <?php if ($is_booked): ?>
                    <div>Booked by <?= htmlspecialchars($bookings[$date]) ?></div>
                <?php endif; ?>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make a Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="booking_date" id="bookingDate">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.day:not(.booked)').forEach(day => {
        day.addEventListener('click', () => {
            const date = day.dataset.date;
            document.getElementById('bookingDate').value = date;
            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        });
    });
</script>
</body>
</html>

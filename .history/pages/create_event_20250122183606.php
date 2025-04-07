<?php
// Database connection
$conn = new mysqli('localhost', 'root', '122401', 'dbusers');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable initialization for success and error messages
$success_message = '';
$error_message = '';

// Query to get total registered users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = mysqli_query($conn, $userQuery);
$totalUsers = mysqli_fetch_assoc($userResult)['total_users'];

// Query to get the number of total bookings
$bookingQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
$bookingResult = mysqli_query($conn, $bookingQuery);
$totalBookings = mysqli_fetch_assoc($bookingResult)['total_bookings'];

// Query to get counts of users marked as completed
$statusQuery = "SELECT COUNT(*) AS completed_users FROM users WHERE status = 'completed'";
$statusResult = mysqli_query($conn, $statusQuery);
$completedUsers = mysqli_fetch_assoc($statusResult)['completed_users'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    // Handle the file upload
    $event_image = '';
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../uploads/';  // Absolute path to the uploads directory
        $image_name = $_FILES['event_image']['name'];
        $image_tmp = $_FILES['event_image']['tmp_name'];
        $image_size = $_FILES['event_image']['size'];
        $image_type = $_FILES['event_image']['type'];

        // Check if the uploaded file is an image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image_type, $allowed_types) && $image_size < 5000000) { // Limit to 5MB
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $new_image_name = uniqid() . '.' . $image_ext;
            $image_path = $upload_dir . $new_image_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                $event_image = $new_image_name; // Save the image name to the database
            } else {
                $error_message = "Error uploading image.";
                exit();
            }
        } else {
            $error_message = "Invalid image file or file too large.";
            exit();
        }
    }

    // Insert event data into the database
    $sql = "INSERT INTO events (event_name, event_date, event_time, event_location, event_description, event_image)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $event_name, $event_date, $event_time, $event_location, $event_description, $event_image);

    if ($stmt->execute()) {
        $success_message = "Event created successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!-- Include the sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Rest of your HTML page -->
<div class="container mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Create New Event</h1>

    <!-- Display success or error message -->
    <?php if ($success_message): ?>
        <div class="bg-green-500 text-white p-4 mb-4 rounded fade-in">
            <?php echo $success_message; ?>
        </div>
    <?php elseif ($error_message): ?>
        <div class="bg-red-500 text-white p-4 mb-4 rounded fade-in">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form action="create_event.php" method="POST" enctype="multipart/form-data">
        <!-- Form Fields -->
        <!-- ... -->
    </form>
</div>
<?php
session_start();

// Initialize variables for SweetAlert
$sweetAlertMessage = '';
$sweetAlertType = '';
$redirectAfterSave = false; // Flag to handle redirection after SweetAlert

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = 'localhost';
$db_username = 'root';
$db_password = '122401';
$dbname = 'dbusers';

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the logged-in user's data
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_firstname = $_POST['firstname'];
    $new_middlename = $_POST['middlename'];
    $new_lastname = $_POST['lastname'];
    $new_phonenumber = $_POST['phonenumber'];
    $new_birthdate = $_POST['birthdate'];
    $new_birthplace = $_POST['birthplace'];
    $new_sex = $_POST['sex'];
    $new_civilstatus = $_POST['civilstatus'];
    $new_citizenship = $_POST['citizenship'];
    $new_email = $_POST['email'];

    // Validate phone number
    if (empty($new_phonenumber)) {
        $sweetAlertMessage = "Phone number cannot be empty.";
        $sweetAlertType = "error";
    } else {
        // Handle change password logic
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password || $confirm_password) { // User attempted to change password
            if ($new_password !== $confirm_password) {
                $sweetAlertMessage = "Passwords do not match.";
                $sweetAlertType = "error";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $updatePasswordStmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $updatePasswordStmt->bind_param("ss", $hashed_password, $username);

                if ($updatePasswordStmt->execute()) {
                    $sweetAlertMessage = "Password changed successfully!";
                    $sweetAlertType = "success";
                    $redirectAfterSave = true;
                } else {
                    $sweetAlertMessage = "Failed to change password.";
                    $sweetAlertType = "error";
                }
            }
        }

        // Update profile data
        $stmt = $conn->prepare("UPDATE users SET 
            username = ?, 
            firstname = ?,
            middlename = ?,
            lastname = ?, 
            phonenumber = ?, 
            birthdate = ?,
            birthplace = ?,
            sex = ?,
            civilstatus = ?,
            citizenship = ?,
            email = ? 
            WHERE username = ?");
        $stmt->bind_param(
            "ssssssssssss",
            $new_username,
            $new_firstname,
            $new_middlename,
            $new_lastname,
            $new_phonenumber,
            $new_birthdate,
            $new_birthplace,
            $new_sex,
            $new_civilstatus,
            $new_citizenship,
            $new_email,
            $username
        );

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username;
            $sweetAlertMessage = "Profile updated successfully!";
            $sweetAlertType = "success";
            $redirectAfterSave = true;
        } else {
            $sweetAlertMessage = "Failed to update profile.";
            $sweetAlertType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10 max-w-xl">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h3 class="text-center text-xl font-semibold mb-4">Edit Profile</h3>
            <form method="POST">
                <div class="grid grid-cols-1 gap-4">
                    <!-- Username -->
                    <div class="relative">
                        <input type="text" id="username" name="username" class="block w-full py-2.5 px-0 text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo htmlspecialchars($user['username']); ?>" required />
                        <label for="username" class="absolute text-sm text-gray-500 transform -translate-y-6 scale-75 top-3 peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
                    </div>

                    <!-- First Name -->
                    <div class="relative">
                        <input type="text" id="firstname" name="firstname" class="block w-full py-2.5 px-0 text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo htmlspecialchars($user['firstname']); ?>" required />
                        <label for="firstname" class="absolute text-sm text-gray-500 transform -translate-y-6 scale-75 top-3 peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First Name</label>
                    </div>

                    <!-- Last Name -->
                    <div class="relative">
                        <input type="text" id="lastname" name="lastname" class="block w-full py-2.5 px-0 text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo htmlspecialchars($user['lastname']); ?>" required />
                        <label for="lastname" class="absolute text-sm text-gray-500 transform -translate-y-6 scale-75 top-3 peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last Name</label>
                    </div>

                    <!-- Phone Number -->
                    <div class="relative">
                        <input type="tel" id="phonenumber" name="phonenumber" class="block w-full py-2.5 px-0 text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo htmlspecialchars($user['phonenumber']); ?>" required />
                        <label for="phonenumber" class="absolute text-sm text-gray-500 transform -translate-y-6 scale-75 top-3 peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone Number</label>
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <input type="email" id="email" name="email" class="block w-full py-2.5 px-0 text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?php echo htmlspecialchars($user['email']); ?>" required />
                        <label for="email" class="absolute text-sm text-gray-500 transform -translate-y-6 scale-75 top-3 peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                    </div>

                    <!-- Submit -->
                    <div class="relative mt-6">
                        <button type="submit" class="w-full py-2.5 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($sweetAlertMessage): ?>
        <script>
            Swal.fire('<?php echo $sweetAlertMessage; ?>', '', '<?php echo $sweetAlertType; ?>').then(() => {
                <?php if ($redirectAfterSave): ?>
                    window.location.href = "profile.php";
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

</body>
</html>

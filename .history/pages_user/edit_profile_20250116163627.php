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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <div class="container mx-auto my-10 p-4 max-w-4xl">
        <div class="bg-white shadow-2xl rounded-lg p-8">
            <h2 class="text-center text-2xl font-semibold mb-6">Edit Profile</h2>
            <form method="POST">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <!-- First Name -->
                        <div class="mb-4">
                            <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                        </div>

                        <!-- Last Name -->
                        <div class="mb-4">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                        </div>

                        <!-- Middle Name -->
                        <div class="mb-4">
                            <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" id="middlename" name="middlename" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['middlename']); ?>" required>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phonenumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="phonenumber" name="phonenumber" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['phonenumber'] ?? ''); ?>" required>
                        </div>

                        <!-- Birthdate -->
                        <div class="mb-4">
                            <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                        </div>

                        <!-- Gender -->
                        <div class="mb-4">
                            <label for="sex" class="block text-sm font-medium text-gray-700">Gender</label>
                            <input type="text" id="sex" name="sex" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['sex']); ?>" required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Last Name -->
                        <div class="mb-4">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                        </div>

                        <!-- Birth Place -->
                        <div class="mb-4">
                            <label for="birthplace" class="block text-sm font-medium text-gray-700">Birth Place</label>
                            <input type="text" id="birthplace" name="birthplace" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['birthplace']); ?>" required>
                        </div>

                        <!-- Civil Status -->
                        <div class="mb-4">
                            <label for="civilstatus" class="block text-sm font-medium text-gray-700">Civil Status</label>
                            <input type="text" id="civilstatus" name="civilstatus" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['civilstatus']); ?>" required>
                        </div>

                        <!-- Citizenship -->
                        <div class="mb-4">
                            <label for="citizenship" class="block text-sm font-medium text-gray-700">Citizen Ship</label>
                            <input type="text" id="citizenship" name="citizenship" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['citizenship']); ?>" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="username" name="username" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>

                        <!-- Change Password -->
                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

            <div class="mt-6 text-center">
                <!-- Update Profile Button -->
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 focus:outline-none">Update Profile</button>
                
                <!-- Cancel Button -->
                <a href="profile.php" class="ml-4 inline-block bg-gray-500 text-white px-6 py-2 rounded-full hover:bg-gray-600 focus:outline-none">Cancel</a>
            </div>

            </form>
        </div>
    </div>

    <script>
        <?php if ($sweetAlertMessage): ?>
        Swal.fire({
            title: "<?php echo $sweetAlertMessage; ?>",
            icon: "<?php echo $sweetAlertType; ?>",
            showConfirmButton: true,
            timer: 3000
        }).then(function() {
            <?php if ($redirectAfterSave): ?>
            window.location.href = 'profile.php';
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>

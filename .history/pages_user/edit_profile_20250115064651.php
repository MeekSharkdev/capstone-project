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
                $new_sex,
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Edit Profile</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <!-- Update Profile Fields -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" 
                                        value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" 
                                        value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phonenumber" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phonenumber" name="phonenumber" 
                                        value="<?php echo htmlspecialchars($user['phonenumber'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="birthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                        value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                
                                <!-- Change Password Section -->
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                
                                <!-- Submit Buttons -->
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

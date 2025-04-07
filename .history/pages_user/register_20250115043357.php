<?php
// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

$errors = [];
$success = false; // Flag to track success

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phonenumber = trim($_POST['phonenumber']);
    $birthdate = trim($_POST['birthdate']);
    $sex = trim($_POST['sex']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Server-side validation
    if (empty($firstname) || empty($lastname) || empty($phonenumber) || empty($birthdate) || empty($sex) || empty($email) || empty($username) || empty($password)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!preg_match('/^[0-9]{11}$/', $phonenumber)) {
        $errors[] = "Phone number must be exactly 11 digits and numeric only.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Insert data if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, lastname, phonenumber, birthdate, email, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

        if ($stmt) {
            $stmt->bind_param("ssssssss", $username, $hashed_password, $firstname, $lastname, $phonenumber, $birthdate, $email, $sex);

            if ($stmt->execute()) {
                $success = true;
            } else {
                $errors[] = "Database error: Unable to execute query.";
            }

            $stmt->close();
        } else {
            $errors[] = "Database error: Unable to prepare statement.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <img src="images/brgylogo.jpg" alt="Logo" class="mx-auto mb-4 w-20">
            <h2 class="text-2xl font-semibold text-gray-700">Register</h2>
        </div>

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger text-red-600 text-sm mb-4">
                <?php echo implode('<br>', $errors); ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <?php if (!$success) : ?>
            <form method="POST" class="space-y-6" id="registration-form" onsubmit="return validateForm();">
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative z-0 w-full group">
                        <input type="text" name="firstname" id="firstname" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="firstname" class="peer-focus:font-medium absolute text-sm text-gray-500">First Name</label>
                    </div>

                    <div class="relative z-0 w-full group">
                        <input type="text" name="lastname" id="lastname" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="lastname" class="peer-focus:font-medium absolute text-sm text-gray-500">Last Name</label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative z-0 w-full group">
                        <input type="tel" name="phonenumber" id="phonenumber" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="phonenumber" class="peer-focus:font-medium absolute text-sm text-gray-500">Phone Number</label>
                    </div>

                    <div class="relative z-0 w-full group">
                        <input type="date" name="birthdate" id="birthdate" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                        <label for="birthdate" class="peer-focus:font-medium absolute text-sm text-gray-500">Birthdate</label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative z-0 w-full group">
                        <select name="sex" id="sex" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                            <option value="">Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <label for="sex" class="peer-focus:font-medium absolute text-sm text-gray-500">Sex</label>
                    </div>

                    <div class="relative z-0 w-full group">
                        <input type="email" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500">Email Address</label>
                    </div>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="username" id="username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="username" class="peer-focus:font-medium absolute text-sm text-gray-500">Username</label>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="password" class="peer-focus:font-medium absolute text-sm text-gray-500">Password</label>
                </div>

                <div class="relative z-0 w-full group">
                    <input type="password" name="confirm_password" id="confirm_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="confirm_password" class="peer-focus:font-medium absolute text-sm text-gray-500">Confirm Password</label>
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">Register</button>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
                    </p>
                </div>
            </form>
        <?php else: ?>
            <script>
                alert('Registered successfully! Redirecting to login...');
                window.location.href = 'login.php';
            </script>
        <?php endif; ?>
    </div>

    <script>
        function validateForm() {
            const phoneNumber = document.getElementById('phonenumber').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!/^[0-9]{11}$/.test(phoneNumber)) {
                alert('Phone number must be 11 numeric digits only.');
                return false;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>

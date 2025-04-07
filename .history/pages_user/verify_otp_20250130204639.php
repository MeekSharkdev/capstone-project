<?php
// Database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$verified = 0; // Default verification status
$otp_error = '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$otp_resend = false; // Flag to check if the OTP is being resent

// Fetch OTP expiration time for checking when the form is submitted
$stmt = $conn->prepare("SELECT otp, otp_expiration_time FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($otp_in_db, $otp_expiration_time);
$stmt->fetch();
$stmt->close();

$current_time = new DateTime();
$expiration_time = new DateTime($otp_expiration_time);
$interval = $current_time->diff($expiration_time);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if OTP is expired (20 seconds expiration)
    if ($interval->s > 20) {
        $otp_error = "OTP has expired. Please request a new one.";
    } else {
        // Check if OTP array is set and not empty
        if (isset($_POST['otp']) && is_array($_POST['otp']) && count($_POST['otp']) === 6) {
            $otp_entered = implode('', $_POST['otp']); // Combine the 6 digits into a single string

            // Check if OTP matches
            if ($otp_entered == $otp_in_db) {
                // Update verification status to 1
                $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $verified = 1;
                    // Redirect to login page after successful verification
                    header("Location: login.php");
                    exit();
                }
                $stmt->close();
            } else {
                $otp_error = "Invalid OTP. Please try again.";
            }
        } else {
            $otp_error = "Please enter a valid 6-digit OTP.";
        }
    }
}

// If the resend button was clicked, regenerate the OTP
if (isset($_POST['resend_otp'])) {
    // Generate a new OTP
    $otp = rand(100000, 999999);

    // Set expiration time to 20 seconds from now
    $expiration_time = date("Y-m-d H:i:s", strtotime('+20 seconds'));

    // Store the new OTP and expiration time in the database
    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiration_time = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $expiration_time, $email);
    $stmt->execute();
    $stmt->close();

    // Set flag to show resend message
    $otp_resend = true;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.onload = function() {
            var otpExpirationTime = <?php echo json_encode($otp_expiration_time); ?>;
            var currentTime = new Date().getTime();
            var expirationTime = new Date(otpExpirationTime).getTime();

            // Check if OTP has expired
            if (currentTime > expirationTime) {
                document.getElementById('otp-error').innerText = 'OTP has expired. Please request a new one.';
                disableOtpFields();
                document.getElementById('resend-button').style.display = 'block'; // Show Resend OTP button
            }
        }

        function disableOtpFields() {
            const otpInputs = document.querySelectorAll('.otp-box');
            otpInputs.forEach(function(input) {
                input.disabled = true;
            });
        }

        function handleInput(event, nextInputId) {
            if (event.target.value.length === 1) {
                // Focus on next input when a digit is entered
                const nextInput = document.getElementById(nextInputId);
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }

        function allowOnlyNumbers(event) {
            const input = event.target;
            input.value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
        }

        function checkIfExpired(event) {
            var otpExpirationTime = <?php echo json_encode($otp_expiration_time); ?>;
            var currentTime = new Date().getTime();
            var expirationTime = new Date(otpExpirationTime).getTime();

            if (currentTime > expirationTime) {
                alert('OTP has expired. Please request a new one.');
                event.preventDefault(); // Prevent form submission
            }
        }
    </script>
    <style>
        .otp-box {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s;
        }

        .otp-box:focus {
            border-color: #4CAF50;
        }
    </style>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">OTP Verification</h2>
        </div>

        <!-- Display Success or Error message -->
        <?php if ($verified === 1): ?>
            <div class="text-center text-green-500 mb-4">
                <h3>Your account is verified!</h3>
                <p>Status: <strong>Verified (1)</strong></p>
            </div>
        <?php elseif ($otp_error): ?>
            <div class="text-center text-red-500 mb-4">
                <p id="otp-error"><?php echo $otp_error; ?></p>
            </div>
        <?php endif; ?>
        <!-- OTP Form -->
        <form method="POST" class="space-y-6" onsubmit="checkIfExpired(event)">
            <div class="grid grid-cols-6 gap-2">
                <!-- 6 individual OTP input fields -->
                <input type="text" name="otp[]" id="otp1" maxlength="1" oninput="handleInput(event, 'otp2'); allowOnlyNumbers(event);" class="otp-box" required>
                <input type="text" name="otp[]" id="otp2" maxlength="1" oninput="handleInput(event, 'otp3'); allowOnlyNumbers(event);" class="otp-box">
                <input type="text" name="otp[]" id="otp3" maxlength="1" oninput="handleInput(event, 'otp4'); allowOnlyNumbers(event);" class="otp-box">
                <input type="text" name="otp[]" id="otp4" maxlength="1" oninput="handleInput(event, 'otp5'); allowOnlyNumbers(event);" class="otp-box">
                <input type="text" name="otp[]" id="otp5" maxlength="1" oninput="handleInput(event, 'otp6'); allowOnlyNumbers(event);" class="otp-box">
                <input type="text" name="otp[]" id="otp6" maxlength="1" oninput="handleInput(event, null); allowOnlyNumbers(event);" class="otp-box">
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg mt-4">Verify OTP</button>
        </form>

        <!-- Resend OTP Button below Verify OTP -->
        <div id="resend-button" class="mt-4 text-center" style="display: block;">
            <form method="POST">
                <button type="submit" name="resend_otp" class="py-2 px-4 bg-blue-600 text-white rounded-lg">
                    Resend OTP
                </button>
            </form>
        </div>
    </div>
</body>

</html>
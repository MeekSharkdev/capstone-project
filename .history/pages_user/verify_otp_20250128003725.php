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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp_entered = implode('', $_POST['otp']); // Combine the 6 digits into a single string

    // Fetch the OTP from the database for the user
    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp_in_db);
    $stmt->fetch();
    $stmt->close();

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
    </script>
    <style><
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
                <p><?php echo $otp_error; ?></p>
            </div>
        <?php endif; ?>

        <!-- OTP Form -->
        <form method="POST" class="space-y-6">
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

        <!-- Display Verification Status -->
        <div class="text-center mt-4">
            <p>Verification Status: <strong><?php echo $verified == 1 ? 'Verified' : 'Not Verified'; ?></strong></p>
        </div>
    </div>
</body>

</html>
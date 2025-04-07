<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $phoneNumber = htmlspecialchars($_POST['phone_number']);
    $birthdate = htmlspecialchars($_POST['birthdate']);
    $email = htmlspecialchars($_POST['email']);

    // Display confirmation message or process data
    echo "<h2>Registration Successful</h2>";
    echo "<p><strong>First Name:</strong> $firstName</p>";
    echo "<p><strong>Last Name:</strong> $lastName</p>";
    echo "<p><strong>Phone Number:</strong> $phoneNumber</p>";
    echo "<p><strong>Birthdate:</strong> $birthdate</p>";
    echo "<p><strong>Email:</strong> $email</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h1>Register Here</h1>
    <form method="POST" action="register.php">
        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}" placeholder="1234567890" required><br><br>

        <label for="birthdate">Birthdate:</label><br>
        <input type="date" id="birthdate" name="birthdate" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="email">Username:</label><br>
        <input type="email" id="username" name="username" required><br><br>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="password2">Confirm Password:</label>
                    <input type="password" id="password2" name="password2" placeholder="Confirm Password">
                </div>

        <button type="submit">Register</button>

        <div class="login-link">
            <p>Already have an account? <a href="../index.php">Log In</a></p>
        </div>
    </form>
</body>
</html>

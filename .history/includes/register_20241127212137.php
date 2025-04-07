<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = htmlspecialchars($_POST['firstname']);
    $lastName = htmlspecialchars($_POST['lastname']);
    $phoneNumber = htmlspecialchars($_POST['phonenumber']);
    $birthdate = htmlspecialchars($_POST['birthdate']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = mysqli_real_escape_string($_POST['password']);
    $password2 = mysqli_real_escape_string($_POST['password2']);
    

    // Display confirmation message or process data
    echo "<h2>Registration Successful</h2>";
    echo "<p><strong>First Name:</strong> $firstname</p>";
    echo "<p><strong>Last Name:</strong> $lastname</p>";
    echo "<p><strong>Phone Number:</strong> $phone  umber</p>";
    echo "<p><strong>Birthdate:</strong> $birthdate</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Username:</strong> $username</p>";
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
        <label for="firstname">First Name:</label><br>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="lastname">Last Name:</label><br>
        <input type="text" id="lastname" name="lastname" required><br><br>

        <label for="phonenumber">Phone Number:</label><br>
        <input type="tel" id="phonenumber" name="phonenumber" pattern="[0-9]{10}" placeholder="09** *** ****" required><br><br>

        <label for="birthdate">Birthdate:</label><br>
        <input type="date" id="birthdate" name="birthdate" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="username">Username:</label><br>
        <input type="username" id="username" name="username" required><br><br>

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

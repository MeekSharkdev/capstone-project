<?php
include('./');
// Establish database connection
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'dbusers';
$dbc = mysqli_connect($servername, $username, $password, $dbname);

if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = htmlspecialchars($_POST['firstname']);
    $lastName = htmlspecialchars($_POST['lastname']);
    $phoneNumber = htmlspecialchars($_POST['phonenumber']);
    $birthdate = htmlspecialchars($_POST['birthdate']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Get the password
    $password2 = $_POST['password2']; // Get the confirm password

    // Check if passwords match
    if ($password !== $password2) {
        echo "Error: Passwords do not match!";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert query to store user data into the database
        $query = "INSERT INTO users (firstname, lastname, phonenumber, birthdate, email, username, password)
                  VALUES ('$firstName', '$lastName', '$phoneNumber', '$birthdate', '$email', '$username', '$hashedPassword')";

        // Execute the query
        if (mysqli_query($dbc, $query)) {
            echo "<h2>Registration Successful</h2>";
        } else {
            echo "Error: " . mysqli_error($dbc);
        }
    }

    // Close the database connection
    mysqli_close($dbc);
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
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
        </div>

        <div class="form-group">
            <label for="password2">Confirm Password:</label><br>
            <input type="password" id="password2" name="password2" required><br><br>
        </div>

        <button type="submit">Register</button>

        <div class="login-link">
            <p>Already have an account? <a href="../index.php">Log In</a></p>
        </div>
    </form>
</body>
</html>

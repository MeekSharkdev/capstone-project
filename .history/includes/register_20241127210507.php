<?php
$servername = 'localhost';
$username = 'root';
$password = '122401';
$dbname = 'clients';

// Create connection
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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Insert query
    $query = "INSERT INTO users (firstname, lastname, phonenumber, birthdate, email, username, password)
              VALUES ('$firstname', '$lastname', '$phoneumber', '$birthdate', '$email', '$username', '$password')";

    // Execute the query
    if (mysqli_query($dbc, $query)) {
        echo "<h2>Registration Successful</h2>";
    } else {
        echo "Error: " . mysqli_error($dbc);
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

        <label for="username">Username:</label><br>
        <input type="username" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Register</button>

        <div class="login-link">
            <p>Already have an account? <a href="../index.php">Log In</a></p>
        </div>
    </form>
</body>
</html>

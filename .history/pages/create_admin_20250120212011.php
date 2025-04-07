<?php
$servername = "localhost";
$username = "root";
$password = "122401";
$dbname = "dbusers";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle admin creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin'])) {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admintbl (firstname, middlename, lastname, phonenumber, email, birthdate, age, sex, role, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $firstname, $middlename, $lastname, $phonenumber, $email, $birthdate, $age, $sex, $role, $username, $password);
    $stmt->execute();
    header("Location: admin-panel.php");
    exit;
}

// Fetch all admins
$result = $conn->query("SELECT * FROM admintbl");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white h-screen p-5">
        <h2 class="text-center text-xl font-bold">Admin Panel</h2>
        <nav class="mt-5">
            <a href="dashboard.php" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a>
            <a href="manage_admins.php" class="block py-2 px-4 hover:bg-gray-700">Manage Admins</a>
            <a href="logout.php" class="block py-2 px-4 hover:bg-red-600">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h1 class="text-2xl font-semibold mb-4">Manage Admins</h1>
        <div class="bg-white p-4 shadow rounded-lg">
            <h2 class="text-xl font-bold mb-3">Create New Admin</h2>
            <form method="POST" class="space-y-3">
                <input type="text" name="firstname" placeholder="Firstname" class="w-full p-2 border rounded">
                <input type="text" name="middlename" placeholder="Middlename" class="w-full p-2 border rounded">
                <input type="text" name="lastname" placeholder="Lastname" class="w-full p-2 border rounded">
                <input type="text" name="phonenumber" placeholder="Phone Number" class="w-full p-2 border rounded">
                <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded">
                <input type="date" name="birthdate" class="w-full p-2 border rounded">
                <input type="number" name="age" placeholder="Age" class="w-full p-2 border rounded">
                <select name="sex" class="w-full p-2 border rounded">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <select name="role" class="w-full p-2 border rounded">
                    <option value="PIO">PIO</option>
                    <option value="Administrator Assistant">Administrator Assistant</option>
                </select>
                <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded">
                <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded">
                <button type="submit" name="create_admin" class="bg-blue-500 text-white px-4 py-2 rounded">Create Admin</button>
            </form>
        </div>

        <h2 class="text-xl font-bold mt-6 mb-3">Admin List</h2>
        <table class="w-full bg-white shadow rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Firstname</th>
                    <th class="p-3 text-left">Lastname</th>
                    <th class="p-3 text-left">Phone</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr class="border-t">
                    <td class="p-3"><?php echo htmlspecialchars($row['firstname']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['lastname']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['phonenumber']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['role']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

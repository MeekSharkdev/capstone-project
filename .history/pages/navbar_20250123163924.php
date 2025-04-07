<!-- navbar.php -->
<?php
// Start session to access user data
session_start();
$username = $_SESSION['username'] ?? 'Guest'; // Default to 'Guest' if not logged in
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php echo "Welcome, " . htmlspecialchars($username); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#upcoming-events">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about-us">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning nav-link text-white" href="./request-form.php">Request Resched</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php" style="color: red;">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
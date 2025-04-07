<?php
// update_appointment.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $userId = $data['userId'];
    $newDate = $data['newDate'];

    // Example: Update the database (replace with actual database code)
    // Assuming you have a PDO connection set up
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $stmt = $pdo->prepare("UPDATE bookings SET appointment_date = :newDate WHERE id = :userId");
        $stmt->execute([
            ':newDate' => $newDate,
            ':userId' => $userId
        ]);

        // Return a response
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>

requested_reschedule.php
<?php
session_start();

// Include PHPMailer for SMTP-based email notifications
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload the required files (if you installed via Composer)
require '../vendor/autoload.php';

// Database connection using PDO
$host = 'localhost'; // Typically 'localhost' for local setups
$dbname = 'dbuser'; // Your database name
$username = 'root'; // Your MySQL username
$password = '224'; // Your MySQL password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Could not connect to the database $dbname: " . $e->getMessage());
}

// Query to get reschedule requests
$query = "
    SELECT 
        id, 
        firstname,
        middlename,
        lastname,
        email,
        date,
        purpose,
        reason
    FROM 
        request_reschedule
    ORDER BY id DESC";

try {
  $stmt = $pdo->query($query);
} catch (PDOException $e) {
  die("Query failed: " . $e->getMessage());
}

// Function to send approval email using PHPMailer
function sendApprovalEmail($recipientEmail, $subject, $message)
{
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'barangaysilangan64@gmail.com'; // Your email address
    $mail->Password = 'epststkzqlyltvrg'; // Your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom('barangaysilangan64@gmail.com', 'BRGY Admin');
    $mail->addAddress($recipientEmail); // Recipient's email

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send email
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']) && isset($_POST['id'])) {
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  $id = $_POST['id']; // Reschedule request ID

  // Send the email with the custom subject and message
  if (sendApprovalEmail($email, $subject, $message)) {
    // After sending the email, update the request status in the database
    try {
      // Update the request status
      $updateQuery = "UPDATE request_reschedule SET status = 'Approved' WHERE id = :id";
      $stmt = $pdo->prepare($updateQuery);
      $stmt->bindParam(':id', $id);
      $stmt->execute();

      echo 'Email sent and status updated.';
    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
  } else {
    echo 'Email sending failed.';
  }
  exit; // Prevent further page loading
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requested Reschedule</title>
  <link rel="icon" href="images/brgy-fav.png" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-200">

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Main Content Area -->
  <div class="sm:ml-64 p-6">
    <h2 class="text-center text-2xl font-bold mb-6">Requested Reschedule</h2>

    <!-- Search Bar -->
    <div class="mb-4 flex justify-center">
      <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for names or details..." class="px-4 py-2 border rounded-lg w-full md:w-1/2 text-gray-700">
    </div>

    <!-- Table Container -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
      <table class="w-full text-sm text-left text-gray-500" id="rescheduleTable">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3">ID</th>
            <th scope="col" class="px-6 py-3">First Name</th>
            <th scope="col" class="px-6 py-3">Middle Name</th>
            <th scope="col" class="px-6 py-3">Last Name</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" class="px-6 py-3">Date</th>
            <th scope="col" class="px-6 py-3">Purpose</th>
            <th scope="col" class="px-6 py-3">Reason</th>
            <th scope="col" class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($stmt->rowCount() > 0):
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
          ?>
              <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4"><?= htmlspecialchars($row['id']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['firstname']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['middlename']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['lastname']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['date']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['purpose']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['reason']); ?></td>
                <td class="px-6 py-4">
                  <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="openEmailModal('<?= htmlspecialchars($row['email']); ?>', <?= $row['id']; ?>)">
                    Send Email
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="text-center px-6 py-4">No reschedule requests found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Email Modal -->
  <div id="emailModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
      <h2 class="text-xl font-bold mb-4">Send Email</h2>
      <form id="emailForm">
        <label class="block text-sm font-medium text-gray-700">Recipient</label>
        <input type="email" id="emailRecipient" class="w-full p-2 border rounded mb-4" readonly>

        <!-- Subject Input -->
        <label class="block text-sm font-medium text-gray-700">Subject</label>
        <input type="text" id="emailSubject" class="w-full p-2 border rounded mb-4" required>

        <!-- Message Textarea -->
        <label class="block text-sm font-medium text-gray-700">Message</label>
        <textarea id="emailMessage" class="w-full p-2 border rounded mb-4" rows="4" required></textarea>

        <!-- Action Buttons -->
        <div class="flex justify-end">
          <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded mr-2" onclick="closeEmailModal()">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Send</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Open the email modal and populate the recipient's email
    function openEmailModal(email, id) {
      document.getElementById('emailRecipient').value = email;
      document.getElementById('emailModal').classList.remove('hidden');
      document.getElementById('emailForm').dataset.id = id; // Save the ID in form data
    }

    // Close the email modal
    function closeEmailModal() {
      document.getElementById('emailModal').classList.add('hidden');
    }

    // Listen for the form submission and handle the email sending
    document.getElementById('emailForm').addEventListener('submit', function(e) {
      e.preventDefault();

      var email = document.getElementById('emailRecipient').value;
      var subject = document.getElementById('emailSubject').value;
      var message = document.getElementById('emailMessage').value;
      var id = this.dataset.id;

      // Validate if the fields are not empty
      if (!subject || !message) {
        alert('Please fill in both subject and message fields.');
        return;
      }

      // Send email via AJAX
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          alert('Email sent successfully to ' + email + '!'); // Display recipient email
          closeEmailModal();
          location.reload(); // Reload to show updated status
        } else {
          alert('Email sending failed.');
        }
      };
      xhr.send('email=' + encodeURIComponent(email) + '&subject=' + encodeURIComponent(subject) + '&message=' + encodeURIComponent(message) + '&id=' + encodeURIComponent(id));
    });
  </script>
</body>

</html>
<?php
session_start();

// Include PHPMailer for SMTP-based email notifications
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload the required files (if you installed via Composer)
require '../vendor/autoload.php';

// Database connection using PDO
$host = 'localhost';
$dbname = 'dbusers';
$username = 'root';
$password = '122401';

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
        middle_initial,  // Fixed typo here
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
function sendApprovalEmail($recipientEmail, $firstname, $lastname, $subject, $message)
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
  $id = $_POST['id']; // Reschedule request ID

  // Fetch user details from the database
  try {
    $stmt = $pdo->prepare("SELECT firstname, lastname, email, purpose FROM request_reschedule WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $firstname = $user['firstname'];
      $lastname = $user['lastname'];
      $email = $user['email'];
      $purpose = $user['purpose'];

      // Prepare the subject and message dynamically
      $subject = 'Your Reschedule Request Has Been Approved';
      $message = "
                <html>
                    <body>
                      <p>Dear $firstname $lastname,</p>
                      <p>We are pleased to inform you that your request to reschedule the following service has been approved:</p>
                       <table style='border-collapse: collapse; width: 100%;'>
                          <tr>
                              <td><strong>Service:</strong></td>
                              <td>" . ucwords(str_replace('_', ' ', $purpose)) . "</td>
                          </tr>
                          <tr>
                              <td><strong>Original Appointment Date:</strong></td>
                              <td>$original_date</td>
                          </tr>
                          <tr>
                              <td><strong>Rescheduled Appointment Date:</strong></td>
                              <td>$rescheduled_date</td>
                          </tr>
                      </table>
                       <br>
                      <p>Please remember to bring the following valid IDs with you to verify your identity during your appointment:</p>
                      <ul>
                          <li>Government-issued ID (e.g., Driver's License, Passport, etc.)</li>
                          <li>Any additional documentation as required for the service (if applicable)</li>
                      </ul>
                      <p>Your rescheduled appointment is now confirmed. If you have any further questions or need to make additional adjustments, please don’t hesitate to contact us at <a href='mailto:barangaysilangan64@gmail.com'>barangaysilangan64@gmail.com</a>.</p>
                      <br>
                      <p>Best regards,<br>
                      Barangay Silangan (Cubao)</p>
                  </body>
                </html>
              ";

      // Send the approval email
      if (sendApprovalEmail($email, $firstname, $lastname, $subject, $message)) {
        // After sending the email, update the request status in the database
        try {
          // Update the request status to 'Approved'
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
    } else {
      echo 'Request not found.';
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
  exit; // Prevent further page loading
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requested Reschedule | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gray-200">
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
            <th scope="col" class="px-6 py-3">Middle Initial</th>
            <th scope="col" class="px-6 py-3">Last Name</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" class="px-6 py-3">Req Date</th>
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
                <td class="px-6 py-4"><?= $row['id']; ?></td>
                <td class="px-6 py-4"><?= $row['firstname']; ?></td>
                <td class="px-6 py-4"><?= $row['middle_initial']; ?></td> <!-- Fixed typo here -->
                <td class="px-6 py-4"><?= $row['lastname']; ?></td>
                <td class="px-6 py-4"><?= $row['email']; ?></td>
                <td class="px-6 py-4"><?= $row['date']; ?></td>
                <td class="px-6 py-4"><?= $row['purpose']; ?></td>
                <td class="px-6 py-4"><?= $row['reason']; ?></td>
                <td class="px-6 py-4">
                  <form method="POST" action="">
                    <button type="submit" name="id" value="<?= $row['id']; ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                      Approve
                    </button>
                  </form>
                </td>
              </tr>
          <?php endwhile;
          endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Function to filter table rows based on search input
    function filterTable() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toUpperCase();
      const table = document.getElementById('rescheduleTable');
      const trs = table.getElementsByTagName('tr');

      for (let i = 0; i < trs.length; i++) {
        const tds = trs[i].getElementsByTagName('td');
        let showRow = false;

        for (let j = 0; j < tds.length; j++) {
          const td = tds[j];
          if (td && td.innerText.toUpperCase().indexOf(filter) > -1) {
            showRow = true;
            break;
          }
        }

        if (showRow) {
          trs[i].style.display = '';
        } else {
          trs[i].style.display = 'none';
        }
      }
    }
  </script>
</body>

</html>
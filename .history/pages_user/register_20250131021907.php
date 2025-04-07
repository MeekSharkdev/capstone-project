    <?php
    // Include PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php';  // Adjust this path as per your project setup

    // Send OTP email function
    function sendOTPEmail($email, $otp)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Use your email service provider's SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'barangaysilangan64@gmail.com';  // Your email address
            $mail->Password = 'epststkzqlyltvrg';  // Your email password (use app password if 2FA enabled)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('barangaysilangan64@gmail.com', 'Barangay Silangan');
            $mail->addAddress($email);  // Add recipient's email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Verification Code';

            // Email Body (HTML Format)
            $mail->Body = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .header {
                background-color: #4CAF50;
                color: #ffffff;
                padding: 15px;
                border-radius: 8px;
                text-align: center;
            }
            .content {
                margin-top: 20px;
                font-size: 16px;
                line-height: 1.6;
                color: #333333;
            }
            .otp-code {
                font-size: 24px;
                font-weight: bold;
                color: #4CAF50;
                padding: 10px;
                background-color: #f4f4f4;
                border: 2px solid #4CAF50;
                display: inline-block;
                margin-top: 10px;
            }
            .footer {
                margin-top: 30px;
                font-size: 14px;
                color: #888888;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Barangay Silangan OTP Verification</h2>
            </div>
            <div class="content">
                <p>Dear User,</p>
                <p>Thank you for using Barangay Silangan services. To verify your request, please use the OTP code below:</p>
                <p class="otp-code">' . $otp . '</p>
                <p>This code will expire in 15 minutes. If you did not request this code, please ignore this email.</p>
            </div>
            <div class="footer">
                <p>Best regards,<br>Barangay Silangan</p>
                <p><a href="mailto:barangaysilangan64@gmail.com" style="color:rgb(158, 221, 160);">Contact us</a> for any inquiries.</p>
            </div>
        </div>
    </body>
    </html>
    ';



            $mail->send();
        } catch (Exception $e) {
            return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    }

    // OTP generation function
    function generateOTP()
    {
        return rand(100000, 999999);  // Generate a 6-digit OTP
    }

    // Database connection
    $servername = 'localhost';
    $username = 'root';
    $password = '122401';
    $dbname = 'dbusers';

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    $errors = [];
    $success = false; // Flag to track success

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $firstname = trim($_POST['firstname']);
        $middlename = trim($_POST['middlename']);
        $lastname = trim($_POST['lastname']);
        $phonenumber = trim($_POST['phonenumber']);
        $birthdate = trim($_POST['birthdate']);
        $birthplace = trim($_POST['birthplace']);
        $sex = trim($_POST['sex']);
        $civilstatus = trim($_POST['civilstatus']);
        $citizenship = trim($_POST['citizenship']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Server-side validation
        if (empty($firstname) || empty($middlename) || empty($lastname) || empty($phonenumber) || empty($birthdate) || empty($birthplace) || empty($sex) || empty($civilstatus) || empty($citizenship) || empty($email) || empty($username) || empty($password)) {
            $errors[] = "Please fill in all required fields.";
        }

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!preg_match('/^[0-9]{11}$/', $phonenumber)) {
            $errors[] = "Phone number must be exactly 11 digits.";
        }

        // Insert data if no errors
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare('INSERT INTO users (username, password, firstname, middlename, lastname, phonenumber, birthdate, birthplace, sex, civilstatus, citizenship, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

            if ($stmt) {
                $stmt->bind_param("ssssssssssss", $username, $hashed_password, $firstname, $middlename, $lastname, $phonenumber, $birthdate, $birthplace, $sex, $civilstatus, $citizenship, $email);

                if ($stmt->execute()) {
                    // Generate OTP
                    $otp = generateOTP();

                    // Store OTP in the database (you should create a table for OTPs or add it to the users table)
                    $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
                    $stmt->bind_param("ss", $otp, $email);
                    $stmt->execute();

                    // Send OTP email
                    sendOTPEmail($email, $otp);

                    $success = true;
                } else {
                    $errors[] = "Database error: Unable to execute query.";
                }

                $stmt->close();
            } else {
                $errors[] = "Database error: Unable to prepare statement.";
            }
        }

        $conn->close();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register | User</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert CDN -->
        <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome CDN for Eye Icon -->
        <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
        <style>
            .fade-in-slide-up {
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInSlideUpAnimation 1s ease-out forwards;
            }

            @keyframes fadeInSlideUpAnimation {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
        <script>
            function validateForm() {
                // Validate each field individually
                var valid = true;

                var firstname = document.getElementById("floating_firstname").value;
                if (firstname === "") {
                    alert("First Name is required");
                    valid = false;
                }

                var phonenumber = document.getElementById("floating_phonenumber").value;
                if (!/^[0-9]{11}$/.test(phonenumber)) {
                    alert("Phone number must be exactly 11 digits");
                    valid = false;
                }

                var email = document.getElementById("floating_email").value;
                if (!/\S+@\S+\.\S+/.test(email)) {
                    alert("Invalid email format");
                    valid = false;
                }

                var password = document.getElementById("floating_password").value;
                var confirmPassword = document.getElementById("floating_confirm_password").value;
                if (password.length < 6) {
                    alert("Password must be at least 6 characters long");
                    valid = false;
                } else if (password !== confirmPassword) {
                    alert("Passwords do not match");
                    valid = false;
                }

                return valid;
            }
        </script>
    </head>

    <body class="bg-gray-200 h-screen flex justify-center items-center">
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full sm:w-4/5 md:w-3/5 lg:w-2/5">
            <div class="text-center mb-6">
                <img src="../images/BRGY.__3_-removebg-preview.png" alt="Logo" class="mx-auto mb-4 w-20">
                <h2 class="text-2xl font-semibold text-gray-700">Register</h2>
            </div>

            <!-- Display SweetAlert for errors -->
            <?php if (!empty($errors)): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '<?php echo implode('<br>', $errors); ?>',
                    });
                </script>
            <?php endif; ?>

            <!-- Display SweetAlert for success -->
            <?php if ($success): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration successful!',
                        text: 'Check your email for the OTP.',
                        confirmButtonText: 'Go to OTP Verification',
                        willClose: () => {
                            window.location.href = "verify_otp.php?email=<?php echo urlencode($email); ?>"; // Redirect to verify OTP page
                        }
                    });
                </script>
            <?php endif; ?>

            <!-- Registration Form -->
            <?php if (!$success) : ?>
                <form method="POST" class="space-y-6 fade-in-slide-up" onsubmit="return validateForm()">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="firstname" id="floating_firstname" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required oninput="capitalizeFirstLetter(event)" />
                            <label for="floating_firstname" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First Name</label>
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="text" name="middlename" id="floating_middlename" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required oninput="capitalizeFirstLetter(event)" />
                            <label for="floating_middlename" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Middle Name</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="lastname" id="floating_lastname" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required oninput="capitalizeFirstLetter(event)" />
                            <label for="floating_lastname" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last Name</label>
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="text" name="phonenumber" id="floating_phonenumber" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required oninput="capitalizeFirstLetter(event)" />
                            <label for="floating_phonenumber" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone Number</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <input type="date" name="birthdate" id="floating_birthdate" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                            <label for="floating_birthdate" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Birthdate</label>
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="text" name="birthplace" id="floating_birthplace" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required oninput="capitalizeFirstLetter(event)" />
                            <label for="floating_birthplace" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Birthplace</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <select name="sex" id="floating_sex" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <label for="floating_sex" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Sex</label>
                        </div>

                        <div class="relative z-0 w-full group">
                            <select name="civilstatus" id="civilstatus" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                                <option value="" disabled selected hidden></option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Separated">Separated</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                            <label for="civilstatus" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Civil Status</label>
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="citizenship" id="floating_citizenship" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required oninput="capitalizeFirstLetter(event)" />
                            <label for="floating_citizenship" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Citizenship</label>
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="email" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required " />
                            <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                        </div>
                    </div>

                    <div class="relative z-0 w-full group">
                        <input type="text" name="username" id="floating_username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="floating_username" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
                    </div>

                    <div class="relative z-0 w-full group">
                        <input type="password" name="password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="floating_password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                    </div>

                    <div class="relative z-0 w-full group">
                        <input type="password" name="confirm_password" id="floating_confirm_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="floating_confirm_password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm Password</label>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-700 text-white rounded-lg">Register</button>
                    <div class="text-sm text-center mt-2">
                        <a href="../pages_user/login.php" class="text-blue-600 hover:underline">Already have an account?</a>
                    </div>
                </form>
                <script>
                    function capitalizeFirstLetter(event) {
                        const value = event.target.value;
                        event.target.value = value.replace(/\b\w/g, char => char.toUpperCase());
                    }
                </script>

            <?php endif; ?>
        </div>
    </body>

    </html>
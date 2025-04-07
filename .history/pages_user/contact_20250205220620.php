<?php
session_start(); // Ensure the session is started
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Appointment Scheduling System</title>
    <!-- Tailwind CSS CDN Link -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for Calendar Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
</head>

<body class="bg-gray-200">


    <div class="min-h-screen flex items-center shadow-2xl justify-center py-12 px-4 sm:px-6 lg:px-8 mt-9">
        <div class="max-w-3xl w-full bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Contact Us</h2>

            <form action="contact_submit.php" method="POST" class="space-y-6">

                <div class="instructions text-center mb-6">
                    <p class="text-lg italic text-gray-500">Please provide as much detail as possible about your concerns, feedback, or suggestions regarding our Appointment Scheduling System. Your input is valuable to us and will help us improve the user experience. Thank you!</p>
                </div>

                <!-- Full Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative z-0 w-full group">
                        <input type="text" id="first_name" name="first_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                        <label for="first_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">First Name</label>
                    </div>
                    <div class="relative z-0 w-full group">
                        <input type="text" id="middle_name" name="middle_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                        <label for="middle_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Middle Name (Optional)</label>
                    </div>
                    <div class="relative z-0 w-full group">
                        <input type="text" id="last_name" name="last_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                        <label for="last_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Last Name</label>
                    </div>
                    <!-- Phone Number Field Added to the Right -->
                    <div class="relative z-0 w-full group">
                        <input type="text" id="phone_number" name="phone_number" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" pattern="^[0-9]{11}$" title="Please enter exactly 11 digits" required />
                        <label for="phone_number" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Phone Number</label>
                    </div>
                </div>

                <!-- Email Address -->
                <div class="relative z-0 w-full group mb-6">
                    <input type="email" id="email" name="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                    <label for="email" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Email Address</label>
                </div>

                <!-- Subject/Concern Type -->
                <div class="relative z-0 w-full group mb-6">
                    <select id="subject" name="subject" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                        <option value="appointment">Appointment Scheduling Concern</option>
                        <option value="feedback">Feedback/Suggestions</option>
                        <option value="technical">Technical Problem</option>
                        <option value="other">Other Concerns</option>
                    </select>
                </div>

                <!-- Message/Description -->
                <div class="relative z-0 w-full group mb-6">
                    <textarea id="message" name="message" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required></textarea>
                    <label for="message" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Message/Description</label>
                </div>

                <!-- Preferred Contact Method -->
                <div class="mb-6">
                    <label for="contact" class="block text-sm font-medium text-gray-700">Preferred Contact Method</label>
                    <div class="flex items-center mt-2">
                        <input type="radio" id="email_contact" name="contact_method" value="email" checked class="mr-2">
                        <label for="email_contact" class="text-sm text-gray-700">Email</label>
                        <input type="radio" id="phone_contact" name="contact_method" value="phone" class="ml-4 mr-2">
                        <label for="phone_contact" class="text-sm text-gray-700">Phone</label>
                    </div>
                </div>

                <!-- Date of Submission with Calendar Icon -->
                <div class="relative z-0 w-full group mb-6">
                    <div class="flex items-center">
                        <input type="date" id="submission_date" name="submission_date" value="<?php echo date('Y-m-d'); ?>" readonly class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                        <span class="absolute right-3 text-gray-600 cursor-pointer"><i class="fas fa-calendar"></i></span>
                    </div>
                    <label for="submission_date" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Date of Submission</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">Submit</button>
            </form>
            <div class="mt-4 text-center">
                <a href="dashboard.php" class="bg-gray-500 text-white px-6 py-2 rounded-full hover:bg-gray-600 transition">
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>

</body>

</html>
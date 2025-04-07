<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-200">
    <div class="flex justify-center items-center min-h-screen">
        <div class="max-w-md mx-auto bg-white shadow-2xl rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Request Reschedule</h2>
            <form id="requestForm" action="submit-request.php" method="POST">
                <!-- First Name, Middle Name, and Last Name -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                    <div class="relative z-0 w-full group">
                        <input type="text" name="firstname" id="floating_first_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="floating_first_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">First Name</label>
                    </div>
                    <div class="relative z-0 w-full group">
                        <input type="text" name="middlename" id="floating_middle_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="floating_middle_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Middle Name</label>
                    </div>
                    <div class="relative z-0 w-full group">
                        <input type="text" name="lastname" id="floating_last_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                        <label for="floating_last_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Last Name</label>
                    </div>
                </div>

                <!-- Email -->
                <div class="relative z-0 w-full mb-5 group">
                    <input type="email" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="floating_email" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Email Address</label>
                </div>

                <!-- Date Picker -->
                <div class="relative z-0 w-full mb-5 group">
                    <input type="date" name="date" id="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                    <label for="date" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Choose Date</label>
                </div>

                <!-- Purpose Dropdown -->
                <div class="relative z-0 w-full mb-5 group">
                    <select name="purpose" id="purpose" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                        <option value="">---,---</option>
                        <option value="brgy_clearance">Barangay Clearance</option>
                        <option value="brgy_indigency">Barangay Indigency</option>
                        <option value="brgy_permit">Barangay Permit</option>
                    </select>
                    <label for="purpose" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Purpose (Documents)</label>
                </div>

                <!-- Reason for Request -->
                <div class="relative z-0 w-full mb-5 group">
                    <textarea name="reason" id="reason" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required></textarea>
                    <label for="reason" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Reason for Request</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="text-white mx-auto bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit Request</button>

                <!-- Back to Dashboard Button -->
                <a href="dashboard.php" class="mt-4 inline-block text-center text-blue-700 hover:text-blue-800">
                <button type="button" class="text-white mr-2 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Back to Dashboard</button>
            </a>
            </form>
        </div>
    </div>

    <script>
        // Trigger SweetAlert2 upon form submission
        document.getElementById('requestForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Check if all required fields are filled
            const form = e.target;
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            // Loop through all required fields to check if any are empty
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500'); // Optional: add red border to empty field
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Show SweetAlert based on the validation result
            if (!isValid) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please fill in all required fields.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                // If form is valid, show success message
                Swal.fire({
                    title: 'Success!',
                    text: 'Your request has been submitted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    // Optionally, you can submit the form after the alert
                    form.submit();
                });
            }
        });
    </script>
</body>
</html>

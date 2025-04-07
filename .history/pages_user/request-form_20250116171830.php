<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-6 w-full sm:w-96">
            <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Request Reschedule</h2>
            <form action="submit-request.php" method="POST">
                <!-- First Name, Middle Name and Last Name -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                    <div class="relative">
                        <input type="text" name="firstname" id="firstname" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="First Name" required>
                    </div>
                    <div class="relative">
                        <input type="text" name="middlename" id="middlename" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Middle Name" required>
                    </div>
                    <div class="relative">
                        <input type="text" name="lastname" id="lastname" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Last Name" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <input type="email" name="email" id="email" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Email" required>
                </div>

                <!-- Date Picker -->
                <div class="mb-6">
                    <label for="date" class="block text-sm text-gray-600 mb-2">Choose Date</label>
                    <input type="date" name="date" id="date" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                </div>

                <!-- Purpose Dropdown -->
                <div class="mb-6">
                    <label for="purpose" class="block text-sm text-gray-600 mb-2">Purpose (Documents)</label>
                    <select name="purpose" id="purpose" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                        <option value="brgy_clearance">Barangay Clearance</option>
                        <option value="brgy_indigency">Barangay Indigency</option>
                        <option value="brgy_permit">Barangay Permit</option>
                    </select>
                </div>

                <!-- Reason for Request -->
                <div class="mb-6">
                    <label for="reason" class="block text-sm text-gray-600 mb-2">Reason for Request</label>
                    <textarea name="reason" id="reason" rows="4" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">Submit Request</button>
                    <a href="profile.php" class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

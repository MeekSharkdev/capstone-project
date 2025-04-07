<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200">

    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 grid grid-cols-2 gap-8">

        <!-- Left Side: Form Fields -->
        <div>
            <h2 class="text-2xl font-semibold mb-6">Request Reschedule</h2>
            <form action="#" method="POST">
                <div class="mb-4">
                    <label for="firstname" class="block font-medium">First Name</label>
                    <input type="text" id="firstname" name="firstname" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="middlename" class="block font-medium">Middle Initial</label>
                    <input type="text" id="middlename" name="middlename" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="lastname" class="block font-medium">Last Name</label>
                    <input type="text" id="lastname" name="lastname" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="phonenumber" class="block font-medium">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium">Email Address</label>
                    <input type="email" id="email" name="email" readonly class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="request_date" class="block font-medium">Choose New Date</label>
                    <input type="date" id="request_date" name="request_date" required class="w-full px-4 py-2 mt-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="reason" class="block font-medium">Reason for Reschedule</label>
                    <textarea id="reason" name="reason" rows="4" required class="w-full px-4 py-2 mt-2 border rounded-lg"></textarea>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">Submit Request</button>
            </form>
        </div>

        <!-- Right Side: Booking Details -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Booking Details</h3>
            <div class="space-y-2">
                <p><strong>Booking Date:</strong></p>
                <p><strong>First Name:</strong></p>
                <p><strong>Last Name:</strong></p>
                <p><strong>Middle Initial:</strong></p>
                <p><strong>Phone Number:</strong></p>
                <p><strong>Email Address:</strong></p>
                <p><strong>Purpose:</strong></p>
            </div>
        </div>
    </div>
</body>

</html>
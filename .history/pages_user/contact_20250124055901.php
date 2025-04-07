<form action="contact_submit.php" method="POST" class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <!-- Instructions -->
    <div class="instructions text-center mb-6">
        <p class="text-lg text-gray-700">Please provide as much detail as possible about your concerns, feedback, or suggestions regarding our Appointment Scheduling System. Your input is valuable to us and will help us improve the user experience. Thank you!</p>
    </div>

    <!-- Full Name Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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
            <option value="payment">Payment Issue</option>
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

    <!-- File Attachment -->
    <div class="relative z-0 w-full group mb-6">
        <input type="file" id="file" name="file" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
        <label for="file" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Attach File (Optional)</label>
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

    <!-- Date of Submission -->
    <div class="relative z-0 w-full group mb-6">
        <input type="date" id="submission_date" name="submission_date" value="<?php echo date('Y-m-d'); ?>" readonly class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
        <label for="submission_date" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 left-0 origin-[0] peer-focus:text-blue-600 peer-focus:scale-75 peer-focus:-translate-y-6">Date of Submission</label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">Submit</button>
</form>
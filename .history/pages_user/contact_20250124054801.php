<form action="contact_submit.php" method="POST">
    <!-- Full Name Fields -->
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" required>

    <label for="middle_name">Middle Name (Optional)</label>
    <input type="text" id="middle_name" name="middle_name">

    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" required>

    <!-- Email Address -->
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" required>

    <!-- Phone Number -->
    <label for="phone">Phone Number (Optional)</label>
    <input type="text" id="phone" name="phone">

    <!-- Subject/Issue Type -->
    <label for="subject">Subject/Issue Type</label>
    <select id="subject" name="subject">
        <option value="appointment">Appointment Scheduling Issue</option>
        <option value="payment">Payment Issue</option>
        <option value="feedback">Feedback</option>
        <option value="technical">Technical Problem</option>
        <option value="other">Other</option>
    </select>

    <!-- Message/Description -->
    <label for="message">Message/Description</label>
    <textarea id="message" name="message" required></textarea>

    <!-- File Attachment -->
    <label for="file">Attach File (Optional)</label>
    <input type="file" id="file" name="file">

    <!-- Preferred Contact Method -->
    <label for="contact">Preferred Contact Method</label>
    <input type="radio" id="email_contact" name="contact_method" value="email" checked>
    <label for="email_contact">Email</label>
    <input type="radio" id="phone_contact" name="contact_method" value="phone">
    <label for="phone_contact">Phone</label>

    <!-- Date of Submission -->
    <label for="submission_date">Date of Submission</label>
    <input type="date" id="submission_date" name="submission_date" value="<?php echo date('Y-m-d'); ?>" readonly>

    <!-- Submit Button -->
    <button type="submit">Submit</button>
</form>
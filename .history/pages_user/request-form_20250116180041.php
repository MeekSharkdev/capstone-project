<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Reschedule Form</title>
    <link href="request.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm p-4">
            <h2 class="text-center mb-4">Requ   est Reschedule</h2>
            <form action="submit-request.php" method="POST">
                <div class="mb-4">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                
                <div class="mb-4">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>

                <div class="mb-4">
                    <label for="date" class="form-label">Choose Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <div class="mb-4">
                    <label for="purpose" class="form-label">Purpose (Documents)</label>
                    <select class="form-select" id="purpose" name="purpose" required>
                        <option value="brgy_clearance">Barangay Clearance</option>
                        <option value="brgy_indigency">Barangay Indigency</option>
                        <option value="brgy_permit">Barangay Permit</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="reason" class="form-label">Reason for Request</label>
                    <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

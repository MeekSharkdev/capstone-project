<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Schedule Records</title>
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/home.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #E5E7EB;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .table-responsive {
            margin-top: 30px;
            border-radius: 15px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .table th,
        .table td {
            text-align: center;
        }
    </style>
</head>

<body>

    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <div class="container">
            <h2 class="text-xl font-semibold text-center text-black">Appointment Records</h2>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="bookingTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Purpose</th>
                            <th>Booking Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bookingData"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-lg">
            <div class="bg-gray-700 rounded-lg shadow-2xl">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-white">Booking Details</h3>
                    <button id="closeModalBtn" class="text-white bg-transparent hover:bg-gray-200 hover:text-gray-500 rounded-lg text-sm w-8 h-8">
                        &times;
                    </button>
                </div>
                <div class="p-6 text-white">
                    <p><strong>Name:</strong> <span id="modalName"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                    <p><strong>Booking Date:</strong> <span id="modalDate"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("fetch_bookings.php")
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById("bookingData");
                    tableBody.innerHTML = data.map(row => `
                        <tr>
                            <td>${row.bookings_id}</td>
                            <td>${row.firstname}</td>
                            <td>${row.lastname}</td>
                            <td>${row.phonenumber}</td>
                            <td>${row.email}</td>
                            <td>${row.purpose}</td>
                            <td>${row.booking_date}</td>
                            <td>
                                <button class="btn btn-info view-btn" 
                                    data-id="${row.bookings_id}"
                                    data-firstname="${row.firstname}"
                                    data-lastname="${row.lastname}"
                                    data-email="${row.email}"
                                    data-purpose="${row.purpose}"
                                    data-date="${row.booking_date}">
                                    View
                                </button>
                            </td>
                        </tr>
                    `).join("");
                    
                    document.querySelectorAll(".view-btn").forEach(button => {
                        button.addEventListener("click", function () {
                            document.getElementById("modalName").textContent = this.dataset.firstname + " " + this.dataset.lastname;
                            document.getElementById("modalEmail").textContent = this.dataset.email;
                            document.getElementById("modalPurpose").textContent = this.dataset.purpose;
                            document.getElementById("modalDate").textContent = this.dataset.date;
                            document.getElementById("viewModal").classList.remove("hidden");
                        });
                    });
                });

            document.getElementById("closeModalBtn").addEventListener("click", function () {
                document.getElementById("viewModal").classList.add("hidden");
            });
        });
    </script>

</body>

</html>

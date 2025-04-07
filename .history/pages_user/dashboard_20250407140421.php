<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Favicon -->
    <link rel="icon" href="../images/brgy-fav.ico" type="image/x-icon">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link to external CSS -->
    <link href="../css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="bg-gray-200">
    <!-- Navigation Bar (Fixed Top) -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <?php
    // Include the database connection
    require_once '../config/db_connection.php';

    // Fetch events from the database
    $sql = "SELECT * FROM events ORDER BY event_date ASC";
    $result = $conn->query($sql);

    function timeAgo($datetime)
    {
        // Ensure the $datetime is being parsed correctly
        $timestamp = strtotime($datetime); // Convert string to timestamp
        $diff = time() - $timestamp; // Get the time difference in seconds

        if ($diff < 60) {
            return "A moment ago."; // Less than a minute
        }
        if ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . " min" . ($minutes > 1 ? "s" : "") . " ago"; // Less than an hour
        }
        if ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago"; // Less than a day
        }
        if ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . " day" . ($days > 1 ? "s" : "") . " ago"; // Less than a week
        }
        return floor($diff / 604800) . " week" . (floor($diff / 604800) > 1 ? "s" : "") . " ago"; // More than a week
    }



    // Function to calculate time left until the event starts
    function timeLeft($eventDate)
    {
        $eventTimestamp = strtotime($eventDate);
        $currentTime = time();
        $diff = $eventTimestamp - $currentTime;

        if ($diff <= 0) return "Event has already started";
        if ($diff < 3600) return floor($diff / 60) . " minutes left";
        if ($diff < 86400) return floor($diff / 3600) . " hours left";
        if ($diff < 604800) return floor($diff / 86400) . " days left";
        return floor($diff / 604800) . " weeks left";
    }
    ?>


    <!-- Welcome Section -->
    <div id="welcome-section"
        class="mt-32 mb-20 max-w-full mx-auto bg-gradient-to-r from-gray-700 to-white text-gray shadow-lg rounded-lg p-5 sm:p-10 md:p-20 flex flex-col sm:flex-row items-center gap-8 opacity-0 translate-y-10 transition-all duration-700 ease-out">

        <!-- Left Side: Barangay Icon (Image) -->
        <div class="flex justify-center mb-8 sm:mb-0 w-[80px] sm:w-[100px] md:w-[120px] lg:w-[150px]">
            <img src="../images/brgy-fav.ico" alt="Barangay Icon" class="w-full h-full object-cover rounded-full border-4 border-white shadow-md">
        </div>


        <!-- Right Side: Greeting and Description -->
        <div class="flex-1 text-center sm:text-left">
            <!-- Welcome Message with Typing Animation -->
            <h1 id="welcomeText" class="text-4xl sm:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-blue-300">
                <!-- Text will be typed dynamically -->
            </h1>
            <p class="text-xl sm:text-2xl font-semibold mt-2 text-gray-200">
                Hassle-Free Barangay Document Requests, Made Simple! 📄✅
            </p>
            <p class="mt-4 text-lg sm:text-xl text-gray-900 leading-relaxed">
                Say goodbye to long lines! Our online scheduling system allows you to book appointments for Barangay Clearance, Indigency, and Permit—fast, easy, and convenient. Get your documents with just a few clicks!
            </p>
        </div>
    </div>

    <!-- JavaScript for Animations -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let welcomeSection = document.getElementById("welcome-section");
            setTimeout(() => {
                welcomeSection.classList.remove("opacity-0", "translate-y-10");
            }, 200); // Small delay for effect
        });
    </script>

    <script>
        const kaBarangayText = "Ka-Barangay! 👋";

        // Replace this with the dynamically fetched username (from PHP, Node.js, etc.)
        const username = "<?php echo $_SESSION['username']; ?>"; // PHP example to get the logged-in user's username
        const capitalizedUsername = username.charAt(0).toUpperCase() + username.slice(1) + '!'; // Capitalize the first letter and add "!"

        const typingSpeed = 100; // Speed of typing (ms per character)
        const backspaceSpeed = 50; // Speed of backspace (ms per character)
        const loopInterval = 2000; // Interval time to restart the animation (2 seconds)

        let currentTextIndex = 0;
        let isKaBarangay = true;
        let textToType = kaBarangayText;

        function typeText() {
            document.getElementById("welcomeText").textContent = "Welcome, " + textToType.substring(0, currentTextIndex);

            if (currentTextIndex < textToType.length) {
                currentTextIndex++;
                setTimeout(typeText, typingSpeed);
            } else {
                // Wait before clearing text
                setTimeout(() => {
                    clearText();
                }, loopInterval);
            }
        }

        function clearText() {
            let currentText = document.getElementById("welcomeText").textContent;

            // Clear text one letter at a time (except for "Welcome")
            if (currentText.length > 8) { // Keep the "Welcome" part intact
                currentTextIndex--;
                document.getElementById("welcomeText").textContent = currentText.substring(0, currentText.length - 1);
                setTimeout(clearText, backspaceSpeed);
            } else {
                // Switch text when fully cleared (after "Welcome")
                isKaBarangay = !isKaBarangay;
                textToType = isKaBarangay ? kaBarangayText : capitalizedUsername; // Switch between "Ka-Barangay" and username
                currentTextIndex = 0;
                setTimeout(typeText, typingSpeed);
            }
        }

        // Start typing animation when the page loads
        window.onload = typeText;
    </script>




    <!-- Upcoming Events Section -->
    <div class="container mt-4 opacity-0 translate-y-10 transition-all duration-700 ease-out" id="upcoming-events">
        <h2 class="mb-9 text-center text-black text-4xl mt-32 font-bold">Upcoming Events</h2>

        <!-- Card events -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php
            $counter = 0;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col mb-4'>";
                    echo "<div class='card shadow-lg zoom-card' style='height: 100%;'>";

                    // Event Image
                    if (!empty($row['event_image'])) {
                        echo "<img src='../uploads/" . htmlspecialchars($row['event_image']) . "' class='card-img-top' alt='Event Image' style='height: 200px; object-fit: cover;'>";
                    } else {
                        echo "<div class='card-img-top bg-secondary text-white d-flex align-items-center justify-content-center' style='height: 200px;'>No Image</div>";
                    }

                    echo "<div class='card-body'>";
                    echo "<h1 class='card-title text-dark fw-semibold text-3xl mb-9'>" . htmlspecialchars($row['event_name']) . "</h1>";

                    $timeLeft = timeLeft($row['event_date']);
                    $timeClass = (strpos($timeLeft, 'minute') !== false || strpos($timeLeft, 'hour') !== false) ? 'text-warning' : 'text-success';
                    echo "<p class='$timeClass'>$timeLeft</p>";

                    echo "<p class='card-text text-muted fw-semibold' style='font-size: 1.1rem;'><span class='fw-normal'>Date:</span> " . htmlspecialchars($row['event_date']) . "</p>";
                    echo "<p class='card-text text-muted fw-semibold' style='font-size: 1.1rem;'><span class='fw-normal'>Time:</span> " . htmlspecialchars($row['event_time']) . "</p>";
                    echo "<p class='card-text text-dark fw-semibold' style='font-size: 1.1rem;'><span class='fw-normal'>Location:</span> " . htmlspecialchars($row['event_location']) . "</p>";
                    echo "<p class='card-text text-muted' style='font-size: 1.1rem;'>" . htmlspecialchars($row['event_description']) . "</p>";

                    if (!empty($row['created_at'])) {
                        echo "<p class='text-secondary small mt-8 mb-2'><span class='fw-normal'>Posted:</span> " . timeAgo($row['created_at']) . "</p>";
                    }

                    if (!empty($row['event_image'])) {
                        echo "<button class='btn btn-primary btn-block' data-bs-toggle='modal' data-bs-target='#eventImageModal" . $row['id'] . "'>View Event Image</button>";
                    }

                    echo "</div></div>";
                    echo "</div>";

                    // Modal for Event Image
                    if (!empty($row['event_image'])) {
                        echo "
                    <div class='modal fade' id='eventImageModal" . $row['id'] . "' tabindex='-1' aria-labelledby='eventImageModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered modal-lg'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='eventImageModalLabel'>" . htmlspecialchars($row['event_name']) . "</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body text-center'>
                                    <img src='../uploads/" . htmlspecialchars($row['event_image']) . "' class='img-fluid' alt='Event Image'>
                                    <p class='mt-3 text-secondary fw-bold'>" . timeLeft($row['event_date']) . "</p>
                                </div>
                            </div>
                        </div>
                    </div>";
                    }

                    $counter++;
                }
            } else {
                echo "<p class='text-muted text-center w-100'>No events available.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Add CSS for Zoom on Hover Effect -->
    <style>
        .zoom-card {
            transition: transform 0.3s ease-in-out;
        }

        .zoom-card:hover {
            transform: scale(1.05);
        }
    </style>

    <!-- JavaScript for Scroll Animation -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let upcomingEvents = document.getElementById("upcoming-events");

            let observer = new IntersectionObserver(
                function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            upcomingEvents.classList.remove("opacity-0", "translate-y-10");
                            observer.unobserve(upcomingEvents); // Stop observing after animation
                        }
                    });
                }, {
                    threshold: 0.2
                } // Trigger when 20% of section is visible
            );

            observer.observe(upcomingEvents);
        });
    </script>



    <!-- Past Event Section -->
    <section id="events" class="events-section text-center opacity-0 translate-y-10 transition-all duration-700 ease-out">
        <h2 class="text-4xl font-bold mb-6 mt-44">Past Events</h2>
        <p class="text-lg mb-9">Explore past events from Barangay Silangan!</p>

        <!-- Carousel for events (empty for now) -->
        <div id="eventCarousel" class="carousel slide mx-auto" data-bs-ride="carousel" data-bs-interval="2000" style="max-width: 80%;">
            <div class="carousel-inner">
                <!-- Placeholder for empty content -->
                <div class="carousel-item active">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">No Past Events Available</h5>
                            <p class="card-text">The past events will be archived here once available. Stay tuned!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Adjusted Image Size -->
    <style>
        #eventCarousel .carousel-item img {
            height: 250px !important;
            object-fit: cover;
        }

        #events h2 {
            font-size: 2.5rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>

    <!-- JavaScript for Scroll Animation -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let pastEventsSection = document.getElementById("events");

            let observer = new IntersectionObserver(
                function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            pastEventsSection.classList.remove("opacity-0", "translate-y-10");
                            observer.unobserve(pastEventsSection); // Stop observing after animation
                        }
                    });
                }, {
                    threshold: 0.2
                } // Trigger when 20% of section is visible
            );

            observer.observe(pastEventsSection);
        });
    </script>



    <style>
        .what-we-do h2 {
            font-size: 2.5rem;
            /* Equivalent to 4xl */
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .what-we-do p {
            text-align: center;
            font-size: 1.1rem;
            color: #555;
            padding: 0 20px;
        }

        .what-we-do .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .what-we-do .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .what-we-do .card-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>


    <!-- Include the styles in the <head> section -->

    <head>
        <style>
            /* Initially hide the section and prepare it for animation */
            .animate-up {
                opacity: 0;
                transform: translateY(50px);
                transition: opacity 1.5s ease, transform 1.5s ease;
            }

            /* When the section is visible */
            .visible {
                opacity: 1;
                transform: translateY(0);
            }

            /* For image hover effect */
            .img-fluid:hover {
                transform: scale(1.05);
                transition: transform 0.3s ease;
            }
        </style>
    </head>

    <!-- Booking Cards Sections -->
    <section id="services" class="what-we-do mt-5 animate-up px-4">
        <h2 class="text-center mb-16 mt-44">Our Services</h2>
        <p class="text-center mb-9">We provide essential services to our community, ensuring their well-being and development.</p>

        <!-- Card Section -->
        <div class="flex flex-wrap justify-center gap-6">
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4">
                <div class="text-center p-4 bg-white rounded-lg shadow-md">
                    <h5 class="text-xl font-semibold mb-4">Choose Your Certificate</h5>
                    <p class="text-gray-600 mb-6">Select the appropriate certificate and proceed to book your appointment.</p>

                    <!-- Images -->
                    <div class="flex flex-wrap justify-center gap-4 mb-6">
                        <img src="../images/bookingimg.png" class="img-fluid w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl transition-transform duration-300 hover:scale-105" alt="Certificate of Indigency">
                    </div>

                    <!-- Book Button -->
                    <a href="./booking_calendar.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 transition-colors duration-300">Book Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Include JavaScript for scroll detection -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select the section
            var section = document.querySelector('#services');

            // Check when section is in view
            function checkVisibility() {
                var sectionTop = section.getBoundingClientRect().top;
                var windowHeight = window.innerHeight;

                // If the section is within the viewport, add the 'visible' class
                if (sectionTop <= windowHeight * 0.8) { // 80% of the viewport height
                    section.classList.add('visible');
                }
            }

            // Call the checkVisibility function on scroll and on page load
            window.addEventListener('scroll', checkVisibility);
            checkVisibility(); // Check when the page loads
        });
    </script>



    <!-- About Us Section -->
    <section id="about-section" class="container my-12 mt-44 animate-up">
        <div class="hero-section text-center py-16 relative text-gray">
            <div class="absolute inset-0 bg-[url('../images/silangan.jpg')] bg-cover bg-center opacity-60 rounded-2xl"></div>
            <div class="relative z-10">
                <h1 class="text-5xl font-bold mb-4">About Us</h1>
                <p class="text-2xl max-w-4xl mx-auto relative z-20 bg-white rounded-full bg-opacity-50 px-1 py-1">
                    We are committed to serving our community with excellence and ensuring that every resident
                    has access to the necessary resources and services.
                </p>
            </div>
        </div>

        <!-- Vision and Mission Container -->
        <div class="vision-mission text-center mt-32">
            <div class="row align-items-center">
                <div class="col-md-6 px-6">
                    <img src="../images/topView.jpg" alt="Vision" class="img-fluid rounded-lg shadow-lg mb-6">
                    <h2 class="text-4xl font-extrabold text-gray-800 mb-3 tracking-wide">Vision</h2>
                    <p class="text-lg text-gray-600 leading-relaxed" style="text-align: justify;">
                        To be a progressive, inclusive, and resilient community where every resident thrives.
                        We envision Barangay Silangan as a model of good governance, environmental stewardship,
                        and active civic engagement, ensuring a high quality of life for present and future generations.
                    </p>
                </div>
                <div class="col-md-6 px-6">
                    <h2 class="text-4xl font-extrabold text-gray-800 mb-3 tracking-wide">Mission</h2>
                    <p class="text-lg text-gray-600 leading-relaxed" style="text-align: justify;">
                        To promote the well-being and safety of all residents through inclusive community programs,
                        proactive public services, and sustainable development initiatives. We are committed to fostering
                        a sense of unity, resilience, and active participation among our constituents.
                    </p>
                    <img src="../images/mission.jpg" alt="Mission" class="img-fluid rounded-lg shadow-lg mt-6">
                </div>
            </div>
        </div>
    </section>

    <!-- Include JavaScript for scroll detection -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select the sections to animate
            var aboutSection = document.querySelector('#about-section');

            // Check when the section is in view
            function checkVisibility() {
                var sectionTop = aboutSection.getBoundingClientRect().top;
                var windowHeight = window.innerHeight;

                // If the section is within the viewport, add the 'visible' class
                if (sectionTop <= windowHeight * 0.8) { // 80% of the viewport height
                    aboutSection.classList.add('visible');
                }
            }

            // Call the checkVisibility function on scroll and on page load
            window.addEventListener('scroll', checkVisibility);
            checkVisibility(); // Check when the page loads
        });
    </script>
    <!-- Include the styles in the <head> section -->

    <head>
        <style>
            /* Initially hide the section and prepare it for animation */
            .animate-left {
                opacity: 0;
                transform: translateX(-50px);
                /* Slide in from the left */
                transition: opacity 1.5s ease, transform 1.5s ease;
            }

            /* When the section is visible */
            .visible {
                opacity: 1;
                transform: translateX(0);
            }

            /* Hover effect for the team member images */
            .team-img:hover {
                transform: scale(1.05);
                transition: transform 0.3s ease;
            }
        </style>
    </head>

    <!-- Developer Team Description Container -->
    <div class="team-description shadow-2xl animate-left" style="margin-top: 200px; margin-bottom: 100px; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
        <h3 style="font-size: 2rem; color: #343a40; font-weight: 600; text-align: center;">Meet Our Development Team</h3>

        <!-- Team Member Section (2 Columns) -->
        <div class="team-member mt-12" style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-left: 50px; justify-items: center;">
            <!-- Team Member Image (left side) -->
            <div class="team-img-container" style="display: flex; justify-content: center;">
                <img src="../images/access.png" alt="Access Student" class="team-img rounded-circle" style="width: 300px; height: 250px; object-fit: cover; border: 3px solid #343a40;">
            </div>

            <!-- Team Member Description (right side) -->
            <div class="team-description-text" style="font-size: 1.1rem; color:rgb(89, 94, 99); line-height: 1.6; padding: 10px; display: flex; flex-direction: column; justify-content: space-between; text-align: justify; width: 500px;">
                <h4 style="font-size: 1.2rem; font-weight: 500; color:rgb(36, 38, 39);">Access Student</h4>
                <p style="margin-top: 10px; flex-grow: 1;">Our team consists of skilled developers and creative problem-solvers who work collaboratively to design innovative and effective solutions. Together, we strive to deliver seamless and user-friendly experiences. We work with the latest technologies to ensure our solutions are both scalable and efficient, always aiming for the best possible user experience.</p>
            </div>
        </div>
    </div>

    <!-- Include JavaScript for scroll detection -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select the sections to animate
            var teamSection = document.querySelector('.team-description');

            // Check when the section is in view
            function checkVisibility() {
                var sectionTop = teamSection.getBoundingClientRect().top;
                var windowHeight = window.innerHeight;

                // If the section is within the viewport, add the 'visible' class
                if (sectionTop <= windowHeight * 0.8) { // 80% of the viewport height
                    teamSection.classList.add('visible');
                }
            }

            // Call the checkVisibility function on scroll and on page load
            window.addEventListener('scroll', checkVisibility);
            checkVisibility(); // Check when the page loads
        });
    </script>



    <!-- Include the styles in the <head> section -->

    <head>
        <style>
            /* Initially hide the section and prepare it for animation */
            .animate-slide-up {
                opacity: 0;
                transform: translateY(50px);
                /* Slide up from the bottom */
                transition: opacity 1.5s ease, transform 1.5s ease;
            }

            /* When the section is visible */
            .visible {
                opacity: 1;
                transform: translateY(0);
            }

            .official img {
                border-radius: 50%;
                width: 120px;
                height: 120px;
                object-fit: cover;
                margin: 0 auto;
                /* Centers the image */
                display: block;
                /* Ensures that the image is treated as a block element for centering */
            }

            .official-name {
                font-weight: bold;
                /* Makes the name bold */
                font-size: 18px;
                /* Adjust the font size */
                margin-top: 10px;
                /* Adds space above the name */
                margin-bottom: 5px;
                /* Adds space below the name */
            }

            .official-position {
                font-size: 14px;
                /* Adjusts the font size */
                margin-bottom: 3px;
                /* Adds space between each position */
            }

            .official {
                text-align: center;
                padding: 10px;
                /* Adds padding to the container */
                margin-bottom: 20px;
                /* Adds margin at the bottom to separate from other items */
            }

            .row {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
    </head>

    <!-- Barangay Officials Section -->
    <section id="barangay-officials" class="container text-center py-4">
        <h1 class="mb-4 font-bold text-3xl mt-16">Barangay Officials</h1>

        <!-- Row for Punong Barangay -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/punongbarangay.png" alt="Punong Barangay">
                    <div class="official-name">SONNY "BONG" C. VILLARBA</div>
                    <div class="official-position">Punong Barangay</div>
                </div>
            </div>
        </div>

        <!-- Row for Kagawads -->
        <div class="row text-center mb-4">
            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad1.jpg" alt="Kagawad 1">
                    <div class="official-name">LUDIVINA "LUDY" M. ALVAREZ</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Senior Citizen</div>
                    <div class="official-position">Committee on Budget Appropriation</div>
                </div>
            </div>

            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad2.png" alt="Kagawad 2">
                    <div class="official-name">APRIL MAE ROSE R. DORADO</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Health and Sanitation</div>
                    <div class="official-position">Committee on Women and Family</div>
                </div>
            </div>

            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad3.png" alt="Kagawad 3">
                    <div class="official-name">ROMEO "ROMY" O. ABAY</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Public Works</div>
                    <div class="official-position">Committee on Livelihood and Cooperative</div>
                </div>
            </div>
        </div>

        <div class="row text-center mb-4">
            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad4.png" alt="Kagawad 4">
                    <div class="official-name">ADRIANNE "EGAY" B. ALVAREZ</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Social Service</div>
                    <div class="official-position">Committee on Disaster</div>
                </div>
            </div>

            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad5.png" alt="Kagawad 5">
                    <div class="official-name">REGINALDO "NALDO" R. RICO</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Transportation</div>
                    <div class="official-position">Committee on Inspection</div>
                </div>
            </div>

            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad6.jpg" alt="Kagawad 6">
                    <div class="official-name">PAUL EMMANUEL "POL" C. HUERTO</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Laws, Finance, and Audit</div>
                    <div class="official-position">Committee on Clean and Green</div>
                </div>
            </div>
        </div>

        <div class="row text-center mb-4">
            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/kagawad7.jpg" alt="Kagawad 7">
                    <div class="official-name">MARLON "ARIANNE" B. BULABOS</div>
                    <div class="official-position">Barangay Kagawad</div>
                    <div class="official-position">Committee on Gender and Devt., PWD, LGBTQ+, and Solo Parent</div>
                    <div class="official-position">Committee on Bids and Awards</div>
                </div>
            </div>
        </div>

        <!-- Row for Barangay Secretary -->
        <div class="row justify-content-center">
            <div class="col-md-4 official animate-slide-up">
                <div class="official-content">
                    <img src="../images/brgysecretary.jpg" alt="Barangay Secretary">
                    <div class="official-name">RAMONETTE S. CORDOVA</div>
                    <div class="official-position">Barangay Secretary</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CSS for Animations -->
    <style>
        /* Initial state for officials */
        .official {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.5s ease, transform 1.5s ease;
        }

        /* Make the official fade and slide up */
        .official.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <!-- JavaScript for Scroll Detection -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all the official elements
            const officials = document.querySelectorAll('.official');

            // Set up an IntersectionObserver to detect when the element is in view
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Add the 'visible' class to trigger the slide-up effect
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target); // Stop observing after it's in view
                    }
                });
            }, {
                threshold: 0.5
            }); // Trigger when 50% of the element is in view

            // Observe each official row
            officials.forEach(official => {
                observer.observe(official);
            });
        });
    </script>




    <footer class="footer">
        <div class="container">
            <div class="text-center my-3">
                <h5><strong>Contact Info</strong></h5>
                <p>
                    <strong>Address:</strong> 193 Ermin Garcia Street, Quezon City, Philippines &nbsp; | &nbsp;
                    <strong>Phone:</strong> 09655408489
                </p>
                <p>
                    <strong>Email:</strong> barangaysilangan64@gmail.com &nbsp; | &nbsp;
                    <strong>Facebook:</strong>
                    <a href="https://www.facebook.com/profile.php?id=100064541356099" target="_blank" class="footer-link">Barangay Silangan Facebook Page</a>
                </p>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2025 Barangay Silangan. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <style>
        .footer {
            background-color: #343a40;
            /* Dark background for a modern look */
            color: white;
            /* White text */
            padding: 40px 0;
            /* Padding for better spacing */
        }

        .footer h5 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .footer p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .footer-link {
            color: #17a2b8;
            /* Light blue color */
            text-decoration: none;
            /* Removes the underline */
            transition: color 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .footer-link:hover {
            color: #f8f9fa;
            /* Lighter color on hover */
        }

        hr.my-4 {
            border: 1px solid #f8f9fa;
            /* Light border for separation */
            width: 80%;
            /* Makes it narrower */
            margin: 20px auto;
            /* Centers it */
        }

        .footer p {
            margin-top: 10px;
            font-size: 0.9rem;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
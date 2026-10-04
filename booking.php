<?php

session_start();
include "db.php";

// Only normal website users can book. Admin login is separate.
if (!isset($_SESSION["user_id"])) {
    header("Location: register.php?redirect=booking.php");
    exit;
}

$message = "";
$messageType = "";

// Login user ki information
$loginName = $_SESSION["user_name"] ?? "";
$loginEmail = $_SESSION["user_email"] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $event_type = trim($_POST["event_type"]);
    $event_date = trim($_POST["event_date"]);
    $guests = (int) $_POST["guests"];
    $booking_message = trim($_POST["message"]);

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($event_type) ||
        empty($event_date) ||
        $guests <= 0
    ) {

        $message = "Please fill all required fields.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO bookings
            (name, email, phone, event_type, event_date, guests, message)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssssis",
            $name,
            $email,
            $phone,
            $event_type,
            $event_date,
            $guests,
            $booking_message
        );

        if ($stmt->execute()) {

            $message = "Your booking has been submitted successfully!";
            $messageType = "success";

            // Form clear
            $name = "";
            $email = "";
            $phone = "";
            $event_type = "";
            $event_date = "";
            $guests = "";
            $booking_message = "";
        } else {

            $message = "Booking failed. Please try again.";
            $messageType = "error";
        }

        $stmt->close();
    }
} else {

    // Login user ke liye automatic values
    $name = $loginName;
    $email = $loginEmail;
    $phone = "";
    $event_type = "";
    $event_date = "";
    $guests = "";
    $booking_message = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Book Your Event - Catering</title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        /* Header */

        header {
            background: #222;
            padding: 18px 6%;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            color: #f59a23;
            font-size: 28px;
            font-weight: bold;
            text-decoration: none;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s;
        }

        nav a:hover {
            color: #f59a23;
        }

        .book-btn {
            background: #f59a23;
            color: white !important;
            padding: 12px 20px;
            border-radius: 5px;
        }

        .book-btn:hover {
            background: #d97e0b;
        }


        /* Banner */

        .page-banner {
            min-height: 300px;

            background:
                linear-gradient(rgba(0, 0, 0, 0.65),
                    rgba(0, 0, 0, 0.65)),
                url("assets/images/booking.jpg") center/cover no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;
            padding: 30px;
        }

        .page-banner h1 {
            color: white;
            font-size: 50px;
            margin-bottom: 10px;
        }

        .page-banner p {
            color: #f59a23;
            font-size: 18px;
        }


        /* Booking Section */

        .booking-section {
            padding: 70px 6%;
        }

        .booking-container {
            max-width: 1100px;
            margin: auto;

            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 40px;

            align-items: start;
        }


        /* Info */

        .booking-info {
            background: #222;
            color: white;
            padding: 40px;
            border-radius: 8px;
        }

        .booking-info span {
            color: #f59a23;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .booking-info h2 {
            font-size: 35px;
            margin: 12px 0 18px;
        }

        .booking-info>p {
            color: #ccc;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            gap: 15px;
            margin-bottom: 22px;
        }

        .info-icon {
            min-width: 42px;
            height: 42px;

            background: #f59a23;
            color: white;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-item h4 {
            margin-bottom: 5px;
        }

        .info-item p {
            color: #bbb;
            font-size: 14px;
        }


        /* Form */

        .booking-form {
            background: white;
            padding: 40px;

            border-radius: 8px;

            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.07);
        }

        .form-title {
            margin-bottom: 25px;
        }

        .form-title h2 {
            color: #222;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .form-title p {
            color: #777;
            font-size: 14px;
        }


        /* Message */

        .message {
            padding: 13px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .message.success {
            background: #e8f7e8;
            color: #277227;
            border: 1px solid #b8dfb8;
        }

        .message.error {
            background: #ffecec;
            color: #c62828;
            border: 1px solid #f2b5b5;
        }


        /* Form */

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group label span {
            color: #f59a23;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            border: 1px solid #ddd;
            border-radius: 5px;

            padding: 13px 14px;

            font-family: Arial, sans-serif;
            font-size: 14px;

            outline: none;

            transition: 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #f59a23;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .form-button {
            width: 100%;

            border: none;

            background: #f59a23;
            color: white;

            padding: 14px;

            border-radius: 5px;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }

        .form-button:hover {
            background: #d97e0b;
        }


        /* Footer */

        footer {
            background: #181818;
            color: #aaa;
            text-align: center;
            padding: 25px 15px;
        }

        footer span {
            color: #f59a23;
        }


        /* Responsive */

        @media (max-width: 900px) {

            header {
                flex-direction: column;
                gap: 18px;
            }

            nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .booking-container {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            header {
                padding: 15px 20px;
            }

            .logo {
                font-size: 24px;
            }

            nav {
                gap: 10px;
            }

            nav a {
                font-size: 13px;
            }

            .book-btn {
                padding: 9px 13px;
            }

            .page-banner {
                min-height: 260px;
            }

            .page-banner h1 {
                font-size: 35px;
            }

            .booking-section {
                padding: 45px 15px;
            }

            .booking-info,
            .booking-form {
                padding: 25px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .booking-info h2 {
                font-size: 28px;
            }

        }



        /* ===== Responsive Header ===== */
        .site-menu-toggle {
            display: none;
            border: 0;
            background: transparent;
            color: #fff;
            width: 44px;
            height: 44px;
            padding: 8px;
            cursor: pointer;
            border-radius: 8px;
        }

        .site-menu-toggle span {
            display: block;
            height: 2px;
            margin: 5px 0;
            background: currentColor;
            border-radius: 2px;
            transition: transform .25s ease, opacity .25s ease;
        }

        .site-menu-toggle.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .site-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .site-menu-toggle.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        @media (max-width: 900px) {
            header {
                position: sticky !important;
                top: 0;
                padding: 14px 5% !important;
                min-height: 70px;
                flex-wrap: nowrap !important;
            }

            header .logo {
                font-size: 25px !important;
            }

            .site-menu-toggle {
                display: block;
                flex: 0 0 auto;
            }

            header .site-nav {
                display: none !important;
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                max-height: calc(100vh - 70px);
                overflow-y: auto;
                background: #222 !important;
                padding: 14px 5% 20px !important;
                flex-direction: column !important;
                align-items: stretch !important;
                justify-content: flex-start !important;
                gap: 0 !important;
                box-shadow: 0 12px 25px rgba(0, 0, 0, .18);
                flex-wrap: nowrap !important;
            }

            header .site-nav.is-open {
                display: flex !important;
            }

            header .site-nav a,
            header .site-nav .user-name {
                display: block;
                width: 100%;
                padding: 12px 4px;
                font-size: 15px;
            }

            header .site-nav .book-btn {
                width: 100%;
                text-align: center;
                margin-top: 6px;
                padding: 12px 18px;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 12px 4% !important;
            }

            header .logo {
                font-size: 22px !important;
            }
        }
    </style>

</head>


<body>


    <!-- Header -->

    <header>

        <a href="index.php" class="logo">
            CATERING
        </a>

        <button type="button" class="site-menu-toggle" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>

        <nav class="site-nav">

            <a href="index.php">Home</a>

            <a href="about.php">About</a>

            <a href="services.php">Services</a>

            <a href="menu.php">Menu</a>
            <a href="gallery.php">Gallery</a>

            <a href="contact.php">Contact</a>

            <a href="login.php">Login</a>

            <a href="booking.php" class="book-btn">
                Book Now
            </a>

        </nav>

    </header>


    <!-- Banner -->

    <section class="page-banner">

        <div>

            <h1>
                Book Your Event
            </h1>

            <p>
                Let us make your special occasion memorable
            </p>

        </div>

    </section>


    <!-- Booking -->

    <section class="booking-section">

        <div class="booking-container">


            <!-- Information -->

            <div class="booking-info">

                <span>
                    Let's Celebrate
                </span>

                <h2>
                    Plan Your Perfect Event
                </h2>

                <p>
                    Tell us about your event and our catering team
                    will take care of the food and service.
                </p>


                <div class="info-item">

                    <div class="info-icon">
                        📅
                    </div>

                    <div>

                        <h4>
                            Flexible Events
                        </h4>

                        <p>
                            Weddings, birthdays, corporate events
                            and more.
                        </p>

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-icon">
                        🍴
                    </div>

                    <div>

                        <h4>
                            Delicious Food
                        </h4>

                        <p>
                            Fresh and tasty food prepared for
                            your special event.
                        </p>

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-icon">
                        ⭐
                    </div>

                    <div>

                        <h4>
                            Professional Service
                        </h4>

                        <p>
                            Our team handles every detail with care.
                        </p>

                    </div>

                </div>

            </div>


            <!-- Form -->

            <div class="booking-form">

                <div class="form-title">

                    <h2>
                        Booking Form
                    </h2>

                    <p>
                        Fill in the details below to submit your booking.
                    </p>

                </div>


                <?php if (!empty($message)) { ?>

                    <div class="message <?php echo $messageType; ?>">

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php } ?>


                <form method="POST" action="">


                    <div class="form-row">


                        <!-- Name -->

                        <div class="form-group">

                            <label>
                                Name <span>*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                placeholder="Enter your name"
                                value="<?php echo htmlspecialchars($name ?? ''); ?>"
                                required>

                        </div>


                        <!-- Email -->

                        <div class="form-group">

                            <label>
                                Email <span>*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                placeholder="Enter your email"
                                value="<?php echo htmlspecialchars($email ?? ''); ?>"
                                required>

                        </div>


                    </div>


                    <div class="form-row">


                        <!-- Phone -->

                        <div class="form-group">

                            <label>
                                Phone <span>*</span>
                            </label>

                            <input
                                type="tel"
                                name="phone"
                                placeholder="Enter phone number"
                                value="<?php echo htmlspecialchars($phone ?? ''); ?>"
                                required>

                        </div>


                        <!-- Event -->

                        <div class="form-group">

                            <label>
                                Event Type <span>*</span>
                            </label>

                            <select
                                name="event_type"
                                required>

                                <option value="">
                                    Select Event
                                </option>

                                <option value="Wedding"
                                    <?php echo ($event_type ?? '') == 'Wedding' ? 'selected' : ''; ?>>
                                    Wedding
                                </option>

                                <option value="Birthday Party"
                                    <?php echo ($event_type ?? '') == 'Birthday Party' ? 'selected' : ''; ?>>
                                    Birthday Party
                                </option>

                                <option value="Corporate Event"
                                    <?php echo ($event_type ?? '') == 'Corporate Event' ? 'selected' : ''; ?>>
                                    Corporate Event
                                </option>

                                <option value="Engagement"
                                    <?php echo ($event_type ?? '') == 'Engagement' ? 'selected' : ''; ?>>
                                    Engagement
                                </option>

                                <option value="Anniversary"
                                    <?php echo ($event_type ?? '') == 'Anniversary' ? 'selected' : ''; ?>>
                                    Anniversary
                                </option>

                                <option value="Other"
                                    <?php echo ($event_type ?? '') == 'Other' ? 'selected' : ''; ?>>
                                    Other
                                </option>

                            </select>

                        </div>


                    </div>


                    <div class="form-row">


                        <!-- Date -->

                        <div class="form-group">

                            <label>
                                Event Date <span>*</span>
                            </label>

                            <input
                                type="date"
                                name="event_date"
                                min="<?php echo date('Y-m-d'); ?>"
                                value="<?php echo htmlspecialchars($event_date ?? ''); ?>"
                                required>

                        </div>


                        <!-- Guests -->

                        <div class="form-group">

                            <label>
                                Number of Guests <span>*</span>
                            </label>

                            <input
                                type="number"
                                name="guests"
                                min="1"
                                placeholder="e.g. 100"
                                value="<?php echo htmlspecialchars($guests ?? ''); ?>"
                                required>

                        </div>


                    </div>


                    <!-- Message -->

                    <div class="form-group">

                        <label>
                            Additional Message
                        </label>

                        <textarea
                            name="message"
                            placeholder="Tell us more about your event..."><?php echo htmlspecialchars($booking_message ?? ''); ?></textarea>

                    </div>


                    <button
                        type="submit"
                        class="form-button">
                        Submit Booking
                    </button>


                </form>

            </div>

        </div>

    </section>


    <!-- Footer -->

    <footer>

        <p>

            © <?php echo date("Y"); ?>

            <span>Catering</span>.

            All Rights Reserved.

        </p>

    </footer>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('header').forEach(function(header) {
                const nav = header.querySelector('nav');
                if (!nav || nav.classList.contains('site-nav')) {
                    if (nav) nav.classList.add('site-nav');
                }
                const currentNav = header.querySelector('.site-nav');
                if (!currentNav || header.querySelector('.site-menu-toggle')) return;
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'site-menu-toggle';
                button.setAttribute('aria-label', 'Open menu');
                button.setAttribute('aria-expanded', 'false');
                button.innerHTML = '<span></span><span></span><span></span>';
                currentNav.before(button);
                button.addEventListener('click', function() {
                    const open = currentNav.classList.toggle('is-open');
                    button.classList.toggle('active', open);
                    button.setAttribute('aria-expanded', open ? 'true' : 'false');
                    button.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                });
                currentNav.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        currentNav.classList.remove('is-open');
                        button.classList.remove('active');
                        button.setAttribute('aria-expanded', 'false');
                    });
                });
            });
        });
    </script>

</body>

</html>
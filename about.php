<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us - Catering</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            color: #333;
            overflow-x: hidden;
        }


        /* =========================
           HEADER
        ========================== */

        header {
            background: #222;
            padding: 18px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1000;
        }

        .logo {
            color: #f59a23;
            font-size: 28px;
            font-weight: bold;
            text-decoration: none;
            white-space: nowrap;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s ease;
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


        /* =========================
           ABOUT BANNER
        ========================== */

        .page-banner {
            min-height: 400px;

            background-image:
                linear-gradient(
                    rgba(0, 0, 0, 0.55),
                    rgba(0, 0, 0, 0.55)
                ),
                url("assets/images/about1.jpg");

            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;
            padding: 30px 20px;
        }

        .page-banner h1 {
            color: #fff;
            font-size: 55px;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .page-banner p {
            color: #f59a23;
            font-size: 18px;
            line-height: 1.6;
        }


        /* =========================
           ABOUT SECTION
        ========================== */

        .about-section {
            padding: 80px 6%;
            background: #fff;
        }

        .about-container {
            max-width: 1200px;
            margin: auto;

            display: grid;
            grid-template-columns: 1fr 1fr;

            gap: 60px;
            align-items: center;
        }

        .about-image {
            width: 100%;
            overflow: hidden;
            border-radius: 10px;
        }

        .about-image img {
            width: 100%;
            height: 450px;

            display: block;

            object-fit: cover;
            object-position: center;

            border-radius: 10px;

            transition: transform 0.4s ease;
        }

        .about-image:hover img {
            transform: scale(1.03);
        }

        .about-content .small-title {
            color: #f59a23;

            font-size: 15px;
            font-weight: bold;

            text-transform: uppercase;
            letter-spacing: 2px;

            margin-bottom: 12px;
        }

        .about-content h2 {
            font-size: 42px;
            color: #222;

            margin-bottom: 20px;
            line-height: 1.2;
        }

        .about-content p {
            color: #666;

            font-size: 16px;
            line-height: 1.8;

            margin-bottom: 18px;
        }

        .about-btn {
            display: inline-block;

            margin-top: 10px;

            background: #f59a23;
            color: #fff;

            text-decoration: none;

            padding: 13px 25px;

            border-radius: 5px;

            font-weight: bold;

            transition: 0.3s ease;
        }

        .about-btn:hover {
            background: #d97e0b;
            transform: translateY(-2px);
        }


        /* =========================
           FEATURES
        ========================== */

        .features {
            padding: 80px 6%;
            background: #f5f5f5;
        }

        .section-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-title span {
            color: #f59a23;

            font-size: 14px;
            font-weight: bold;

            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section-title h2 {
            font-size: 40px;
            color: #222;

            margin-top: 10px;
        }

        .feature-container {
            max-width: 1200px;
            margin: auto;

            display: grid;
            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }

        .feature-box {
            background: #fff;

            padding: 35px 25px;

            text-align: center;

            border-radius: 8px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.06);

            transition: 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-5px);

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.10);
        }

        .feature-icon {
            width: 65px;
            height: 65px;

            background: #f59a23;
            color: #fff;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 20px;

            font-size: 27px;
        }

        .feature-box h3 {
            color: #222;

            margin-bottom: 12px;

            font-size: 21px;
        }

        .feature-box p {
            color: #666;

            line-height: 1.7;

            font-size: 15px;
        }


        /* =========================
           CTA
        ========================== */

        .cta {
            padding: 75px 6%;

            background: #222;

            text-align: center;
        }

        .cta h2 {
            color: #fff;

            font-size: 40px;

            margin-bottom: 15px;
        }

        .cta p {
            color: #ccc;

            font-size: 17px;

            margin-bottom: 25px;
        }

        .cta a {
            display: inline-block;

            background: #f59a23;
            color: #fff;

            text-decoration: none;

            padding: 14px 28px;

            border-radius: 5px;

            font-weight: bold;

            transition: 0.3s ease;
        }

        .cta a:hover {
            background: #d97e0b;
            transform: translateY(-2px);
        }


        /* =========================
           FOOTER
        ========================== */

        footer {
            background: #181818;

            color: #aaa;

            text-align: center;

            padding: 25px 15px;
        }

        footer p {
            font-size: 14px;
        }

        footer span {
            color: #f59a23;
        }


        /* =========================
           MOBILE MENU
        ========================== */

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

            transition:
                transform .25s ease,
                opacity .25s ease;
        }

        .site-menu-toggle.active span:nth-child(1) {
            transform:
                translateY(7px)
                rotate(45deg);
        }

        .site-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .site-menu-toggle.active span:nth-child(3) {
            transform:
                translateY(-7px)
                rotate(-45deg);
        }


        /* =========================
           TABLET
        ========================== */

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

                max-height:
                    calc(100vh - 70px);

                overflow-y: auto;

                background: #222 !important;

                padding:
                    14px 5% 20px !important;

                flex-direction: column !important;

                align-items: stretch !important;

                justify-content: flex-start !important;

                gap: 0 !important;

                box-shadow:
                    0 12px 25px
                    rgba(0, 0, 0, .18);

                flex-wrap: nowrap !important;
            }

            header .site-nav.is-open {
                display: flex !important;
            }

            header .site-nav a {
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


            .page-banner {
                min-height: 400px;

                background-position: center center;
            }

            .page-banner h1 {
                font-size: 42px;
            }


            .about-container {
                grid-template-columns: 1fr;

                gap: 35px;
            }

            .about-image img {
                height: 380px;
            }


            .feature-container {
                grid-template-columns: 1fr;
            }

        }


        /* =========================
           MOBILE
        ========================== */

        @media (max-width: 600px) {

            header {
                padding: 12px 20px !important;
            }

            header .logo {
                font-size: 23px !important;
            }

            .page-banner {
                min-height: 320px;

                padding: 30px 20px;

                background-position: center center;
            }

            .page-banner h1 {
                font-size: 36px;
            }

            .page-banner p {
                font-size: 15px;
            }


            .about-section,
            .features {
                padding: 55px 20px;
            }

            .about-content h2 {
                font-size: 32px;
            }

            .about-content p {
                font-size: 15px;
            }

            .about-image img {
                height: 300px;
            }


            .section-title h2 {
                font-size: 30px;
            }


            .cta {
                padding: 55px 20px;
            }

            .cta h2 {
                font-size: 30px;
            }

        }


        /* =========================
           SMALL MOBILE
        ========================== */

        @media (max-width: 480px) {

            header {
                padding: 12px 4% !important;
            }

            header .logo {
                font-size: 22px !important;
            }

            .page-banner {
                min-height: 300px;
            }

            .page-banner h1 {
                font-size: 32px;
            }

            .page-banner p {
                font-size: 14px;
            }

            .about-image img {
                height: 260px;
            }

            .about-content h2 {
                font-size: 29px;
            }

            .about-btn {
                width: 100%;
                text-align: center;
            }

            .cta a {
                width: 100%;
                max-width: 280px;
            }

        }

    </style>
</head>


<body>


    <!-- =========================
         HEADER
    ========================== -->

    <header>

        <a href="index.php" class="logo">
            CATERING
        </a>


        <button
            type="button"
            class="site-menu-toggle"
            aria-label="Open menu"
            aria-expanded="false">

            <span></span>
            <span></span>
            <span></span>

        </button>


        <nav class="site-nav">

            <a href="index.php">Home</a>

            <a href="about.php">About</a>

            <a href="services.php">Services</a>

            <a href="menu.php">Menu</a>

            <a href="gallery.php">Gallery</a>

            <a href="contact.php">Contact</a>

            <a href="booking.php" class="book-btn">
                Book Now
            </a>

        </nav>

    </header>



    <!-- =========================
         ABOUT BANNER
    ========================== -->

    <section class="page-banner">

        <div>

            <h1>
                About Us
            </h1>

            <p>
                Creating memorable events with delicious food
            </p>

        </div>

    </section>



    <!-- =========================
         ABOUT SECTION
    ========================== -->

    <section class="about-section">

        <div class="about-container">


            <div class="about-image">

                <img
                    src="assets/images/a1.jpg"
                    alt="Catering Services"
                    onerror="this.onerror=null; this.src='assets/images/default.jpg';">

            </div>


            <div class="about-content">

                <div class="small-title">
                    About Our Catering
                </div>

                <h2>
                    Delicious Food & Unforgettable Events
                </h2>

                <p>
                    Welcome to our catering service. We are dedicated to
                    providing delicious food, professional service and
                    memorable experiences for every special occasion.
                </p>

                <p>
                    From weddings and birthday parties to corporate events
                    and family celebrations, our experienced team takes care
                    of every detail so you can enjoy your event with your
                    guests.
                </p>

                <p>
                    We believe that great food brings people together.
                    That's why we prepare every dish with fresh ingredients,
                    attention to detail and a passion for quality.
                </p>

                <a href="booking.php" class="about-btn">
                    Book Your Event
                </a>

            </div>

        </div>

    </section>



    <!-- =========================
         FEATURES
    ========================== -->

    <section class="features">

        <div class="section-title">

            <span>
                Why Choose Us
            </span>

            <h2>
                What Makes Us Special
            </h2>

        </div>


        <div class="feature-container">


            <div class="feature-box">

                <div class="feature-icon">
                    🍴
                </div>

                <h3>
                    Quality Food
                </h3>

                <p>
                    We prepare fresh and delicious food using quality
                    ingredients for every event.
                </p>

            </div>


            <div class="feature-box">

                <div class="feature-icon">
                    👨‍🍳
                </div>

                <h3>
                    Professional Team
                </h3>

                <p>
                    Our experienced catering team provides professional
                    service and takes care of every detail.
                </p>

            </div>


            <div class="feature-box">

                <div class="feature-icon">
                    ⭐
                </div>

                <h3>
                    Memorable Events
                </h3>

                <p>
                    We help make weddings, parties and corporate events
                    enjoyable and memorable for everyone.
                </p>

            </div>


        </div>

    </section>



    <!-- =========================
         CTA
    ========================== -->

    <section class="cta">

        <h2>
            Planning Your Next Event?
        </h2>

        <p>
            Let us make your special occasion delicious and unforgettable.
        </p>

        <a href="booking.php">
            Book Your Event
        </a>

    </section>



    <!-- =========================
         FOOTER
    ========================== -->

    <footer>

        <p>

            © <?php echo date("Y"); ?>

            <span>
                Catering
            </span>.

            All Rights Reserved.

        </p>

    </footer>



    <!-- =========================
         MOBILE MENU SCRIPT
    ========================== -->

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const header =
                    document.querySelector('header');

                if (!header) return;


                const nav =
                    header.querySelector('.site-nav');

                const button =
                    header.querySelector('.site-menu-toggle');


                if (!nav || !button) return;


                button.addEventListener(
                    'click',
                    function () {

                        const open =
                            nav.classList.toggle('is-open');

                        button.classList.toggle(
                            'active',
                            open
                        );

                        button.setAttribute(
                            'aria-expanded',
                            open ? 'true' : 'false'
                        );

                        button.setAttribute(
                            'aria-label',
                            open
                                ? 'Close menu'
                                : 'Open menu'
                        );

                    }
                );


                nav.querySelectorAll('a')
                    .forEach(function (link) {

                        link.addEventListener(
                            'click',
                            function () {

                                nav.classList.remove(
                                    'is-open'
                                );

                                button.classList.remove(
                                    'active'
                                );

                                button.setAttribute(
                                    'aria-expanded',
                                    'false'
                                );

                                button.setAttribute(
                                    'aria-label',
                                    'Open menu'
                                );

                            }
                        );

                    });

            }
        );

    </script>


</body>

</html>
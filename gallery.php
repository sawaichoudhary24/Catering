<?php

include "db.php";

$result = $conn->query("
    SELECT id, image, title, created_at
    FROM gallery
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gallery - Catering</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f7f7f7;
            color: #333;
        }


        /* =========================
           HEADER
        ========================= */

        header {
            background: #222;
            padding: 18px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #f59a23;
            font-size: 28px;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 15px;
        }

        nav a:hover {
            color: #f59a23;
        }

        .book-btn {
            background: #f59a23;
            padding: 10px 18px;
            border-radius: 5px;
        }


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            background-image: url("assets/images/gallery-bg.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            color: #fff;
            text-align: center;

            padding: 100px 20px;

            position: relative;
        }

        /* Dark Overlay */

        .page-header::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
        }

        .page-header h1,
        .page-header p {
            position: relative;
            z-index: 1;
        }

        .page-header h1 {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .page-header span {
            color: #f59a23;
        }

        .page-header p {
            color: #ddd;
            font-size: 16px;
        }


        /* =========================
           GALLERY SECTION
        ========================= */

        .gallery-section {
            padding: 80px 6%;
        }


        /* SECTION TITLE */

        .section-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-title h2 {
            font-size: 38px;
            margin-bottom: 12px;
        }

        .section-title span {
            color: #f59a23;
        }

        .section-title p {
            color: #777;
            font-size: 16px;
        }


        /* =========================
           GALLERY GRID
           3 IMAGES IN ONE ROW
        ========================= */

        .gallery-grid {
            max-width: 1200px;
            margin: auto;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }


        /* =========================
           GALLERY CARD
        ========================= */

        .gallery-card {
            background: #fff;

            border-radius: 10px;

            overflow: hidden;

            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);

            transition: 0.3s;
        }

        .gallery-card:hover {
            transform: translateY(-5px);

            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.13);
        }


        /* =========================
           GALLERY IMAGE
        ========================= */

        .gallery-image {
            width: 100%;
            height: 280px;

            overflow: hidden;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

            transition: 0.3s;
        }

        .gallery-card:hover .gallery-image img {
            transform: scale(1.07);
        }


        /* =========================
           GALLERY TITLE
        ========================= */

        .gallery-title {
            padding: 16px;

            text-align: center;

            font-size: 17px;

            font-weight: bold;

            color: #333;
        }


        /* =========================
           EMPTY
        ========================= */

        .empty {
            grid-column: 1 / -1;

            text-align: center;

            background: #fff;

            padding: 60px 20px;

            border-radius: 10px;

            color: #777;
        }

        .empty h3 {
            margin-bottom: 10px;
            color: #333;
        }


        /* =========================
           CTA
        ========================= */

        .cta {
            background: #222;

            color: #fff;

            text-align: center;

            padding: 70px 20px;
        }

        .cta h2 {
            font-size: 38px;

            margin-bottom: 12px;
        }

        .cta p {
            color: #ccc;

            margin-bottom: 25px;
        }

        .cta a {
            display: inline-block;

            background: #f59a23;

            color: #fff;

            text-decoration: none;

            padding: 13px 25px;

            border-radius: 5px;

            font-weight: bold;
        }

        .cta a:hover {
            background: #d97e0c;
        }


        /* =========================
           FOOTER
        ========================= */

        footer {
            background: #111;

            color: #aaa;

            text-align: center;

            padding: 25px;
        }


        /* =========================
           TABLET
        ========================= */

        @media (max-width: 1000px) {

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 650px) {

            header {
                flex-direction: column;

                gap: 15px;
            }

            nav {
                gap: 12px;

                flex-wrap: wrap;

                justify-content: center;
            }

            .page-header {
                padding: 80px 20px;
            }

            .page-header h1 {
                font-size: 36px;
            }

            .page-header p {
                font-size: 14px;
            }

            .gallery-section {
                padding: 50px 15px;
            }

            .gallery-grid {
                grid-template-columns: 1fr;

                gap: 20px;
            }

            .gallery-image {
                height: 250px;
            }

            .section-title h2 {
                font-size: 30px;
            }

            .cta h2 {
                font-size: 30px;
            }

        }


        /* =========================
           SMALL MOBILE
        ========================= */

        @media (max-width: 400px) {

            nav a {
                font-size: 14px;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .gallery-image {
                height: 220px;
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


    <!-- =========================
     HEADER
========================= -->

    <header>

        <div class="logo">
            CATERING
        </div>

        <button type="button" class="site-menu-toggle" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>

        <nav class="site-nav">

            <a href="index.php">
                Home
            </a>

            <a href="about.php">
                About
            </a>

            <a href="services.php">
                Services
            </a>

            <a href="menu.php">
                Menu
            </a>

            <a href="gallery.php">
                Gallery
            </a>

            <a href="contact.php">
                Contact
            </a>

            <a href="booking.php" class="book-btn">
                Book Now
            </a>

        </nav>

    </header>


    <!-- =========================
     PAGE HEADER
========================= -->

    <section class="page-header">

        <h1>
            Our <span>Gallery</span>
        </h1>

        <p>
            Explore our food, events and special moments.
        </p>

    </section>


    <!-- =========================
     GALLERY
========================= -->

    <section class="gallery-section">

        <div class="section-title">

            <h2>
                Event <span>Gallery</span>
            </h2>

            <p>
                A glimpse of our catering events and delicious food.
            </p>

        </div>


        <div class="gallery-grid">

            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="gallery-card">

                        <div class="gallery-image">

                            <img
                                src="uploads/<?php echo htmlspecialchars($row['image']); ?>"
                                alt="<?php echo htmlspecialchars($row['title'] ?? 'Gallery Image'); ?>">

                        </div>


                        <?php if (!empty($row['title'])): ?>

                            <div class="gallery-title">

                                <?php
                                echo htmlspecialchars($row['title']);
                                ?>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty">

                    <h3>
                        No Gallery Images Available
                    </h3>

                    <p>
                        Gallery images will be added soon.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =========================
     CTA
========================= -->

    <section class="cta">

        <h2>
            Make Your Event Special
        </h2>

        <p>
            Let us create delicious memories for your special occasion.
        </p>

        <a href="booking.php">
            Book Your Event
        </a>

    </section>


    <!-- =========================
     FOOTER
========================= -->

    <footer>

        © <?php echo date("Y"); ?> Catering. All Rights Reserved.

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
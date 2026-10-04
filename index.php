<?php

session_start();
include "db.php";


// ===============================
// GET SERVICES
// ===============================

$services = $conn->query("
    SELECT *
    FROM services
    ORDER BY id DESC
    LIMIT 6
");


// ===============================
// GET PRODUCTS
// ===============================

$products = $conn->query("
    SELECT *
    FROM products
    ORDER BY id DESC
    LIMIT 6
");


// ===============================
// GET GALLERY
// ===============================

$gallery = $conn->query("
    SELECT *
    FROM gallery
    ORDER BY id DESC
    LIMIT 6
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catering - Delicious Food & Events</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: #fff;
        }

        a {
            text-decoration: none;
        }


        /* =====================================
           HEADER
        ===================================== */

        header {
            width: 100%;
            background: #222;
            padding: 18px 6%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            position: sticky;
            top: 0;
            z-index: 999;
        }

        .logo {
            color: #f59a23;
            font-size: 30px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 22px;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            font-size: 15px;
            transition: 0.3s;
        }

        nav a:hover {
            color: #f59a23;
        }

        .user-name {
            color: #f59a23;
            font-weight: 600;
            font-size: 15px;
        }

        .book-btn {
            background: #f59a23;
            color: white !important;
            padding: 11px 18px;
            border-radius: 5px;
            font-weight: bold;
        }

        .book-btn:hover {
            background: #d97e0d;
        }


        /* =====================================
           HERO
        ===================================== */

        .hero {

            min-height: 650px;

            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.55),
                    rgba(0, 0, 0, 0.55)
                ),
                url("assets/images/index.jpg");

            background-size: cover;
            background-position: center;

            display: flex;

            align-items: center;

            justify-content: flex-start;

            text-align: left;

            padding: 80px 6%;
        }

        .hero-content {
            max-width: 700px;
            color: white;
        }

        .hero-content h1 {
            font-size: 60px;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 20px;
            line-height: 1.7;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            flex-wrap: wrap;
        }

        .hero-btn {
            display: inline-block;
            padding: 14px 28px;
            background: #f59a23;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        .hero-btn:hover {
            background: #d97e0d;
        }

        .hero-btn-outline {
            display: inline-block;
            padding: 13px 28px;
            border: 2px solid white;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        .hero-btn-outline:hover {
            background: white;
            color: #222;
        }


        /* =====================================
           COMMON SECTION
        ===================================== */

        .section {
            padding: 80px 6%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-title h2 {
            font-size: 40px;
            margin-bottom: 12px;
            color: #222;
        }

        .section-title p {
            color: #777;
            max-width: 650px;
            margin: auto;
            line-height: 1.7;
        }


        /* =====================================
           SERVICES
        ===================================== */

        .cards {
            max-width: 1200px;
            margin: auto;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }

        .card {
            background: white;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.08);

            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);

            box-shadow:
                0 8px 25px rgba(0,0,0,0.12);
        }

        .card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .card-content {
            padding: 22px;
        }

        .card-content h3 {
            color: #222;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .card-content p {
            color: #777;
            line-height: 1.7;
        }


        /* =====================================
           MENU
        ===================================== */

        .menu-section {
            background: #f7f7f7;
        }

        .menu-grid {
            max-width: 1200px;
            margin: auto;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }

        .food-card {
            background: white;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 3px 12px rgba(0,0,0,0.07);

            transition: 0.3s;
        }

        .food-card:hover {
            transform: translateY(-5px);

            box-shadow:
                0 8px 22px rgba(0,0,0,0.10);
        }

        .food-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .food-content {
            padding: 18px;
        }

        .food-content h3 {
            font-size: 19px;
            margin-bottom: 7px;
            color: #222;
        }

        .category {
            color: #f59a23;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .food-content p {
            color: #777;
            line-height: 1.5;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .price {
            color: #f59a23;
            font-size: 20px;
            font-weight: bold;
        }


        /* =====================================
           GALLERY
        ===================================== */

        .gallery-grid {
            max-width: 1200px;
            margin: auto;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }

        .gallery-item {
            height: 280px;

            overflow: hidden;

            border-radius: 10px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.08);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

            transition: 0.4s;
        }

        .gallery-item:hover img {
            transform: scale(1.08);
        }


        /* =====================================
           VIEW ALL BUTTON
        ===================================== */

        .view-all {
            text-align: center;
            margin-top: 35px;
        }

        .view-all a {
            display: inline-block;

            background: #f59a23;

            color: white;

            padding: 13px 28px;

            border-radius: 5px;

            font-weight: bold;

            transition: 0.3s;
        }

        .view-all a:hover {
            background: #d97e0d;

            transform: translateY(-2px);
        }


        /* =====================================
           CTA
        ===================================== */

        .cta {

            background:
                linear-gradient(
                    rgba(0,0,0,0.72),
                    rgba(0,0,0,0.72)
                ),
                url("assets/images/events.jpg");

            background-size: cover;

            background-position: center;

            text-align: center;

            color: white;

            padding: 100px 20px;
        }

        .cta h2 {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .cta p {
            max-width: 650px;

            margin:
                0 auto 30px;

            line-height: 1.7;
        }


        /* =====================================
           FOOTER
        ===================================== */

        footer {
            background: #222;

            color: white;

            padding:
                50px 6% 20px;
        }

       
       
        



        .copyright {
            border-top:
                1px solid #444;

            padding-top: 20px;

            text-align: center;

            color: #aaa;

            font-size: 14px;
        }


        /* =====================================
           TABLET
        ===================================== */

        @media (max-width: 1000px) {

            header {
                flex-direction: column;
                gap: 18px;
            }

            nav {
                justify-content: center;
                gap: 15px;
            }

            .hero-content h1 {
                font-size: 50px;
            }

            .cards,
            .menu-grid,
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (max-width: 650px) {

            header {
                padding: 15px 20px;
            }

            .logo {
                font-size: 26px;
            }

            nav {
                gap: 10px 14px;
                justify-content: center;
            }

            nav a,
            .user-name {
                font-size: 13px;
            }

            .hero {
                min-height: 550px;

                padding:
                    60px 20px;

                text-align: left;

                background-position:
                    center center;
            }

            .hero-content {
                max-width: 100%;
            }

            .hero-content h1 {
                font-size: 38px;
                line-height: 1.15;
            }

            .hero-content p {
                font-size: 16px;
                line-height: 1.6;
            }

            .hero-buttons {
                justify-content: flex-start;
            }

            .hero-btn,
            .hero-btn-outline {
                padding:
                    12px 20px;

                font-size: 14px;
            }

            .section {
                padding:
                    60px 20px;
            }

            .section-title h2 {
                font-size: 32px;
            }

            .cards,
            .menu-grid,
            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .gallery-item {
                height: 280px;
            }

            .cta h2 {
                font-size: 32px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
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
.site-menu-toggle.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.site-menu-toggle.active span:nth-child(2) { opacity: 0; }
.site-menu-toggle.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

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
        box-shadow: 0 12px 25px rgba(0,0,0,.18);
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
    header { padding: 12px 4% !important; }
    header .logo { font-size: 22px !important; }
}
</style>

</head>


<body>




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

        <a href="<?php echo isset($_SESSION["user_id"]) ? "menu.php" : "register.php?redirect=menu.php"; ?>">
            Menu
        </a>

      

        <a href="gallery.php">
            Gallery
        </a>

        <a href="contact.php">
            Contact
        </a>


        <?php if (isset($_SESSION["user_id"])): ?>

            <span class="user-name">

                Hi,
                <?php
                echo htmlspecialchars(
                    $_SESSION["user_name"]
                );
                ?>

            </span>

            <a href="logout.php">
                Logout
            </a>

        <?php else: ?>

            <a href="login.php">
                Login
            </a>

            <a href="register.php">
                Register
            </a>

        <?php endif; ?>


        <a
            href="<?php echo isset($_SESSION["user_id"]) ? "booking.php" : "register.php?redirect=booking.php"; ?>"
            class="book-btn"
        >
            Book Now
        </a>

    </nav>

</header>




<section class="hero">

    <div class="hero-content">

        <h1>
            Delicious Food,
            <br>
            Memorable Events
        </h1>

        <p>
            Professional catering services for weddings,
            birthdays, corporate events and every special
            celebration.
        </p>

        <div class="hero-buttons">

            <a
                href="<?php echo isset($_SESSION["user_id"]) ? "booking.php" : "register.php?redirect=booking.php"; ?>"
                class="hero-btn"
            >
                Book Your Event
            </a>

            <a
                href="menu.php"
                class="hero-btn-outline"
            >
                View Our Menu
            </a>

        </div>

    </div>

</section>




<section class="section">

    <div class="section-title">

        <h2>
            Our Services
        </h2>

        <p>
            We provide professional catering and event
            services to make your special occasions
            unforgettable.
        </p>

    </div>


    <div class="cards">

        <?php if ($services && $services->num_rows > 0): ?>

            <?php while ($service = $services->fetch_assoc()): ?>

                <?php

                $serviceImage =
                    "assets/images/default.jpg";

                if (!empty($service["image"])) {

                    $serviceImage =
                        "uploads/" .
                        $service["image"];
                }

                ?>

                <div class="card">

                    <img
                        src="<?php
                        echo htmlspecialchars(
                            $serviceImage
                        );
                        ?>"
                        alt="<?php
                        echo htmlspecialchars(
                            $service["name"]
                        );
                        ?>"
                        onerror="
                            this.src=
                            'assets/images/default.jpg';
                        "
                    >

                    <div class="card-content">

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $service["name"]
                            );
                            ?>
                        </h3>

                        <p>
                            <?php
                            echo htmlspecialchars(
                                $service["description"]
                            );
                            ?>
                        </p>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="card">

                <div class="card-content">

                    <h3>
                        Catering Services
                    </h3>

                    <p>
                        Quality food and professional
                        catering services for every occasion.
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>


    

    <div class="view-all">

        <a href="services.php">
            View All Services
        </a>

    </div>

</section>



<section class="section menu-section">

    <div class="section-title">

        <h2>
            Our Menu
        </h2>

        <p>
            Explore our delicious selection of food
            prepared with quality ingredients.
        </p>

    </div>


    <div class="menu-grid">

        <?php if ($products && $products->num_rows > 0): ?>

            <?php while ($product = $products->fetch_assoc()): ?>

                <?php

                $productImage =
                    "assets/images/default.jpg";

                if (!empty($product["image"])) {

                    $productImage =
                        "uploads/" .
                        $product["image"];
                }

                ?>

                <div class="food-card">

                    <img
                        src="<?php
                        echo htmlspecialchars(
                            $productImage
                        );
                        ?>"
                        alt="<?php
                        echo htmlspecialchars(
                            $product["name"]
                        );
                        ?>"
                        onerror="
                            this.src=
                            'assets/images/default.jpg';
                        "
                    >

                    <div class="food-content">

                        <?php if (!empty($product["category_name"])): ?>

                            <div class="category">

                                <?php
                                echo htmlspecialchars(
                                    $product["category_name"]
                                );
                                ?>

                            </div>

                        <?php endif; ?>


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $product["name"]
                            );
                            ?>

                        </h3>


                        <p>

                            <?php

                            $description =
                                $product["description"]
                                ?? "";

                            echo htmlspecialchars(

                                strlen($description) > 100

                                ? substr(
                                    $description,
                                    0,
                                    100
                                ) . "..."

                                : $description

                            );

                            ?>

                        </p>


                        <div class="price">

                            ₹<?php

                            echo number_format(

                                (float)
                                $product["price"],

                                2

                            );

                            ?>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="food-card">

                <div class="food-content">

                    <h3>
                        Menu Coming Soon
                    </h3>

                    <p>
                        Our delicious menu items
                        will be available soon.
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>


    <!-- VIEW FULL MENU -->

    <div class="view-all">

        <a href="<?php echo isset($_SESSION["user_id"]) ? "menu.php" : "register.php?redirect=menu.php"; ?>">
            View Full Menu
        </a>

    </div>

</section>




<section class="section">

    <div class="section-title">

        <h2>
            Our Gallery
        </h2>

        <p>
            Take a look at some of our memorable events
            and delicious food.
        </p>

    </div>


    <div class="gallery-grid">

        <?php if ($gallery && $gallery->num_rows > 0): ?>

            <?php while ($image = $gallery->fetch_assoc()): ?>

                <?php

                $galleryImage =
                    "assets/images/default.jpg";

                if (!empty($image["image"])) {

                    $galleryImage =
                        "uploads/" .
                        $image["image"];
                }

                ?>

                <div class="gallery-item">

                    <img
                        src="<?php
                        echo htmlspecialchars(
                            $galleryImage
                        );
                        ?>"
                        alt="<?php
                        echo htmlspecialchars(
                            $image["title"]
                            ?? "Gallery Image"
                        );
                        ?>"
                        onerror="
                            this.src=
                            'assets/images/default.jpg';
                        "
                    >

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="gallery-item">

                <img
                    src="assets/images/default.jpg"
                    alt="Gallery"
                >

            </div>

        <?php endif; ?>

    </div>


    <!-- VIEW ALL GALLERY -->

    <div class="view-all">

        <a href="gallery.php">
            View All Gallery
        </a>

    </div>

</section>




<footer>

    <div class="copyright">

        ©
        <?php echo date("Y"); ?>
        Catering.
        All Rights Reserved.

    </div>

</footer>



<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('header').forEach(function (header) {
        const nav = header.querySelector('.site-nav') || header.querySelector('nav');
        const button = header.querySelector('.site-menu-toggle');

        if (!nav || !button) return;

        nav.classList.add('site-nav');

        button.addEventListener('click', function () {
            const open = nav.classList.toggle('is-open');
            button.classList.toggle('active', open);
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
            button.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        });

        nav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                nav.classList.remove('is-open');
                button.classList.remove('active');
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-label', 'Open menu');
            });
        });
    });
});
</script>

</body>

</html>
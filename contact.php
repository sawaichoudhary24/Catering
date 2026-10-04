<?php

include "db.php";

$success = "";
$error = "";

if (isset($_POST['send_message'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if ($name == "" || $email == "" || $message == "") {

        $error = "Please fill all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO contact_messages
            (name, email, phone, message)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $phone,
            $message
        );

        if ($stmt->execute()) {

            $success = "Your message has been sent successfully.";

            $_POST = [];

        } else {

            $error = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Contact Us - Catering</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f5f6f8;
    color: #333;
}


/* HEADER */

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
}

nav a:hover {
    color: #f59a23;
}

.book-btn {
    background: #f59a23;
    padding: 10px 18px;
    border-radius: 5px;
}


/* PAGE HEADER */

.page-header {
    min-height: 400px;
    background:
        linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)),
        url("assets/images/contact.jpg") center center / cover no-repeat;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 70px 20px;
}

.page-header h1 {
    font-size: 45px;
    margin-bottom: 10px;
}

.page-header span {
    color: #f59a23;
}

.page-header p {
    color: #ccc;
}


/* CONTACT */

.contact-section {
    padding: 70px 6%;
}

.contact-container {
    max-width: 1050px;
    margin: auto;
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 30px;
}


/* INFO */

.contact-info {
    background: #222;
    color: #fff;
    padding: 40px;
    border-radius: 10px;
}

.contact-info h2 {
    font-size: 30px;
    margin-bottom: 15px;
}

.contact-info h2 span {
    color: #f59a23;
}

.contact-info > p {
    color: #ccc;
    line-height: 1.7;
    margin-bottom: 30px;
}

.info-item {
    margin-bottom: 25px;
}

.info-item h3 {
    color: #f59a23;
    margin-bottom: 6px;
}

.info-item p {
    color: #ddd;
    line-height: 1.5;
}


/* FORM */

.contact-form {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
}

.contact-form h2 {
    font-size: 30px;
    margin-bottom: 25px;
}

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
    font-weight: bold;
    margin-bottom: 7px;
}

.form-group label span {
    color: red;
}

input,
textarea {
    width: 100%;
    padding: 13px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    font-size: 15px;
}

input:focus,
textarea:focus {
    border-color: #f59a23;
}

textarea {
    height: 140px;
    resize: vertical;
}


/* BUTTON */

.submit-btn {
    width: 100%;
    border: none;
    background: #f59a23;
    color: #fff;
    padding: 14px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn:hover {
    background: #d97d0b;
}


/* ALERT */

.alert {
    padding: 13px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

.success {
    background: #dff5e4;
    color: #18733a;
}

.error {
    background: #ffe0e0;
    color: #b42318;
}


/* FOOTER */

footer {
    background: #111;
    color: #aaa;
    text-align: center;
    padding: 25px;
}


/* MOBILE */

@media (max-width: 750px) {

    header {
        flex-direction: column;
        gap: 15px;
    }

    nav {
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .contact-container {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .contact-section {
        padding: 40px 15px;
    }

    .contact-info,
    .contact-form {
        padding: 25px;
    }

    .page-header h1 {
        font-size: 35px;
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


<!-- HEADER -->

<header>

    <div class="logo">
        CATERING
    </div>

    <button type="button" class="site-menu-toggle" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>

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


<!-- PAGE HEADER -->

<section class="page-header">

    <h1>
        Contact <span>Us</span>
    </h1>

    <p>
        We would love to hear from you.
    </p>

</section>


<!-- CONTACT -->

<section class="contact-section">

    <div class="contact-container">


        <!-- CONTACT INFO -->

        <div class="contact-info">

            <h2>
                Get In <span>Touch</span>
            </h2>

            <p>
                Have a question about our catering services?
                Contact us and our team will be happy to help.
            </p>


            <div class="info-item">

                <h3>
                    Phone
                </h3>

                <p>
                    +91 98765 43210
                </p>

            </div>


            <div class="info-item">

                <h3>
                    Email
                </h3>

                <p>
                    info@catering.com
                </p>

            </div>


            <div class="info-item">

                <h3>
                    Address
                </h3>

                <p>
                    Rajasthan, India
                </p>

            </div>


            <div class="info-item">

                <h3>
                    Working Hours
                </h3>

                <p>
                    Monday - Sunday<br>
                    9:00 AM - 9:00 PM
                </p>

            </div>

        </div>


        <!-- CONTACT FORM -->

        <div class="contact-form">

            <h2>
                Send Us a Message
            </h2>


            <?php if ($success != ""): ?>

                <div class="alert success">
                    <?php echo htmlspecialchars($success); ?>
                </div>

            <?php endif; ?>


            <?php if ($error != ""): ?>

                <div class="alert error">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Name <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            placeholder="Your Name"
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Email <span>*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Your Email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Phone
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        placeholder="Your Phone Number"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Message <span>*</span>
                    </label>

                    <textarea
                        name="message"
                        placeholder="Write your message..."
                        required
                    ><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>

                </div>


                <button
                    type="submit"
                    name="send_message"
                    class="submit-btn"
                >
                    Send Message
                </button>

            </form>

        </div>

    </div>

</section>


<!-- FOOTER -->

<footer>

    © <?php echo date("Y"); ?> Catering. All Rights Reserved.

</footer>



<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('header').forEach(function (header) {
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
        button.addEventListener('click', function () {
            const open = currentNav.classList.toggle('is-open');
            button.classList.toggle('active', open);
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
            button.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        });
        currentNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
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
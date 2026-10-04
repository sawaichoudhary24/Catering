<?php

session_start();
include "db.php";

$message = "";
$messageType = "";
$redirect = isset($_GET["redirect"]) ? $_GET["redirect"] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validation
    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $message = "Please fill all required fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $messageType = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } else {

        // Check duplicate email
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $message = "Email is already registered.";
            $messageType = "error";

        } else {

            // Password hash
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Always create normal user
            $role = "user";

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, phone, address, role)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $name,
                $email,
                $hashedPassword,
                $phone,
                $address,
                $role
            );

            if ($stmt->execute()) {

                $message = "Registration successful! You can now login and continue.";
                $messageType = "success";

                // Clear form values
                $name = "";
                $email = "";
                $phone = "";
                $address = "";

            } else {

                $message = "Registration failed. Please try again.";
                $messageType = "error";
            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register - Catering</title>

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
            gap: 28px;
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
            padding: 12px 20px;
            border-radius: 5px;
            color: white !important;
        }

        .book-btn:hover {
            background: #d97e0b;
        }

        /* Register Section */

        .register-section {
            min-height: calc(100vh - 80px);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 60px 20px;
        }

        .register-box {
            width: 100%;
            max-width: 600px;

            background: white;

            padding: 40px;

            border-radius: 10px;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .register-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-title span {
            color: #f59a23;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .register-title h1 {
            color: #222;
            font-size: 34px;
            margin-top: 8px;
        }

        .register-title p {
            color: #777;
            margin-top: 8px;
            font-size: 14px;
        }

        /* Message */

        .message {
            padding: 13px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
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

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group label span {
            color: #f59a23;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 13px 14px;
            font-size: 14px;
            outline: none;
            font-family: Arial, sans-serif;
            transition: 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #f59a23;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .register-button {
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

        .register-button:hover {
            background: #d97e0b;
        }

        .login-text {
            text-align: center;
            margin-top: 22px;
            color: #666;
            font-size: 14px;
        }

        .login-text a {
            color: #f59a23;
            text-decoration: none;
            font-weight: bold;
        }

        .login-text a:hover {
            text-decoration: underline;
        }

        /* Footer */

        footer {
            background: #181818;
            color: #aaa;
            text-align: center;
            padding: 20px;
        }

        footer span {
            color: #f59a23;
        }

        /* Responsive */

        @media (max-width: 800px) {

            header {
                flex-direction: column;
                gap: 18px;
            }

            nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
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

            .register-section {
                padding: 35px 15px;
            }

            .register-box {
                padding: 25px 20px;
            }

            .register-title h1 {
                font-size: 28px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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


<!-- Header -->

<header>

    <a href="index.php" class="logo">
        CATERING
    </a>

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


<!-- Register -->

<section class="register-section">

    <div class="register-box">

        <div class="register-title">

            <span>
                Create Account
            </span>

            <h1>
                Register
            </h1>

            <p>
                Create your account to book catering services.
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
                        required
                    >

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
                        required
                    >

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
                        required
                    >

                </div>


                <!-- Password -->

                <div class="form-group">

                    <label>
                        Password <span>*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Minimum 6 characters"
                        required
                    >

                </div>

            </div>


            <!-- Confirm Password -->

            <div class="form-group">

                <label>
                    Confirm Password <span>*</span>
                </label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required
                >

            </div>


            <!-- Address -->

            <div class="form-group">

                <label>
                    Address
                </label>

                <textarea
                    name="address"
                    placeholder="Enter your address"
                ><?php echo htmlspecialchars($address ?? ''); ?></textarea>

            </div>


            <button
                type="submit"
                class="register-button"
            >
                Create Account
            </button>


        </form>


        <div class="login-text">

            Already have an account?

            <a href="login.php">
                Login here
            </a>

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
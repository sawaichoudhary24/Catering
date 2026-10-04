<?php

session_start();

include "db.php";

$error = "";


/* =========================
   USER LOGIN
========================= */

if (isset($_POST["login"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {

        $error = "Please enter email and password.";

    } else {

        /*
         * Website login sirf normal USER ke liye hai.
         * Admin yahan se login nahi kar sakta.
         */

        $stmt = $conn->prepare(
            "SELECT * FROM users
             WHERE email = ? AND role = 'user'
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();


            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_role"] = $user["role"];


                header("Location: index.php");
                exit;

            } else {

                $error = "Invalid email or password.";

            }

        } else {

            $error = "Invalid email or password.";

        }


        $stmt->close();
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

    <title>User Login - Catering</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }


        .login-container {
            width: 100%;
            max-width: 430px;
        }


        .login-box {
            background: white;
            padding: 40px;
            border-radius: 12px;

            box-shadow:
                0 5px 25px rgba(0,0,0,0.08);
        }


        .logo {
            text-align: center;
            color: #f59a23;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 8px;
        }


        .subtitle {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }


        .login-box h1 {
            text-align: center;
            font-size: 27px;
            margin-bottom: 25px;
            color: #222;
        }


        .form-group {
            margin-bottom: 18px;
        }


        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }


        .form-group input {
            width: 100%;
            padding: 13px 14px;

            border: 1px solid #ddd;
            border-radius: 6px;

            font-size: 14px;
            outline: none;
        }


        .form-group input:focus {
            border-color: #f59a23;
        }


        .login-btn {
            width: 100%;
            border: none;

            background: #f59a23;
            color: white;

            padding: 13px;
            border-radius: 6px;

            font-size: 15px;
            font-weight: bold;

            cursor: pointer;

            margin-top: 5px;
        }


        .login-btn:hover {
            background: #d98210;
        }


        .error {
            background: #f8d7da;
            color: #842029;

            padding: 12px 14px;
            border-radius: 6px;

            font-size: 14px;

            margin-bottom: 18px;
        }


        .register-text {
            text-align: center;
            margin-top: 22px;

            color: #777;
            font-size: 14px;
        }


        .register-text a {
            color: #f59a23;
            text-decoration: none;
            font-weight: bold;
        }


        .register-text a:hover {
            text-decoration: underline;
        }


        .back-home {
            display: block;
            text-align: center;

            margin-top: 18px;

            color: #555;
            text-decoration: none;

            font-size: 14px;
        }


        .back-home:hover {
            color: #f59a23;
        }


        @media (max-width: 480px) {

            .login-box {
                padding: 30px 22px;
            }


            .logo {
                font-size: 28px;
            }


            .login-box h1 {
                font-size: 24px;
            }

        }

    </style>

</head>


<body>


<div class="login-container">


    <div class="login-box">


        <div class="logo">
            CATERING
        </div>


        <div class="subtitle">
            User Account
        </div>


        <h1>
            Login
        </h1>


        <?php if (!empty($error)): ?>

            <div class="error">

                <?php
                echo htmlspecialchars($error);
                ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                name="login"
                class="login-btn"
            >
                Login
            </button>


        </form>


        <div class="register-text">

            Don't have an account?

            <a href="register.php">
                Register
            </a>

        </div>


        <a
            href="index.php"
            class="back-home"
        >
            ← Back to Home
        </a>


    </div>

</div>


</body>

</html>
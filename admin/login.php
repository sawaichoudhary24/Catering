<?php

session_start();

include "../db.php";

$error = "";


/* =========================
   ADMIN LOGIN
========================= */

if (isset($_POST["login"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {

        $error = "Please enter email and password.";

    } else {

        /*
         * Admin panel login sirf ADMIN ke liye hai.
         */

        $stmt = $conn->prepare(
            "SELECT * FROM users
             WHERE email = ? AND role = 'admin'
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows === 1) {

            $admin = $result->fetch_assoc();


            if (password_verify($password, $admin["password"])) {

                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_name"] = $admin["name"];
                $_SESSION["admin_email"] = $admin["email"];


                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Invalid admin email or password.";

            }

        } else {

            $error = "Admin account not found.";

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

    <title>Admin Login - Catering</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, sans-serif;

            background: #222;

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
                0 10px 35px rgba(0,0,0,0.25);
        }


        .logo {
            text-align: center;

            color: #f59a23;

            font-size: 32px;

            font-weight: bold;

            margin-bottom: 8px;
        }


        .admin-text {
            text-align: center;

            color: #777;

            font-size: 14px;

            margin-bottom: 30px;
        }


        .login-box h1 {
            text-align: center;

            color: #222;

            font-size: 27px;

            margin-bottom: 25px;
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


        .back-home {
            display: block;

            text-align: center;

            margin-top: 22px;

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


        <div class="admin-text">
            Admin Panel
        </div>


        <h1>
            Admin Login
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
                    Admin Email
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter admin email"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Admin Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter admin password"
                    required
                >

            </div>


            <button
                type="submit"
                name="login"
                class="login-btn"
            >
                Login to Admin Panel
            </button>


        </form>


        <a
            href="../index.php"
            class="back-home"
        >
            ← Back to Website
        </a>


    </div>

</div>


</body>

</html>
<?php

session_start();
include "../db.php";

// Admin protection
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Dashboard counts
$categories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];

$products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

$services = $conn->query("SELECT COUNT(*) AS total FROM services")->fetch_assoc()['total'];

$bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];

$users = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")->fetch_assoc()['total'];

$messages = $conn->query("SELECT COUNT(*) AS total FROM contact_messages")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Catering</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f6f8;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #222;
            color: white;
            padding: 25px 15px;
        }

        .logo {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 35px;
            color: #f59a23;
        }

        .admin-name {
            text-align: center;
            margin-bottom: 30px;
            color: #ddd;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #ddd;
            padding: 13px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background: #f59a23;
            color: white;
        }

        .logout {
            margin-top: 25px;
            background: #d9534f !important;
            color: white !important;
        }

        .main {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar h1 {
            color: #222;
        }

        .welcome {
            color: #777;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .card h3 {
            color: #777;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .card .number {
            font-size: 32px;
            font-weight: bold;
            color: #f59a23;
        }

        .recent {
            margin-top: 30px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .recent h2 {
            margin-bottom: 20px;
        }

        .empty {
            color: #777;
            padding: 20px 0;
        }

        @media (max-width: 900px) {

            .sidebar {
                width: 210px;
            }

            .main {
                margin-left: 210px;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="logo">
            CATERING
        </div>

        <div class="admin-name">
            Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
        </div>

        <div class="menu">

            <a href="dashboard.php" class="active">
                Dashboard
            </a>

            <a href="categories.php">
                Categories
            </a>

            <a href="products.php">
                Menu / Products
            </a>

            <a href="services.php">
                Services
            </a>

            <a href="gallery.php">
                Gallery
            </a>

            <a href="bookings.php">
                Bookings
            </a>

            <a href="messages.php">
                Messages
            </a>

            <a href="users.php">
                Users
            </a>

            <a href="logout.php" class="logout">
                Logout
            </a>

        </div>

    </aside>


    <!-- Main -->

    <main class="main">

        <div class="topbar">

            <div>

                <h1>Dashboard</h1>

                <p class="welcome">
                    Welcome to Catering Admin Panel
                </p>

            </div>

        </div>


        <!-- Cards -->

        <div class="cards">

            <div class="card">

                <h3>Total Categories</h3>

                <div class="number">
                    <?php echo $categories; ?>
                </div>

            </div>


            <div class="card">

                <h3>Total Menu Items</h3>

                <div class="number">
                    <?php echo $products; ?>
                </div>

            </div>


            <div class="card">

                <h3>Total Services</h3>

                <div class="number">
                    <?php echo $services; ?>
                </div>

            </div>


            <div class="card">

                <h3>Total Bookings</h3>

                <div class="number">
                    <?php echo $bookings; ?>
                </div>

            </div>


            <div class="card">

                <h3>Total Users</h3>

                <div class="number">
                    <?php echo $users; ?>
                </div>

            </div>


            <div class="card">

                <h3>Total Messages</h3>

                <div class="number">
                    <?php echo $messages; ?>
                </div>

            </div>

        </div>


        <!-- Recent -->

        <div class="recent">

            <h2>Recent Bookings</h2>

            <p class="empty">
                No bookings available yet.
            </p>

        </div>

    </main>

</body>

</html>
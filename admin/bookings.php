<?php

session_start();
include "../db.php";

/* =========================
   ADMIN CHECK
========================= */

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}


/* =========================
   UPDATE BOOKING STATUS
========================= */

if (isset($_POST["update_status"])) {

    $booking_id = intval($_POST["booking_id"]);
    $status = $_POST["status"];

    $allowed_status = ["pending", "confirmed", "cancelled"];

    if (in_array($status, $allowed_status)) {

        $stmt = $conn->prepare(
            "UPDATE bookings SET status = ? WHERE id = ?"
        );

        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: bookings.php");
    exit;
}


/* =========================
   DELETE BOOKING
========================= */

if (isset($_POST["delete_booking"])) {

    $booking_id = intval($_POST["booking_id"]);

    $stmt = $conn->prepare(
        "DELETE FROM bookings WHERE id = ?"
    );

    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    header("Location: bookings.php");
    exit;
}


/* =========================
   BOOKING COUNTS
========================= */

$totalBookings = 0;
$pendingBookings = 0;
$confirmedBookings = 0;
$cancelledBookings = 0;

$countResult = $conn->query("
    SELECT
        COUNT(*) AS total,
        SUM(status = 'pending') AS pending,
        SUM(status = 'confirmed') AS confirmed,
        SUM(status = 'cancelled') AS cancelled
    FROM bookings
");

if ($countResult) {

    $countData = $countResult->fetch_assoc();

    $totalBookings = $countData["total"] ?? 0;
    $pendingBookings = $countData["pending"] ?? 0;
    $confirmedBookings = $countData["confirmed"] ?? 0;
    $cancelledBookings = $countData["cancelled"] ?? 0;
}


/* =========================
   GET BOOKINGS
========================= */

$result = $conn->query("
    SELECT *
    FROM bookings
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bookings - Admin Panel</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #222;
            color: white;
            padding: 25px 18px;
            overflow-y: auto;
        }


        .logo {
            color: #f59a23;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }


        .welcome {
            text-align: center;
            color: #aaa;
            font-size: 14px;
            margin-bottom: 30px;
        }


        .sidebar-menu {
            list-style: none;
        }


        .sidebar-menu li {
            margin-bottom: 8px;
        }


        .sidebar-menu a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 13px 15px;
            border-radius: 6px;
            transition: 0.3s;
        }


        .sidebar-menu a:hover {
            background: #333;
            color: #f59a23;
        }


        .sidebar-menu a.active {
            background: #f59a23;
            color: white;
        }


        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 250px;
            padding: 30px;
        }


        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .topbar h1 {
            font-size: 28px;
        }


        .topbar p {
            color: #777;
            margin-top: 5px;
        }


        /* =========================
           STATS
        ========================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }


        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }


        .stat-card h3 {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
        }


        .number {
            font-size: 30px;
            font-weight: bold;
        }


        .orange {
            color: #f59a23;
        }


        .yellow {
            color: #e5a500;
        }


        .green {
            color: #198754;
        }


        .red {
            color: #dc3545;
        }


        /* =========================
           TABLE BOX
        ========================= */

        .table-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }


        .table-header {
            margin-bottom: 20px;
        }


        .table-header h2 {
            font-size: 22px;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow: hidden;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }


        th {
            background: #222;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 12px;
            word-break: break-word;
        }


        td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
            word-break: break-word;
            overflow-wrap: anywhere;
        }


        tr:hover {
            background: #fafafa;
        }


        .id {
            color: #f59a23;
            font-weight: bold;
        }


        /* =========================
           CUSTOMER
        ========================= */

        .customer-name {
            font-weight: bold;
            margin-bottom: 4px;
        }


        .customer-email {
            font-size: 11px;
            color: #777;
        }


        .phone {
            color: #555;
        }


        .event-type {
            font-weight: 600;
        }


        .message {
            color: #666;
            font-size: 12px;
            line-height: 1.4;
        }


        /* =========================
           STATUS
        ========================= */

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: capitalize;
        }


        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }


        .badge-confirmed {
            background: #d1e7dd;
            color: #0f5132;
        }


        .badge-cancelled {
            background: #f8d7da;
            color: #842029;
        }


        /* =========================
           STATUS FORM
        ========================= */

        .status-form {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }


        .status-select {
            width: 100%;
            padding: 7px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
            font-size: 12px;
        }


        .update-btn {
            width: 100%;
            background: #f59a23;
            border: none;
            color: white;
            padding: 7px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 11px;
        }


        .update-btn:hover {
            background: #d98210;
        }


        /* =========================
           DELETE
        ========================= */

        .delete-form {
            display: inline;
        }


        .delete-btn {
            background: #dc3545;
            border: none;
            color: white;
            padding: 7px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 11px;
        }


        .delete-btn:hover {
            background: #bb2d3b;
        }


        .no-bookings {
            text-align: center;
            padding: 50px 20px;
            color: #777;
        }


        /* =========================
           TABLET
        ========================= */

        @media (max-width: 1100px) {

            .sidebar {
                width: 210px;
            }


            .main {
                margin-left: 210px;
                padding: 20px;
            }


            .stats {
                grid-template-columns: repeat(2, 1fr);
            }


            th,
            td {
                padding: 9px 5px;
                font-size: 11px;
            }


            .customer-email {
                font-size: 10px;
            }

        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 20px 15px;
            }


            .main {
                margin-left: 0;
                padding: 15px;
            }


            .stats {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }


            .stat-card {
                padding: 15px;
            }


            .number {
                font-size: 24px;
            }


            .topbar {
                padding: 18px;
            }


            .topbar h1 {
                font-size: 23px;
            }


            .table-box {
                padding: 12px;
            }


            table {
                table-layout: fixed;
            }


            th,
            td {
                padding: 8px 4px;
                font-size: 10px;
            }


            .badge {
                padding: 4px 6px;
                font-size: 9px;
            }


            .status-select {
                padding: 5px;
                font-size: 10px;
            }


            .update-btn,
            .delete-btn {
                padding: 6px 5px;
                font-size: 9px;
            }

        }


        /* =========================
           SMALL MOBILE
        ========================= */

        @media (max-width: 450px) {

            .stats {
                grid-template-columns: 1fr;
            }


            .sidebar-menu a {
                padding: 10px 12px;
            }


            .table-box {
                padding: 8px;
            }


            th,
            td {
                padding: 6px 3px;
                font-size: 9px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<aside class="sidebar">

    <div class="logo">
        CATERING
    </div>


    <div class="welcome">

        Welcome,
        <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>

    </div>


    <ul class="sidebar-menu">

        <li>
            <a href="dashboard.php">
                Dashboard
            </a>
        </li>


        <li>
            <a href="categories.php">
                Categories
            </a>
        </li>


        <li>
            <a href="products.php">
                Menu / Products
            </a>
        </li>


        <li>
            <a href="services.php">
                Services
            </a>
        </li>


        <li>
            <a href="gallery.php">
                Gallery
            </a>
        </li>


        <li>
            <a href="bookings.php" class="active">
                Bookings
            </a>
        </li>


        <li>
            <a href="messages.php">
                Messages
            </a>
        </li>


        <li>
            <a href="users.php">
                Users
            </a>
        </li>


        <li>
            <a href="logout.php">
                Logout
            </a>
        </li>

    </ul>

</aside>


<!-- =========================
     MAIN
========================= -->

<main class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>

            <h1>Bookings</h1>

            <p>
                Manage customer event bookings
            </p>

        </div>

    </div>


    <!-- =========================
         STATS
    ========================= -->

    <div class="stats">


        <div class="stat-card">

            <h3>Total Bookings</h3>

            <div class="number orange">
                <?php echo $totalBookings; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>Pending</h3>

            <div class="number yellow">
                <?php echo $pendingBookings; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>Confirmed</h3>

            <div class="number green">
                <?php echo $confirmedBookings; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>Cancelled</h3>

            <div class="number red">
                <?php echo $cancelledBookings; ?>
            </div>

        </div>


    </div>


    <!-- =========================
         TABLE
    ========================= -->

    <div class="table-box">


        <div class="table-header">

            <h2>All Bookings</h2>

        </div>


        <div class="table-wrapper">


            <?php if ($result && $result->num_rows > 0): ?>


                <table>


                    <thead>

                        <tr>

                            <th style="width: 5%;">
                                ID
                            </th>

                            <th style="width: 17%;">
                                Customer
                            </th>

                            <th style="width: 13%;">
                                Phone
                            </th>

                            <th style="width: 13%;">
                                Event
                            </th>

                            <th style="width: 10%;">
                                Date
                            </th>

                            <th style="width: 7%;">
                                Guests
                            </th>

                            <th style="width: 10%;">
                                Status
                            </th>

                            <th style="width: 12%;">
                                Update
                            </th>

                            <th style="width: 8%;">
                                Delete
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php while ($row = $result->fetch_assoc()): ?>


                            <?php

                            $status = $row["status"] ?? "pending";

                            ?>


                            <tr>


                                <!-- ID -->

                                <td class="id">

                                    #<?php echo $row["id"]; ?>

                                </td>


                                <!-- CUSTOMER -->

                                <td>

                                    <div class="customer-name">

                                        <?php
                                        echo htmlspecialchars(
                                            $row["name"]
                                        );
                                        ?>

                                    </div>


                                    <div class="customer-email">

                                        <?php
                                        echo htmlspecialchars(
                                            $row["email"]
                                        );
                                        ?>

                                    </div>

                                </td>


                                <!-- PHONE -->

                                <td class="phone">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["phone"]
                                    );
                                    ?>

                                </td>


                                <!-- EVENT -->

                                <td class="event-type">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["event_type"]
                                    );
                                    ?>

                                </td>


                                <!-- DATE -->

                                <td>

                                    <?php

                                    if (!empty($row["event_date"])) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $row["event_date"]
                                            )
                                        );

                                    } else {

                                        echo "-";

                                    }

                                    ?>

                                </td>


                                <!-- GUESTS -->

                                <td>

                                    <?php
                                    echo (int)$row["guests"];
                                    ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="badge badge-<?php echo $status; ?>"
                                    >

                                        <?php
                                        echo ucfirst($status);
                                        ?>

                                    </span>

                                </td>


                                <!-- UPDATE -->

                                <td>

                                    <form
                                        method="POST"
                                        class="status-form"
                                    >


                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?php echo $row["id"]; ?>"
                                        >


                                        <select
                                            name="status"
                                            class="status-select"
                                        >

                                            <option
                                                value="pending"
                                                <?php
                                                echo ($status == "pending")
                                                    ? "selected"
                                                    : "";
                                                ?>
                                            >
                                                Pending
                                            </option>


                                            <option
                                                value="confirmed"
                                                <?php
                                                echo ($status == "confirmed")
                                                    ? "selected"
                                                    : "";
                                                ?>
                                            >
                                                Confirmed
                                            </option>


                                            <option
                                                value="cancelled"
                                                <?php
                                                echo ($status == "cancelled")
                                                    ? "selected"
                                                    : "";
                                                ?>
                                            >
                                                Cancelled
                                            </option>

                                        </select>


                                        <button
                                            type="submit"
                                            name="update_status"
                                            class="update-btn"
                                        >
                                            Update
                                        </button>


                                    </form>

                                </td>


                                <!-- DELETE -->

                                <td>

                                    <form
                                        method="POST"
                                        class="delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this booking?');"
                                    >


                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?php echo $row["id"]; ?>"
                                        >


                                        <button
                                            type="submit"
                                            name="delete_booking"
                                            class="delete-btn"
                                        >
                                            Delete
                                        </button>


                                    </form>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    </tbody>


                </table>


            <?php else: ?>


                <div class="no-bookings">

                    No bookings found.

                </div>


            <?php endif; ?>


        </div>

    </div>


</main>


</body>

</html>
<?php

session_start();
include "../db.php";

// Admin check
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Delete user
if (isset($_POST["delete_user"])) {

    $id = intval($_POST["delete_user"]);

    // Current logged-in admin ko delete nahi karna
    if ($id == $_SESSION["admin_id"]) {

        $message = "You cannot delete your own account.";

    } else {

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "User deleted successfully.";
        } else {
            $message = "Unable to delete user.";
        }

        $stmt->close();
    }
}

// Get users
$result = $conn->query("
    SELECT id, name, email, phone, address, role, created_at
    FROM users
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users - Catering Admin</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #333;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */

        .sidebar {
            width: 250px;
            background: #222;
            color: white;
            padding: 25px 15px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .logo {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #f59a23;
            margin-bottom: 10px;
        }

        .welcome {
            text-align: center;
            color: #ddd;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 6px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #f59a23;
            color: white;
        }

        /* Main */

        .main {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .topbar h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .topbar p {
            color: #777;
        }

        /* Message */

        .message {
            padding: 12px 15px;
            background: #fff3cd;
            color: #856404;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        /* Table */

        .table-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background: #222;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
            font-size: 14px;
        }

        tr:hover {
            background: #fafafa;
        }

        /* Role */

        .role {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .admin-role {
            background: #ffe0b2;
            color: #e65100;
        }

        .user-role {
            background: #dbeafe;
            color: #1d4ed8;
        }

        /* Delete */

        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }

        .delete-btn:hover {
            background: #b52a37;
        }

        .current-admin {
            color: #28a745;
            font-weight: bold;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        .address {
            max-width: 220px;
            word-break: break-word;
            color: #666;
        }

        /* Mobile */

        @media (max-width: 768px) {

            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
            }

            .logo {
                margin-bottom: 5px;
            }

            .welcome {
                margin-bottom: 15px;
            }

            .sidebar a {
                display: inline-block;
                padding: 9px 10px;
                margin: 3px;
                font-size: 13px;
            }

            .main {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .topbar h1 {
                font-size: 23px;
            }

            .table-box {
                padding: 10px;
            }

        }

    </style>

</head>

<body>

<div class="layout">

    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="logo">
            CATERING
        </div>

        <div class="welcome">
            Welcome,
            <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>
        </div>

        <a href="dashboard.php">
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

        <a href="users.php" class="active">
            Users
        </a>

        <a href="logout.php">
            Logout
        </a>

    </aside>


    <!-- Main -->

    <main class="main">

        <div class="topbar">

            <h1>Users</h1>

            <p>
                Manage registered users and administrators.
            </p>

        </div>


        <?php if ($message != ""): ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <div class="table-box">

            <?php if ($result && $result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Address</th>

                            <th>Role</th>

                            <th>Joined</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($row = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo $row["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["email"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["phone"] ?? ""); ?>
                            </td>

                            <td>

                                <div class="address">

                                    <?php
                                    echo !empty($row["address"])
                                        ? htmlspecialchars($row["address"])
                                        : "No address";
                                    ?>

                                </div>

                            </td>

                            <td>

                                <?php if ($row["role"] === "admin"): ?>

                                    <span class="role admin-role">
                                        ADMIN
                                    </span>

                                <?php else: ?>

                                    <span class="role user-role">
                                        USER
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime($row["created_at"])
                                );
                                ?>

                            </td>

                            <td>

                                <?php if ($row["id"] == $_SESSION["admin_id"]): ?>

                                    <span class="current-admin">
                                        Current Admin
                                    </span>

                                <?php else: ?>

                                    <form
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');"
                                    >

                                        <button
                                            type="submit"
                                            name="delete_user"
                                            value="<?php echo $row["id"]; ?>"
                                            class="delete-btn"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty">
                    No users found.
                </div>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>

</html>
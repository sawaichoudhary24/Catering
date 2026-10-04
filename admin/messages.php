<?php

session_start();
include "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Delete message
if (isset($_POST["delete_message"])) {

    $id = intval($_POST["delete_message"]);

    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Message deleted successfully.";
    }

    $stmt->close();
}

// Get messages
$result = $conn->query("
    SELECT *
    FROM contact_messages
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Messages - Catering Admin</title>

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

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 15px;
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
            min-width: 900px;
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

        .message-text {
            max-width: 350px;
            line-height: 1.5;
            color: #555;
            word-break: break-word;
        }

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

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        /* Mobile */

        @media (max-width: 768px) {

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
            }

            .layout {
                display: block;
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
            Welcome, <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>
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

        <a href="messages.php" class="active">
            Messages
        </a>

        <a href="users.php">
            Users
        </a>

        <a href="logout.php">
            Logout
        </a>

    </aside>


    <!-- Main Content -->

    <main class="main">

        <div class="topbar">

            <h1>Contact Messages</h1>

            <p>
                Manage messages received from your website contact form.
            </p>

        </div>


        <?php if ($message != ""): ?>

            <div class="success">
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
                            <th>Message</th>
                            <th>Date</th>
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
                                <?php echo htmlspecialchars($row["phone"]); ?>
                            </td>

                            <td>

                                <div class="message-text">
                                    <?php echo nl2br(htmlspecialchars($row["message"])); ?>
                                </div>

                            </td>

                            <td>
                                <?php echo date("d M Y", strtotime($row["created_at"])); ?>
                            </td>

                            <td>

                                <form method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this message?');">

                                    <button
                                        type="submit"
                                        name="delete_message"
                                        value="<?php echo $row["id"]; ?>"
                                        class="delete-btn">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty">
                    No contact messages found.
                </div>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>
</html>
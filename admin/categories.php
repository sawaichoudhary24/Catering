<?php

session_start();
include "../db.php";

// Admin protection
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

// ADD CATEGORY
if (isset($_POST['add_category'])) {

    $name = trim($_POST['name']);

    if ($name == "") {
        $error = "Please enter category name.";
    } else {

        $check = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Category already exists.";
        } else {

            $stmt = $conn->prepare(
                "INSERT INTO categories (name) VALUES (?)"
            );

            $stmt->bind_param("s", $name);

            if ($stmt->execute()) {
                $message = "Category added successfully.";
            } else {
                $error = "Something went wrong.";
            }

            $stmt->close();
        }

        $check->close();
    }
}


// DELETE CATEGORY
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Category deleted successfully.";
    } else {
        $error = "Unable to delete category.";
    }

    $stmt->close();
}


// GET CATEGORIES
$categories = $conn->query(
    "SELECT * FROM categories ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Categories | Catering Admin</title>

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


/* SIDEBAR */

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
    color: #f59a23;
    margin-bottom: 35px;
}

.admin-name {
    text-align: center;
    color: #ddd;
    margin-bottom: 30px;
}

.menu a {
    display: block;
    text-decoration: none;
    color: #ddd;
    padding: 13px 15px;
    margin-bottom: 5px;
    border-radius: 6px;
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


/* MAIN */

.main {
    margin-left: 250px;
    padding: 30px;
}

.topbar {
    margin-bottom: 25px;
}

.topbar h1 {
    color: #222;
}

.topbar p {
    color: #777;
    margin-top: 5px;
}


/* FORM */

.form-box {
    background: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

.form-box h2 {
    margin-bottom: 18px;
}

.form {
    display: flex;
    gap: 10px;
}

.form input {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    outline: none;
}

.form input:focus {
    border-color: #f59a23;
}

.form button {
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    background: #f59a23;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.form button:hover {
    background: #df8412;
}


/* MESSAGE */

.success {
    background: #dff6e5;
    color: #18733a;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.error {
    background: #ffe2e2;
    color: #c00000;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}


/* TABLE */

.table-box {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f59a23;
    color: white;
}

.delete {
    display: inline-block;
    padding: 7px 12px;
    background: #d9534f;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.delete:hover {
    background: #c9302c;
}


/* MOBILE */

@media (max-width: 700px) {

    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }

    .main {
        margin-left: 0;
        padding: 20px;
    }

    .form {
        flex-direction: column;
    }

    .form button {
        width: 100%;
    }

    .table-box {
        overflow-x: auto;
    }

}

</style>

</head>

<body>


<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="logo">
        CATERING
    </div>

    <div class="admin-name">
        Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
    </div>

    <div class="menu">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="categories.php" class="active">
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


<!-- MAIN -->

<main class="main">

    <div class="topbar">

        <h1>Categories</h1>

        <p>
            Manage your catering food categories
        </p>

    </div>


    <?php if ($message != ""): ?>

        <div class="success">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <?php if ($error != ""): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <!-- ADD CATEGORY -->

    <div class="form-box">

        <h2>Add New Category</h2>

        <form method="POST" class="form">

            <input
                type="text"
                name="name"
                placeholder="Enter category name"
                required
            >

            <button type="submit" name="add_category">
                Add Category
            </button>

        </form>

    </div>


    <!-- CATEGORY TABLE -->

    <div class="table-box">

        <h2>All Categories</h2>

        <br>

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

            <?php if ($categories->num_rows > 0): ?>

                <?php while ($row = $categories->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $row['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td>

                            <a
                                class="delete"
                                href="categories.php?delete=<?php echo $row['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this category?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="3">
                        No categories found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>

</html>
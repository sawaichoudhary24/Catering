<?php

session_start();
include "../db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

/* ADD SERVICE */

if (isset($_POST['add_service'])) {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name == "" || $description == "") {

        $error = "Please fill all fields.";

    } else {

        $imageName = "";

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            $uploadDir = "../uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalName = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];

            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );

            $allowed = ["jpg", "jpeg", "png", "webp"];

            if (!in_array($extension, $allowed)) {

                $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

            } else {

                $imageName = time() . "_" . uniqid() . "." . $extension;

                move_uploaded_file(
                    $tmpName,
                    $uploadDir . $imageName
                );
            }
        }

        if ($error == "") {

            $stmt = $conn->prepare(
                "INSERT INTO services (name, description, image)
                 VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $description,
                $imageName
            );

            if ($stmt->execute()) {
                $message = "Service added successfully.";
            } else {
                $error = "Unable to add service.";
            }

            $stmt->close();
        }
    }
}


/* DELETE SERVICE */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
        "SELECT image FROM services WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $service = $result->fetch_assoc();

        if (!empty($service['image'])) {

            $imagePath = "../uploads/" . $service['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    $stmt->close();


    $stmt = $conn->prepare(
        "DELETE FROM services WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Service deleted successfully.";
    } else {
        $error = "Unable to delete service.";
    }

    $stmt->close();
}


/* GET SERVICES */

$services = $conn->query(
    "SELECT * FROM services ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Services | Catering Admin</title>

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


/* MESSAGES */

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


/* FORM */

.form-box {
    background: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

.form-box h2 {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 7px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    outline: none;
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #f59a23;
}

.submit-btn {
    padding: 13px 25px;
    border: none;
    border-radius: 6px;
    background: #f59a23;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.submit-btn:hover {
    background: #df8412;
}


/* SERVICE LIST */

.table-box {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 13px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f59a23;
    color: white;
}

.service-image {
    width: 90px;
    height: 65px;
    object-fit: cover;
    border-radius: 6px;
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
        Welcome,
        <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
    </div>

    <div class="menu">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="categories.php">
            Categories
        </a>

        <a href="products.php">
            Menu / Products
        </a>

        <a href="services.php" class="active">
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

        <h1>Services</h1>

        <p>
            Manage catering services
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


    <!-- ADD SERVICE -->

    <div class="form-box">

        <h2>Add New Service</h2>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">

                <label>Service Name</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Example: Wedding Catering"
                    required
                >

            </div>


            <div class="form-group">

                <label>Description</label>

                <textarea
                    name="description"
                    placeholder="Enter service description..."
                    required
                ></textarea>

            </div>


            <div class="form-group">

                <label>Service Image</label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                >

            </div>


            <button
                type="submit"
                name="add_service"
                class="submit-btn"
            >
                Add Service
            </button>

        </form>

    </div>


    <!-- SERVICES TABLE -->

    <div class="table-box">

        <h2>All Services</h2>

        <br>

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

            <?php if ($services->num_rows > 0): ?>

                <?php while ($service = $services->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $service['id']; ?>
                        </td>

                        <td>

                            <?php if (!empty($service['image'])): ?>

                                <img
                                    src="../uploads/<?php echo htmlspecialchars($service['image']); ?>"
                                    class="service-image"
                                    alt="Service"
                                >

                            <?php else: ?>

                                No Image

                            <?php endif; ?>

                        </td>

                        <td>
                            <?php echo htmlspecialchars($service['name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($service['description']); ?>
                        </td>

                        <td>

                            <a
                                href="services.php?delete=<?php echo $service['id']; ?>"
                                class="delete"
                                onclick="return confirm('Are you sure you want to delete this service?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="5">
                        No services found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>

</html>
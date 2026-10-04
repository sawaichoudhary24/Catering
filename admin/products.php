<?php

session_start();
include "../db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

/* ADD PRODUCT */

if (isset($_POST['add_product'])) {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $category_name = trim($_POST['category_name']);

    if ($name == "" || $description == "" || $price == "" || $stock == "" || $category_name == "") {

        $error = "Please fill all fields.";

    } else {

        $imageName = "";

        /* IMAGE UPLOAD */

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
                "INSERT INTO products
                (name, description, price, stock, image, category_name)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssdiss",
                $name,
                $description,
                $price,
                $stock,
                $imageName,
                $category_name
            );

            if ($stmt->execute()) {

                $message = "Product added successfully.";

            } else {

                $error = "Unable to add product.";
            }

            $stmt->close();
        }
    }
}


/* DELETE PRODUCT */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
        "SELECT image FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $product = $result->fetch_assoc();

        if (!empty($product['image'])) {

            $imagePath = "../uploads/" . $product['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    $stmt->close();


    $stmt = $conn->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $message = "Product deleted successfully.";

    } else {

        $error = "Unable to delete product.";
    }

    $stmt->close();
}


/* GET CATEGORIES */

$categories = $conn->query(
    "SELECT * FROM categories ORDER BY name ASC"
);


/* GET PRODUCTS */

$products = $conn->query(
    "SELECT * FROM products ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Products | Catering Admin</title>

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

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 7px;
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    outline: none;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #f59a23;
}

.full {
    grid-column: span 2;
}

.submit-btn {
    margin-top: 18px;
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


/* TABLE */

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
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f59a23;
    color: white;
}

.product-image {
    width: 70px;
    height: 55px;
    object-fit: cover;
    border-radius: 6px;
}

.no-image {
    color: #999;
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

@media (max-width: 800px) {

    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }

    .main {
        margin-left: 0;
        padding: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .full {
        grid-column: span 1;
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

        <a href="products.php" class="active">
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

        <h1>Menu / Products</h1>

        <p>
            Add and manage your catering food items
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


    <!-- ADD PRODUCT -->

    <div class="form-box">

        <h2>Add New Food Item</h2>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-grid">

                <div class="form-group">

                    <label>Food Name</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Example: Paneer Tikka"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Category</label>

                    <select name="category_name" required>

                        <option value="">
                            Select Category
                        </option>

                        <?php while ($category = $categories->fetch_assoc()): ?>

                            <option value="<?php echo htmlspecialchars($category['name']); ?>">

                                <?php echo htmlspecialchars($category['name']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>Price</label>

                    <input
                        type="number"
                        name="price"
                        step="0.01"
                        min="0"
                        placeholder="Example: 250"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Stock</label>

                    <input
                        type="number"
                        name="stock"
                        min="0"
                        placeholder="Example: 50"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Enter food description..."
                        required
                    ></textarea>

                </div>


                <div class="form-group full">

                    <label>Food Image</label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                </div>

            </div>


            <button
                type="submit"
                name="add_product"
                class="submit-btn"
            >
                Add Food Item
            </button>

        </form>

    </div>


    <!-- PRODUCT LIST -->

    <div class="table-box">

        <h2>All Food Items</h2>

        <br>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Image</th>

                    <th>Name</th>

                    <th>Category</th>

                    <th>Price</th>

                    <th>Stock</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

            <?php if ($products->num_rows > 0): ?>

                <?php while ($product = $products->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $product['id']; ?>
                        </td>


                        <td>

                            <?php if (!empty($product['image'])): ?>

                                <img
                                    src="../uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                    class="product-image"
                                    alt="Food"
                                >

                            <?php else: ?>

                                <span class="no-image">
                                    No Image
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </td>


                        <td>
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </td>


                        <td>
                            ₹<?php echo number_format($product['price'], 2); ?>
                        </td>


                        <td>
                            <?php echo $product['stock']; ?>
                        </td>


                        <td>

                            <a
                                href="products.php?delete=<?php echo $product['id']; ?>"
                                class="delete"
                                onclick="return confirm('Are you sure you want to delete this food item?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7">
                        No food items found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>

</html>
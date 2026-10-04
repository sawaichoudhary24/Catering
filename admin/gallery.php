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
   DELETE IMAGE
========================= */

if (isset($_POST["delete_gallery"])) {

    $id = intval($_POST["gallery_id"]);

    $stmt = $conn->prepare(
        "SELECT image FROM gallery WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();


    if ($row) {

        $imagePath = "../uploads/" . $row["image"];

        if (!empty($row["image"]) && file_exists($imagePath)) {
            unlink($imagePath);
        }


        $delete = $conn->prepare(
            "DELETE FROM gallery WHERE id = ?"
        );

        $delete->bind_param("i", $id);
        $delete->execute();
        $delete->close();
    }


    header("Location: gallery.php");
    exit;
}


/* =========================
   UPLOAD IMAGE
========================= */

if (isset($_POST["upload_gallery"])) {

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] === 0
    ) {

        $fileName = $_FILES["image"]["name"];
        $tmpName = $_FILES["image"]["tmp_name"];
        $fileSize = $_FILES["image"]["size"];

        $extension = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );


        $allowedExtensions = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];


        if (!in_array($extension, $allowedExtensions)) {

            $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

        } elseif ($fileSize > 5 * 1024 * 1024) {

            $error = "Image size must be less than 5MB.";

        } else {

            $newFileName =
                time() . "_" .
                uniqid() . "." .
                $extension;


            $uploadPath =
                "../uploads/" . $newFileName;


            if (move_uploaded_file($tmpName, $uploadPath)) {

                $stmt = $conn->prepare(
                    "INSERT INTO gallery (image) VALUES (?)"
                );

                $stmt->bind_param(
                    "s",
                    $newFileName
                );

                $stmt->execute();

                $stmt->close();


                header("Location: gallery.php");
                exit;

            } else {

                $error = "Image upload failed.";

            }
        }

    } else {

        $error = "Please select an image.";

    }
}


/* =========================
   GET GALLERY
========================= */

$result = $conn->query(
    "SELECT * FROM gallery ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Gallery - Admin Panel</title>


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
        }


        .topbar h1 {
            font-size: 28px;
        }


        .topbar p {
            color: #777;
            margin-top: 5px;
        }


        /* =========================
           UPLOAD BOX
        ========================= */

        .upload-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }


        .upload-box h2 {
            font-size: 21px;
            margin-bottom: 18px;
        }


        .upload-form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }


        .file-input {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            background: #fff;
        }


        .upload-btn {
            background: #f59a23;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }


        .upload-btn:hover {
            background: #d98210;
        }


        /* =========================
           MESSAGE
        ========================= */

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }


        /* =========================
           GALLERY
        ========================= */

        .gallery-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }


        .gallery-box h2 {
            font-size: 21px;
            margin-bottom: 20px;
        }


        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }


        .gallery-card {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }


        .gallery-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }


        .gallery-action {
            padding: 12px;
            text-align: center;
        }


        .delete-btn {
            width: 100%;
            background: #dc3545;
            color: white;
            border: none;
            padding: 9px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }


        .delete-btn:hover {
            background: #bb2d3b;
        }


        .no-images {
            text-align: center;
            color: #777;
            padding: 40px 20px;
            grid-column: 1 / -1;
        }


        /* =========================
           TABLET
        ========================= */

        @media (max-width: 1000px) {

            .sidebar {
                width: 210px;
            }


            .main {
                margin-left: 210px;
            }


            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
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
            }


            .main {
                margin-left: 0;
                padding: 15px;
            }


            .upload-form {
                flex-direction: column;
                align-items: stretch;
            }


            .file-input {
                width: 100%;
            }


            .upload-btn {
                width: 100%;
            }


            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }


            .gallery-image {
                height: 180px;
            }

        }


        /* =========================
           SMALL MOBILE
        ========================= */

        @media (max-width: 450px) {

            .gallery-grid {
                grid-template-columns: 1fr;
            }


            .gallery-image {
                height: 250px;
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
        <?php
        echo htmlspecialchars(
            $_SESSION["admin_name"]
        );
        ?>

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
            <a
                href="gallery.php"
                class="active"
            >
                Gallery
            </a>
        </li>


        <li>
            <a href="bookings.php">
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

        <h1>Gallery</h1>

        <p>
            Upload and manage gallery images
        </p>

    </div>


    <!-- ERROR -->

    <?php if (isset($error)): ?>

        <div class="error">

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php endif; ?>


    <!-- =========================
         UPLOAD
    ========================= -->

    <div class="upload-box">

        <h2>
            Upload Gallery Image
        </h2>


        <form
            method="POST"
            enctype="multipart/form-data"
            class="upload-form"
        >


            <input
                type="file"
                name="image"
                class="file-input"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >


            <button
                type="submit"
                name="upload_gallery"
                class="upload-btn"
            >
                Upload Image
            </button>


        </form>

    </div>


    <!-- =========================
         GALLERY
    ========================= -->

    <div class="gallery-box">

        <h2>
            Gallery Images
        </h2>


        <div class="gallery-grid">


            <?php if ($result && $result->num_rows > 0): ?>


                <?php while ($row = $result->fetch_assoc()): ?>


                    <div class="gallery-card">


                        <img
                            src="../uploads/<?php echo htmlspecialchars($row["image"]); ?>"
                            alt="Gallery Image"
                            class="gallery-image"
                        >


                        <div class="gallery-action">


                            <form
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this image?');"
                            >


                                <input
                                    type="hidden"
                                    name="gallery_id"
                                    value="<?php echo $row["id"]; ?>"
                                >


                                <button
                                    type="submit"
                                    name="delete_gallery"
                                    class="delete-btn"
                                >
                                    Delete
                                </button>


                            </form>


                        </div>


                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="no-images">

                    No gallery images found.

                </div>


            <?php endif; ?>


        </div>

    </div>


</main>


</body>

</html>
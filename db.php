<?php
/*
 * Catering Website Database Connection
 * XAMPP defaults: host=localhost, user=root, password=empty.
 */
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$username = "root";
$password = "";
$database = "catering_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_errno) {
    http_response_code(500);
    die(
        "Database connection failed. Please start MySQL in XAMPP and make sure "
        . "the 'catering_db' database exists. Error: "
        . htmlspecialchars($conn->connect_error, ENT_QUOTES, "UTF-8")
    );
}

if (!$conn->set_charset("utf8mb4")) {
    die("Database character set could not be configured.");
}
?>

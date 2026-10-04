<?php
// Protect pages that require a logged-in normal user.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["user_role"]) ||
    $_SESSION["user_role"] !== "user"
) {
    $currentPage = basename($_SERVER["PHP_SELF"]);
    header(
        "Location: register.php?required=1&redirect=" .
        urlencode($currentPage)
    );
    exit;
}
?>

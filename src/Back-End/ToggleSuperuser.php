<?php
require __DIR__ . "/superuser_auth.php";
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Admin/dashboard.php");
    exit();
}

$submittedToken = $_POST["csrf_token"] ?? "";
if (
    empty($_SESSION["toggle_superuser_csrf_token"]) ||
    !is_string($submittedToken) ||
    !hash_equals($_SESSION["toggle_superuser_csrf_token"], $submittedToken)
) {
    echo "<h2>CSRF Attack is detected</h2>";
    die();
}

$userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
$superuser = filter_input(INPUT_POST, 'superuser', FILTER_VALIDATE_INT);

if ($userid === false || $userid === null || $superuser === false || $superuser === null) {
    $_SESSION['StatusMessage'] = "That account could not be found.";
    header("Location: ../Admin/dashboard.php");
    exit();
}

if ($superuser !== 0 && $superuser !== 1) {
    $_SESSION['StatusMessage'] = "Invalid request.";
    header("Location: ../Admin/dashboard.php");
    exit();
}

if ($userid === (int) $_SESSION['userid']) {
    $_SESSION['StatusMessage'] = "You cannot change your own superuser access.";
    header("Location: ../Admin/dashboard.php");
    exit();
}

SetUserSuperuser($userid, $superuser === 1);
header("Location: ../Admin/dashboard.php");
exit();

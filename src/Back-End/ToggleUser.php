<?php
require __DIR__ . "/superuser_auth.php";
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Admin/dashboard.php");
    exit();
}

$submittedToken = $_POST["csrf_token"] ?? "";
if (
    empty($_SESSION["toggle_user_csrf_token"]) ||
    !is_string($submittedToken) ||
    !hash_equals($_SESSION["toggle_user_csrf_token"], $submittedToken)
) {
    echo "<h2>CSRF Attack is detected</h2>";
    die();
}

$userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
$disabled = filter_input(INPUT_POST, 'disabled', FILTER_VALIDATE_INT);

if ($userid === false || $userid === null || $disabled === false || $disabled === null) {
    $_SESSION['StatusMessage'] = "That account could not be found.";
    header("Location: ../Admin/dashboard.php");
    exit();
}

if ($disabled !== 0 && $disabled !== 1) {
    $_SESSION['StatusMessage'] = "Invalid request.";
    header("Location: ../Admin/dashboard.php");
    exit();
}

if ($userid === (int) $_SESSION['userid']) {
    $_SESSION['StatusMessage'] = "You cannot disable your own account.";
    header("Location: ../Admin/dashboard.php");
    exit();
}

SetUserDisabled($userid, $disabled === 1);
header("Location: ../Admin/dashboard.php");
exit();

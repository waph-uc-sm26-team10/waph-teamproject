<?php
require __DIR__ . "/session_auth.php";
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Front-End/viewposts.php");
    exit();
}

$submittedToken = $_POST["csrf_token"] ?? "";
if (
    empty($_SESSION["post_action_csrf_token"]) ||
    !is_string($submittedToken) ||
    !hash_equals($_SESSION["post_action_csrf_token"], $submittedToken)
) {
    echo "<h2>CSRF Attack is detected</h2>";
    die();
}

$postid = filter_input(INPUT_POST, 'postid', FILTER_VALIDATE_INT);
if ($postid === false || $postid === null) {
    $_SESSION['StatusMessage'] = "That post could not be found.";
    header("Location: ../Front-End/viewposts.php");
    exit();
}

DeletePost($postid, $_SESSION['userid']);
header("Location: ../Front-End/viewposts.php");
exit();

<?php
require __DIR__ . "/session_auth.php";
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Front-End/viewposts.php");
    exit();
}

$submittedToken = $_POST["csrf_token"] ?? "";
if (
    empty($_SESSION["comment_csrf_token"]) ||
    !is_string($submittedToken) ||
    !hash_equals($_SESSION["comment_csrf_token"], $submittedToken)
) {
    echo "<h2>CSRF Attack is detected</h2>";
    die();
}

$postid = filter_input(INPUT_POST, 'postid', FILTER_VALIDATE_INT);
$content = trim($_POST["content"] ?? "");

if ($postid === false || $postid === null) {
    $_SESSION['StatusMessage'] = "That post could not be found.";
    header("Location: ../Front-End/viewposts.php");
    exit();
}

if ($content === "") {
    $_SESSION['StatusMessage'] = "A comment cannot be empty.";
    header("Location: ../Front-End/viewposts.php");
    exit();
}

if (strlen($content) > 1000) {
    $_SESSION['StatusMessage'] = "A comment cannot be longer than 1000 characters.";
    header("Location: ../Front-End/viewposts.php");
    exit();
}

if (GetPost($postid) === null) {
    $_SESSION['StatusMessage'] = "That post could not be found.";
    header("Location: ../Front-End/viewposts.php");
    exit();
}

AddComment($postid, $_SESSION['userid'], $content);
header("Location: ../Front-End/viewposts.php");
exit();

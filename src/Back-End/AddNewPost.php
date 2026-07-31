<?php
require __DIR__ . "/session_auth.php";
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Front-End/newpost.php");
    exit();
}

$submittedToken = $_POST["csrf_token"] ?? "";
if (
    empty($_SESSION["newpost_csrf_token"]) ||
    !is_string($submittedToken) ||
    !hash_equals($_SESSION["newpost_csrf_token"], $submittedToken)
) {
    echo "<h2>CSRF Attack is detected</h2>";
    die();
}

$title = trim($_POST["title"] ?? "");
$content = trim($_POST["content"] ?? "");

if ($title === "" || $content === "") {
    $_SESSION['StatusMessage'] = "Title and content cannot be empty.";
    header("Location: ../Front-End/newpost.php");
    exit();
}

if (strlen($title) < 3 || strlen($title) > 150) {
    $_SESSION['StatusMessage'] = "Title must be between 3 and 150 characters.";
    header("Location: ../Front-End/newpost.php");
    exit();
}

if (strlen($content) > 5000) {
    $_SESSION['StatusMessage'] = "Content cannot be longer than 5000 characters.";
    header("Location: ../Front-End/newpost.php");
    exit();
}

if (AddPost($title, $content, $_SESSION['userid'])) {
    unset($_SESSION["newpost_csrf_token"]);
    header("Location: ../Front-End/viewposts.php");
    exit();
}

header("Location: ../Front-End/newpost.php");
exit();

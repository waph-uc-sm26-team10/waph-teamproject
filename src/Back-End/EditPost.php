<?php
require __DIR__ . "/session_auth.php";
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../Front-End/viewposts.php");
    exit();
}

$submittedToken = $_POST["csrf_token"] ?? "";
if (
    empty($_SESSION["editpost_csrf_token"]) ||
    !is_string($submittedToken) ||
    !hash_equals($_SESSION["editpost_csrf_token"], $submittedToken)
) {
    echo "<h2>CSRF Attack is detected</h2>";
    die();
}

$postid = filter_input(INPUT_POST, 'postid', FILTER_VALIDATE_INT);
$title = trim($_POST["title"] ?? "");
$content = trim($_POST["content"] ?? "");

if ($postid === false || $postid === null) {
    $_SESSION['StatusMessage'] = "That post could not be found.";
    header("Location: ../Front-End/viewposts.php");
    exit();
}

if ($title === "" || $content === "") {
    $_SESSION['StatusMessage'] = "Title and content cannot be empty.";
    header("Location: ../Front-End/editpost.php?postid=" . $postid);
    exit();
}

if (strlen($title) < 3 || strlen($title) > 150 || strlen($content) > 5000) {
    $_SESSION['StatusMessage'] = "Title must be 3-150 characters and content under 5000.";
    header("Location: ../Front-End/editpost.php?postid=" . $postid);
    exit();
}

UpdatePost($postid, $title, $content, $_SESSION['userid']);
unset($_SESSION["editpost_csrf_token"]);
header("Location: ../Front-End/viewposts.php");
exit();

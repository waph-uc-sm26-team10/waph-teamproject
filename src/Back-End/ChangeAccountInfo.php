<?php
session_start();

require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Please submit the account information form.");
}

$submittedToken = $_POST["csrf_token"] ?? "";

if (
    empty($_SESSION["account_info_csrf_token"]) ||
    empty($submittedToken) ||
    !hash_equals(
        $_SESSION["account_info_csrf_token"],
        $submittedToken
    )
) {
    http_response_code(403);
    exit("CSRF Attack Detected");
}

$email = $_POST["email"] ?? "";
$username = $_POST["username"] ?? "";
$name = $_POST["name"] ?? "";
$phone = $_POST["phone"] ?? "";

if (
    empty($email) &&
    empty($username) &&
    empty($name) &&
    empty($phone)
) {
    echo "<h2>Account update failed.</h2>";
    echo "<p>All fields are empty. Cannot change user information.</p>";
    header("Refresh:2; url=../Front-End/changeaccountinfo.php");
    exit();
}

if (UpdateUser($email, $username, $name, $phone)) {
    unset($_SESSION["account_info_csrf_token"]);

    echo "<h2>Account information updated successfully!</h2>";

    if (!empty($name)) {
        echo "<p>Welcome, " .
            htmlentities($name, ENT_QUOTES, "UTF-8") .
            "</p>";
    }

    header("Refresh:2; url=../Front-End/homepage.php");
    exit();
}

echo "<h2>Account update failed!</h2>";
header("Refresh:2; url=../Front-End/changeaccountinfo.php");
exit();
?>

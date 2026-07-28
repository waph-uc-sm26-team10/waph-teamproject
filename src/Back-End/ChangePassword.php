<?php
session_start();
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $oldPassword = sanitize_input($_POST["oldPassword"] ?? "");
    $newPassword = sanitize_input($_POST["newPassword"] ?? "");

    if (empty($oldPassword) || empty($newPassword)) {
        echo "<h2>Login failed.</h2>";
        echo "<p>Username and password cannot be empty.</p>";
        header("Refresh:0; url=../Front-End/changepassword.php");
        die();
    }

    if (strlen($oldPassword) > 100 || strlen($newPassword) > 100) {
        echo "<h2>Password changed Failed!</h2>";
        echo "<p>Input is too long.</p>";
        header("Refresh:0; url=../Front-End/changepassword.php");
        die();
    }

    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/", $newPassword)) {
        echo "<h2>Password changed Failed!</h2>";
        echo "<p>Password must be at least 6 characters and include uppercase, lowercase, and a number.</p>";
        header("Refresh:0; url=../Front-End/changepassword.php");
        die();
    }

    if (ChangePassword($newPassword,$oldPassword)) {
        echo "<h2>Password changed successful!</h2>";
        echo "<p>Welcome, " . htmlentities($name, ENT_QUOTES, 'UTF-8') . "</p>";
        header("Refresh:0; url=../Front-End/homepage.php");
        exit();
    } else {
        echo "<h2>Password changed Failed!</h2>";
        header("Refresh:0; url=../Front-End/changepassword.php");
        die();    
    }

} else {
    echo "Please submit the login form.";
}

function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>
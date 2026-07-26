<?php
session_start();
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $oldPassword = $_POST["oldPassword"] ?? "";
    $newPassword = $_POST["newPassword"] ?? "";

    if (empty($oldPassword) || empty($newPassword)) {
        echo "<h2>Login failed.</h2>";
        echo "<p>Username and password cannot be empty.</p>";
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
?>

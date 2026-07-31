<?php
session_start();
require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        echo "<h2>Login failed.</h2>";
        echo "<p>Username and password cannot be empty.</p>";
        header("Refresh:0; url=../Front-End/loginform.php");
        die();
    }
    if (LoginUser($username, $password)) {
        echo "<h2>Login successful!</h2>";
        echo "<p>Welcome, " . htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8') . "</p>";
        header("Refresh:0; url=../Front-End/homepage.php");
        exit();
    } else {
        header("Refresh:0; url=../Front-End/loginform.php");
        die();    
    }

} else {
    echo "Please submit the login form.";
}
?>

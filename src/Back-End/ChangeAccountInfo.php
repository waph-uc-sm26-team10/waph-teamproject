<?php
session_start();
    require __DIR__ . "/database.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"] ?? "";
        $username = $_POST["username"] ?? "";
        $name = $_POST["name"] ?? "";
        $phone = $_POST["phone"] ?? "";

        //Since the sql query handles null checking the only checking we have to do is where each value is null.
        if(empty($email) && empty($name) && empty($username)){
            echo "<h2>Registration failed.</h2>";
            echo "<p>All Fields are empty. Cannot change user information</p>";
            header("Refresh:0; url=../Front-End/changeaccountinfo.php");
            exit();
        }

        if (UpdateUser($email,$username,$name)) {
            echo "<h2>Registration successful!</h2>";
            echo "<p>Welcome, " . htmlentities($name, ENT_QUOTES, 'UTF-8') . "</p>";
            header("Refresh:0; url=../Front-End/homepage.php");
            exit();
        } else {
            echo "<h2>Registration Failed!</h2>";
            header("Refresh:0; url=../Front-End/changeaccountinfo.php");
            exit();
        }

    } else {
        echo "Please submit the login form.";
    }
?>
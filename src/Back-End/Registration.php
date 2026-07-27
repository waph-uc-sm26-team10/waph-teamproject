<?php
    session_start();
    require __DIR__ . "/database.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"] ?? "";
        $username = $_POST["username"] ?? "";
        $name = $_POST["name"] ?? "";
        $password = $_POST["password"] ?? "";

        if (empty($username) || empty($password)) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Username and password cannot be empty.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }
        echo $email;
        echo $name;
        var_dump($_POST); 

        if(empty($email) || empty($name)){
            echo "<h2>Registration failed.</h2>";
            echo "<p>email and name cannot be empty.</p>";
            header("Refresh:0; ur=../Front-End/registrationform.php");
            exit();
        }

        if (RegisterUser($email,$username,$name,$password)) {
            echo "<h2>Registration successful!</h2>";
            echo "<p>Welcome, " . htmlentities($name, ENT_QUOTES, 'UTF-8') . "</p>";
            header("Refresh:0; url=../Front-End/loginform.php");
            exit();
        } else {
            echo "<h2>Registration Failed!</h2>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

    } else {
        echo "Please submit the login form.";
    }
?>
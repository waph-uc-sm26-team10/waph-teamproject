<?php
    session_start();
    require __DIR__ . "/database.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$submittedToken = $_POST["csrf_token"] ?? "";

	if (
       	 empty($_SESSION["registration_csrf_token"]) ||
   	 empty($submittedToken) ||
   	 !hash_equals(
       		 $_SESSION["registration_csrf_token"],
       		 $submittedToken
   	 )
	){
   	 http_response_code(403);
   	 exit("CSRF attack detected.");
	}
        $email = sanitize_input($_POST["email"] ?? "");
        $username = sanitize_input($_POST["username"] ?? "");
        $name = sanitize_input($_POST["name"] ?? "");
        $password = sanitize_input($_POST["password"] ?? "");

        if (empty($username) || empty($password)) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Username and password cannot be empty.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }
        if(empty($email) || empty($name)){
            echo "<h2>Registration failed.</h2>";
            echo "<p>email and name cannot be empty.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

        if (strlen($name) > 50 || strlen($email) > 255 || strlen($username) > 50 || strlen($password) > 100) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Input is too long.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

        if (!preg_match("/^\w+$/", $username)) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Username can contain only letters, numbers, and underscores.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Name can contain only letters and spaces.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

        if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/", $password)) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Password must be at least 6 characters and include uppercase, lowercase, and a number.</p>";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

        if (FindUser($username)) {
            echo "<h2>Registration failed.</h2>";
            echo "<p>Username Already Exists</p>";
            $_SESSION['StatusMessage'] = "Username Already Exists";
            header("Refresh:0; url=../Front-End/registrationform.php");
            exit();
        }

        if (RegisterUser($email,$username,$name,$password)) {
 	    unset($_SESSION["registration_csrf_token"]);
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

    function sanitize_input($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
?>

<?php
    session_start();
    require_once __DIR__ . '/../config/config.php';

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        // Log the real reason; show the user nothing that describes our database.
        error_log("WAPH-Team10 database connection failed: " . $mysqli->connect_error);
        die("Database connection failed.");
    }

    function ChangePassword($newPassword, $oldPassword){
        try{
            global $mysqli;
            $preparedsql = "UPDATE users SET password = md5(?) WHERE username = ? AND password = md5(?)";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                die();
            }
            $stmt->bind_param("sss", $newPassword, $_SESSION['username'], $oldPassword );

            if($stmt->execute()){
                return true;
            }   
            $_SESSION['StatusMessage'] = "Changed Password Failed";
            return false;
        }
        catch(mysqli_sql_exception $e){
            die();
        }
    }

    function LoginUser($username, $password){
        try{
            global $mysqli;
            $preparedsql = "SELECT * FROM users WHERE username = ? AND password = md5(?)";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                die();
            }
            $stmt->bind_param("ss", $username, $password);

            $stmt->execute();

            if($stmt->get_result()->num_rows == 1){
                $_SESSION['username'] = $username;
                return true;
            }
            $_SESSION['StatusMessage'] = "Incorrect Username or Password";
            return false;
        }
        catch(mysqli_sql_exception $e){
            //comment out once working
            echo "<p>debug Info>" . $e->getMessage() ."</p>";
            die();
        }
    }
    function FindUser($username){
        try{
            global $mysqli;
            $preparedsql = "SELECT * FROM users where username = ?";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                die();
            }
            $stmt->bind_param("s",$username);
            $stmt->execute();

            if($stmt->get_result()->num_rows == 1){
                return true;
            }
            return false;
        }
        catch(mysqli_sql_exception $e){
            //echo "<p>debug Info>" . $e->getMessage() ."</p>";
            die();
        }
    }
    function RegisterUser($email, $username, $name, $password){
        try{
            global $mysqli;
            $preparedsql = "INSERT INTO users (email, username, name, password) VALUES(?,?,?,md5(?))";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                die();
            }
            $stmt->bind_param("ssss", $email, $username, $name, $password);
            //check if username already exists 
            if(FindUser($username)){
                $_SESSION['StatusMessage'] = "Username Already Exists";
                return false;
            }
            if($stmt->execute()){
                $_SESSION['StatusMessage'] = "Account Registered Successfully";
                return true;
            }
            $_SESSION['StatusMessage'] = "Failed to Register User. Please try again!";
            return false;
        }catch(mysqli_sql_exception $e){
            //echo "<p>debug Info>" . $e->getMessage() ."</p>";
            die();
        }
    }

    function UpdateUser($email,$username,$name,$phone){
        try{
            global $mysqli;
            //Check if value is empty and if so set it to null so the sqli query works 
            //otherwise keep value
            $email = empty($email) ? null : $email;
            $username = empty($username) ? null : $username;
            $name = empty($name) ? null : $name;
            $phone = empty($phone) ? null : $phone;
            //This checks if the new email username or name is null and if so keeps the current value inside that column.
            //https://www.w3schools.com/sql/sql_case.asp
            $preparedsql = "UPDATE users SET
                email = CASE WHEN ? IS NOT NULL THEN ? ELSE email END,
                username = CASE WHEN ? IS NOT NULL THEN ? ELSE username END,
                name = CASE WHEN ? IS  NOT NULL THEN ? ELSE name END,
                phone = CASE WHEN ? IS  NOT NULL THEN ? ELSE phone END
            WHERE username = ?";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                die();
            }
            $stmt->bind_param("sssssssss", $email, $email, $username, $username, $name, $name,$phone,$phone, $_SESSION['username']);
            //check if username already exists 
            if(FindUser($username)){
                $_SESSION['StatusMessage'] = "Username Already Exists";
                return false;
            }
            if($stmt->execute()){
                if(!empty($username)) $_SESSION['username'] = $username;
                $_SESSION['StatusMessage'] = "Account Info Changed Successfully";
                return true;
            }
            $_SESSION['StatusMessage'] = "Failed to change account info. Please try again";
            return false;
        }catch(mysqli_sql_exception $e){
            echo "<p>debug Info>" . $e->getMessage() ."</p>";
            die();
        }
    }
?>
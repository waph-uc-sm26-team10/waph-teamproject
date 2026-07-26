<?php
    session_start();
    $ini = parse_ini_file(__DIR__ . '/../../config.ini');

    $mysqli = new mysqli(
        $ini['DB_HOST'],
        $ini['DB_USER'],
        $ini['DB_PASSWORD'],
        $ini['DB_NAME']
    );

    if ($mysqli->connect_errno) {
        die("Database connection failed - Error Message:" . $mysqli->connect_error);
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
                return true;
            }
            $_SESSION['StatusMessage'] = "Failed to Register User. Please try again!";
            return false;
        }catch(mysqli_sql_exception $e){
            //echo "<p>debug Info>" . $e->getMessage() ."</p>";
            die();
        }
    }
?>
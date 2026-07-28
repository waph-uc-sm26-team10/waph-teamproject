<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
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
            $preparedsql = "UPDATE users SET password = md5(?) WHERE userid = ? AND password = md5(?)";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("sis", $newPassword, $_SESSION['userid'], $oldPassword );

            if(!$stmt->execute()){
                $_SESSION['StatusMessage'] = "Changed Password Failed";
                return false;
            }
            if($stmt->affected_rows === 1){
                return true;
            }
            $_SESSION['StatusMessage'] = "Current password is incorrect.";
            return false;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 ChangePassword failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not change password. Please try again.";
            return false;
        }
    }

    function LoginUser($username, $password){
        try{
            global $mysqli;
            $preparedsql = "SELECT userid, username FROM users WHERE username = ? AND password = md5(?)";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("ss", $username, $password);

            $stmt->execute();

            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $_SESSION['userid'] = (int) $row['userid'];
                $_SESSION['username'] = $row['username'];
                return true;
            }
            $_SESSION['StatusMessage'] = "Incorrect Username or Password";
            return false;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 LoginUser failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Login failed. Please try again.";
            return false;
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

    function UsernameTakenByOther($username, $userid){
        try{
            global $mysqli;
            $preparedsql = "SELECT userid FROM users WHERE username = ? AND userid <> ?";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return true;
            }
            $stmt->bind_param("si", $username, $userid);
            $stmt->execute();
            return $stmt->get_result()->num_rows > 0;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 UsernameTakenByOther failed: " . $e->getMessage());
            return true;
        }
    }

    function GetAllPosts(){
        try{
            global $mysqli;
            $preparedsql = "SELECT p.postid, p.title, p.content, p.date, u.username
                            FROM posts p
                            JOIN users u ON p.owner = u.userid
                            ORDER BY p.date DESC, p.postid DESC";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return [];
            }
            if(!$stmt->execute()){
                $_SESSION['StatusMessage'] = "Could not load posts.";
                return [];
            }

            $postid = NULL; $title = NULL; $content = NULL; $date = NULL; $owner = NULL;
            if(!$stmt->bind_result($postid, $title, $content, $date, $owner)){
                $_SESSION['StatusMessage'] = "Binding failed.";
                return [];
            }

            $posts = [];
            while($stmt->fetch()){
                $posts[] = [
                    'postid'  => $postid,
                    'title'   => $title,
                    'content' => $content,
                    'date'    => $date,
                    'owner'   => $owner
                ];
            }
            $stmt->close();
            return $posts;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 GetAllPosts failed: " . $e->getMessage());
            return [];
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
            WHERE userid = ?";
            $stmt = $mysqli->prepare($preparedsql);
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("ssssssssi", $email, $email, $username, $username, $name, $name,$phone,$phone, $_SESSION['userid']);
            if($username !== null && UsernameTakenByOther($username, $_SESSION['userid'])){
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
            error_log("WAPH-Team10 UpdateUser failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Failed to change account info. Please try again";
            return false;
        }
    }
?>
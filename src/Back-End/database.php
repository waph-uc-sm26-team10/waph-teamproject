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
            $preparedsql = "SELECT userid, username, disabled FROM users WHERE username = ? AND password = md5(?)";
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
                if((int) $row['disabled'] === 1){
                    $_SESSION['StatusMessage'] = "This account has been disabled. Please contact an administrator.";
                    return false;
                }
                session_regenerate_id(true);
                $_SESSION['userid'] = (int) $row['userid'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = IsSuperuser((int) $row['userid']) ? 'superuser' : 'user';
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
            $preparedsql = "SELECT p.postid, p.title, p.content, p.date, u.username, u.userid
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

            $postid = NULL; $title = NULL; $content = NULL; $date = NULL; $owner = NULL; $ownerid = NULL;
            if(!$stmt->bind_result($postid, $title, $content, $date, $owner, $ownerid)){
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
                    'owner'   => $owner,
                    'ownerid' => (int) $ownerid
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

    function AddPost($title, $content, $userid){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("INSERT INTO posts (title, content, owner) VALUES (?,?,?)");
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("ssi", $title, $content, $userid);
            if($stmt->execute()){
                $_SESSION['StatusMessage'] = "Post created.";
                return true;
            }
            $_SESSION['StatusMessage'] = "Could not create the post. Please try again.";
            return false;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 AddPost failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not create the post. Please try again.";
            return false;
        }
    }

    function GetPost($postid){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT postid, title, content, date, owner FROM posts WHERE postid = ?");
            if(!$stmt) return null;
            $stmt->bind_param("i", $postid);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows === 1 ? $result->fetch_assoc() : null;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 GetPost failed: " . $e->getMessage());
            return null;
        }
    }

    function UpdatePost($postid, $title, $content, $userid){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("UPDATE posts SET title = ?, content = ? WHERE postid = ? AND owner = ?");
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("ssii", $title, $content, $postid, $userid);
            if(!$stmt->execute()){
                $_SESSION['StatusMessage'] = "Could not update the post.";
                return false;
            }
            if($stmt->affected_rows === 1){
                $_SESSION['StatusMessage'] = "Post updated.";
                return true;
            }
            $_SESSION['StatusMessage'] = "You can only edit your own posts.";
            return false;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 UpdatePost failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not update the post.";
            return false;
        }
    }

    function DeletePost($postid, $userid){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("DELETE FROM posts WHERE postid = ? AND owner = ?");
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("ii", $postid, $userid);
            if(!$stmt->execute()){
                $_SESSION['StatusMessage'] = "Could not delete the post.";
                return false;
            }
            if($stmt->affected_rows === 1){
                $_SESSION['StatusMessage'] = "Post deleted.";
                return true;
            }
            $_SESSION['StatusMessage'] = "You can only delete your own posts.";
            return false;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 DeletePost failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not delete the post.";
            return false;
        }
    }

    function AddComment($postid, $userid, $content){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("INSERT INTO comments (postid, owner, content) VALUES (?,?,?)");
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("iis", $postid, $userid, $content);
            if($stmt->execute()){
                $_SESSION['StatusMessage'] = "Comment added.";
                return true;
            }
            $_SESSION['StatusMessage'] = "Could not add the comment.";
            return false;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 AddComment failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not add the comment.";
            return false;
        }
    }

    function GetCommentsForPost($postid){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT c.commentid, c.content, c.date, u.username
                                      FROM comments c
                                      JOIN users u ON c.owner = u.userid
                                      WHERE c.postid = ?
                                      ORDER BY c.date ASC, c.commentid ASC");
            if(!$stmt) return [];
            $stmt->bind_param("i", $postid);
            $stmt->execute();
            $comments = [];
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $comments[] = $row;
            }
            return $comments;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 GetCommentsForPost failed: " . $e->getMessage());
            return [];
        }
    }

    function IsSuperuser($userid){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT superuserid FROM superusers WHERE userid = ?");
            if(!$stmt) return false;
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            return $stmt->get_result()->num_rows === 1;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 IsSuperuser failed: " . $e->getMessage());
            return false;
        }
    }

    function GetAllUsers(){
        try{
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT u.userid, u.username, u.email, u.name, u.phone, u.disabled,
                                             (s.superuserid IS NOT NULL) AS is_superuser
                                      FROM users u
                                      LEFT JOIN superusers s ON s.userid = u.userid
                                      ORDER BY u.userid ASC");
            if(!$stmt) return [];
            $stmt->execute();
            $users = [];
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $users[] = $row;
            }
            return $users;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 GetAllUsers failed: " . $e->getMessage());
            return [];
        }
    }

    function SetUserSuperuser($userid, $makeSuperuser){
        try{
            global $mysqli;
            if($makeSuperuser){
                $stmt = $mysqli->prepare("INSERT IGNORE INTO superusers (userid) VALUES (?)");
            } else {
                $stmt = $mysqli->prepare("DELETE FROM superusers WHERE userid = ?");
            }
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("i", $userid);
            if(!$stmt->execute()){
                $_SESSION['StatusMessage'] = "Could not update the account.";
                return false;
            }
            $_SESSION['StatusMessage'] = $makeSuperuser ? "Account promoted to superuser." : "Superuser access removed.";
            return true;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 SetUserSuperuser failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not update the account.";
            return false;
        }
    }

    function SetUserDisabled($userid, $disabled){
        try{
            global $mysqli;
            $flag = $disabled ? 1 : 0;
            $stmt = $mysqli->prepare("UPDATE users SET disabled = ? WHERE userid = ?");
            if(!$stmt) {
                $_SESSION['StatusMessage'] = "Prepared statement failed.";
                return false;
            }
            $stmt->bind_param("ii", $flag, $userid);
            if(!$stmt->execute()){
                $_SESSION['StatusMessage'] = "Could not update the account.";
                return false;
            }
            $_SESSION['StatusMessage'] = $flag ? "Account disabled." : "Account enabled.";
            return true;
        }
        catch(mysqli_sql_exception $e){
            error_log("WAPH-Team10 SetUserDisabled failed: " . $e->getMessage());
            $_SESSION['StatusMessage'] = "Could not update the account.";
            return false;
        }
    }
?>
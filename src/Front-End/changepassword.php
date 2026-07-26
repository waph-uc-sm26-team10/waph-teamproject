<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab 3 Login</title>
</head>
<body>
    <h2>Simple Login System</h2>

    <form action="../Back-End/ChangePassword.php" method="POST">
        <label>Username: <?php echo $_SESSION['username']?></label>
        <label>Old Password:</label>
        <input type="password" name="oldPassword"><br><br>
        <label>New Password:</label>
        <input type="password" name="newPassword"><br><br>
        <!-- for input validation and regular expression checking  
        <label>Retype Password:</label>
        <input type="password" name=""><br><br> 
        -->
        <input type="submit" value="Login">
    </form>
</body>
</html>

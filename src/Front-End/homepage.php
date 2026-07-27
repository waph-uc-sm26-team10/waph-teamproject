<?php
    require __DIR__ . "/../Back-End/session_auth.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - miniFacebook</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <a href="viewposts.php">view posts</a>
    <a href="changepassword.php">change password</a>
    <a href="changeaccountinfo.php">change account info</a>
</body>
</html>

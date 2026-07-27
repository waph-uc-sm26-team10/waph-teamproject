<?php
    require __DIR__ . "/../Back-End/session_auth.php";
    require __DIR__ . "/../Back-End/database.php";

    $posts = GetAllPosts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - miniFacebook</title>
</head>
<body>
    <h2>Posts</h2>
    <p>
        Signed in as <?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>
        | <a href="homepage.php">Home</a>
    </p>

<?php if (empty($posts)): ?>
    <p>There are no posts yet.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
    <div>
        <!-- Every value from the database is encoded before it reaches the
             page, so a post whose content contains HTML cannot inject it. -->
        <h3><?php echo htmlentities($post['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <p><?php echo nl2br(htmlentities($post['content'], ENT_QUOTES, 'UTF-8')); ?></p>
        <p>
            <small>
                Posted by <?php echo htmlentities($post['owner'], ENT_QUOTES, 'UTF-8'); ?>
                on <?php echo htmlentities($post['date'], ENT_QUOTES, 'UTF-8'); ?>
            </small>
        </p>
    </div>
    <hr>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>

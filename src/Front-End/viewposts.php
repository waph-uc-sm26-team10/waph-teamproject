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
    <link rel="stylesheet" href="../../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css">
</head>
<body>
    <main class="page-shell">
        <section class="content-panel">
            <header class="content-header">
                <div>
                    <p class="project-kicker">miniFacebook</p>
                    <h1>Posts</h1>
                    <p class="account-meta">Signed in as <?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <a class="secondary-button" href="homepage.php">
                    <i class="fa-solid fa-house"></i>
                    Home
                </a>
            </header>

<?php if (empty($posts)): ?>
            <div class="empty-state">There are no posts yet.</div>
<?php else: ?>
            <div class="post-list">
    <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <!-- Every value from the database is encoded before it reaches the
                         page, so a post whose content contains HTML cannot inject it. -->
                    <h2><?php echo htmlentities($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <div class="post-content"><?php echo nl2br(htmlentities($post['content'], ENT_QUOTES, 'UTF-8')); ?></div>
                    <p class="post-meta">
                        <small>
                            <i class="fa-solid fa-user"></i>
                            Posted by <?php echo htmlentities($post['owner'], ENT_QUOTES, 'UTF-8'); ?>
                            on <?php echo htmlentities($post['date'], ENT_QUOTES, 'UTF-8'); ?>
                        </small>
                    </p>
                </article>
    <?php endforeach; ?>
            </div>
<?php endif; ?>

        </section>
    </main>
</body>
</html>
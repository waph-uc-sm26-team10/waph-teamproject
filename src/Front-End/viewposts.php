<?php
    require __DIR__ . "/../Back-End/session_auth.php";
    require __DIR__ . "/../Back-End/database.php";

    $posts = GetAllPosts();

    if (empty($_SESSION["comment_csrf_token"])) {
        $_SESSION["comment_csrf_token"] = bin2hex(random_bytes(32));
    }
    if (empty($_SESSION["post_action_csrf_token"])) {
        $_SESSION["post_action_csrf_token"] = bin2hex(random_bytes(32));
    }

    $statusMessage = $_SESSION['StatusMessage'] ?? "";
    unset($_SESSION['StatusMessage']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - miniFacebook</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
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
                <div class="header-actions">
                    <a class="secondary-button" href="newpost.php">
                        <i class="fa-solid fa-plus"></i>
                        New post
                    </a>
                    <a class="secondary-button" href="homepage.php">
                        <i class="fa-solid fa-house"></i>
                        Home
                    </a>
                    <a class="secondary-button" href="../Back-End/logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </a>
                </div>
            </header>

<?php if ($statusMessage !== ""): ?>
            <p class="status-message"><?php echo htmlentities($statusMessage, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<?php if (empty($posts)): ?>
            <div class="empty-state">There are no posts yet.</div>
<?php else: ?>
            <div class="post-list">
    <?php foreach ($posts as $post): ?>
        <?php $isOwner = ((int) $post['ownerid'] === (int) $_SESSION['userid']); ?>
                <article class="post-card">
                    <h2><?php echo htmlentities($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <div class="post-content"><?php echo nl2br(htmlentities($post['content'], ENT_QUOTES, 'UTF-8')); ?></div>
                    <p class="post-meta">
                        <small>
                            <i class="fa-solid fa-user"></i>
                            Posted by <?php echo htmlentities($post['owner'], ENT_QUOTES, 'UTF-8'); ?>
                            on <?php echo htmlentities($post['date'], ENT_QUOTES, 'UTF-8'); ?>
                        </small>
                    </p>

        <?php if ($isOwner): ?>
                    <div class="post-actions">
                        <a class="compact-button" href="editpost.php?postid=<?php echo (int) $post['postid']; ?>">
                            <i class="fa-solid fa-pen"></i>
                            Edit
                        </a>
                        <form action="../Back-End/DeletePost.php" method="POST" class="inline-form"
                              onsubmit="return confirm('Delete this post?');">
                            <input type="hidden" name="postid" value="<?php echo (int) $post['postid']; ?>">
                            <input type="hidden" name="csrf_token"
                                   value="<?php echo htmlentities($_SESSION['post_action_csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" class="compact-button is-danger">
                                <i class="fa-solid fa-trash"></i>
                                Delete
                            </button>
                        </form>
                    </div>
        <?php endif; ?>

                    <div class="comment-block">
                        <h3>Comments</h3>
        <?php $comments = GetCommentsForPost($post['postid']); ?>
        <?php if (empty($comments)): ?>
                        <p class="no-comments">No comments yet.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                        <p class="comment">
                            <strong><?php echo htmlentities($comment['username'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <span><?php echo nl2br(htmlentities($comment['content'], ENT_QUOTES, 'UTF-8')); ?></span>
                            <small><?php echo htmlentities($comment['date'], ENT_QUOTES, 'UTF-8'); ?></small>
                        </p>
            <?php endforeach; ?>
        <?php endif; ?>

                        <form action="../Back-End/AddComment.php" method="POST" class="comment-form">
                            <input type="hidden" name="postid" value="<?php echo (int) $post['postid']; ?>">
                            <input type="hidden" name="csrf_token"
                                   value="<?php echo htmlentities($_SESSION['comment_csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                            <label for="comment-<?php echo (int) $post['postid']; ?>">Add a comment</label>
                            <textarea id="comment-<?php echo (int) $post['postid']; ?>"
                                      name="content" rows="2" required maxlength="1000"
                                      placeholder="Write a comment"></textarea>
                            <button type="submit" class="primary-button">Comment</button>
                        </form>
                    </div>
                </article>
    <?php endforeach; ?>
            </div>
<?php endif; ?>

        </section>
    </main>
</body>
</html>

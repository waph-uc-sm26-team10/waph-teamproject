<?php
    require __DIR__ . "/../Back-End/session_auth.php";

    if (empty($_SESSION["newpost_csrf_token"])) {
        $_SESSION["newpost_csrf_token"] = bin2hex(random_bytes(32));
    }

    $statusMessage = $_SESSION['StatusMessage'] ?? "";
    unset($_SESSION['StatusMessage']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New post - miniFacebook</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>
<body>
    <main class="page-shell">
        <section class="content-panel">
            <header class="content-header">
                <div>
                    <p class="project-kicker">miniFacebook</p>
                    <h1>New post</h1>
                </div>
                <div class="header-actions">
                    <a class="secondary-button" href="viewposts.php">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to posts
                    </a>
                </div>
            </header>

<?php if ($statusMessage !== ""): ?>
            <p class="status-message"><?php echo htmlentities($statusMessage, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

            <form action="../Back-End/AddNewPost.php" method="POST" class="stacked-form">
                <input type="hidden" name="csrf_token"
                       value="<?php echo htmlentities($_SESSION['newpost_csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <label for="title">Title</label>
                <input type="text" id="title" name="title" required maxlength="150"
                       pattern=".{3,150}" title="Title must be between 3 and 150 characters"
                       placeholder="Post title">

                <label for="content">Content</label>
                <textarea id="content" name="content" rows="8" required maxlength="5000"
                          placeholder="What do you want to say?"></textarea>

                <button type="submit" class="primary-button">Publish</button>
            </form>
        </section>
    </main>
</body>
</html>

<?php
    require __DIR__ . "/../Back-End/session_auth.php";

    $isSuperuser = (($_SESSION['role'] ?? '') === 'superuser');
    $statusMessage = $_SESSION['StatusMessage'] ?? "";
    unset($_SESSION['StatusMessage']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - miniFacebook</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>
<body>
    <main class="page-shell">
        <section class="content-panel home-panel">
            <header class="content-header home-header">
                <div>
                    <p class="project-kicker">miniFacebook</p>
                    <h1>Welcome, <?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="account-meta">
                        <?php echo $isSuperuser ? 'Signed in as a superuser.' : 'Choose an account option below.'; ?>
                    </p>
                </div>
            </header>

<?php if ($statusMessage !== ""): ?>
            <p class="status-message"><?php echo htmlentities($statusMessage, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

            <nav class="home-actions">
                <a class="home-action" href="viewposts.php">
                    <i class="fa-solid fa-newspaper"></i>
                    <span>view posts</span>
                </a>
                <a class="home-action" href="publicchat.php">
                    <i class="fa-solid fa-message"></i>
                    <span>Join Public Chat</span>
                </a>
<?php if ($isSuperuser): ?>
                <a class="home-action" href="../Admin/dashboard.php">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>manage users</span>
                </a>
<?php endif; ?>
                <a class="home-action" href="changepassword.php">
                    <i class="fa-solid fa-key"></i>
                    <span>change password</span>
                </a>
                <a class="home-action" href="changeaccountinfo.php">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>change account info</span>
                </a>
                <a class="home-action" href="../Back-End/logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>logout</span>
                </a>
            </nav>
        </section>
    </main>
</body>
</html>
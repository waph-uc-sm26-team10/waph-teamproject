<?php
    require __DIR__ . "/../Back-End/superuser_auth.php";
    require __DIR__ . "/../Back-End/database.php";

    $users = GetAllUsers();

    if (empty($_SESSION["toggle_user_csrf_token"])) {
        $_SESSION["toggle_user_csrf_token"] = bin2hex(random_bytes(32));
    }
    if (empty($_SESSION["toggle_superuser_csrf_token"])) {
        $_SESSION["toggle_superuser_csrf_token"] = bin2hex(random_bytes(32));
    }

    $statusMessage = $_SESSION['StatusMessage'] ?? "";
    unset($_SESSION['StatusMessage']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage users - miniFacebook</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../Front-End/ui.css?v=<?php echo filemtime(__DIR__ . '/../Front-End/ui.css'); ?>">
</head>
<body>
    <main class="page-shell">
        <section class="content-panel">
            <header class="content-header">
                <div>
                    <p class="project-kicker">miniFacebook &middot; administration</p>
                    <h1>Manage users</h1>
                    <p class="account-meta">
                        Signed in as <?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?> (superuser)
                    </p>
                </div>
                <div class="header-actions">
                    <a class="secondary-button" href="../Front-End/viewposts.php">
                        <i class="fa-solid fa-newspaper"></i>
                        Posts
                    </a>
                    <a class="secondary-button" href="../Front-End/homepage.php">
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

<?php if (empty($users)): ?>
            <div class="empty-state">No registered users.</div>
<?php else: ?>
            <div class="table-scroll">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($users as $user): ?>
        <?php
            $isDisabled = ((int) $user['disabled'] === 1);
            $isSuper = ((int) $user['is_superuser'] === 1);
            $isSelf = ((int) $user['userid'] === (int) $_SESSION['userid']);
        ?>
                    <tr>
                        <td class="col-id"><?php echo (int) $user['userid']; ?></td>
                        <td class="col-user">
                            <strong><?php echo htmlentities($user['username'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <small><?php echo htmlentities($user['name'], ENT_QUOTES, 'UTF-8'); ?></small>
                        </td>
                        <td><?php echo htmlentities($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <span class="badge <?php echo $isSuper ? 'badge-superuser' : 'badge-user'; ?>">
                                <?php echo $isSuper ? 'Superuser' : 'User'; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo $isDisabled ? 'badge-disabled' : 'badge-active'; ?>">
                                <?php echo $isDisabled ? 'Disabled' : 'Active'; ?>
                            </span>
                        </td>
                        <td>
        <?php if ($isSelf): ?>
                            <span class="self-note">This is you</span>
        <?php else: ?>
                            <div class="table-actions">
                            <form action="../Back-End/ToggleUser.php" method="POST" class="inline-form">
                                <input type="hidden" name="userid" value="<?php echo (int) $user['userid']; ?>">
                                <input type="hidden" name="disabled" value="<?php echo $isDisabled ? '0' : '1'; ?>">
                                <input type="hidden" name="csrf_token"
                                       value="<?php echo htmlentities($_SESSION['toggle_user_csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="compact-button <?php echo $isDisabled ? 'is-positive' : 'is-danger'; ?>">
                                    <?php echo $isDisabled ? 'Enable' : 'Disable'; ?>
                                </button>
                            </form>
                            <form action="../Back-End/ToggleSuperuser.php" method="POST" class="inline-form">
                                <input type="hidden" name="userid" value="<?php echo (int) $user['userid']; ?>">
                                <input type="hidden" name="superuser" value="<?php echo $isSuper ? '0' : '1'; ?>">
                                <input type="hidden" name="csrf_token"
                                       value="<?php echo htmlentities($_SESSION['toggle_superuser_csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="compact-button <?php echo $isSuper ? '' : 'is-promote'; ?>">
                                    <?php echo $isSuper ? 'Revoke superuser' : 'Make superuser'; ?>
                                </button>
                            </form>
                            </div>
        <?php endif; ?>
                        </td>
                    </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
<?php endif; ?>

        </section>
    </main>
</body>
</html>

<?php
session_start();

if (empty($_SESSION["change_password_csrf_token"])) {
    $_SESSION["change_password_csrf_token"] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>

<body>
    <main class="auth-shell">
        <div class="auth-panel">
            <section class="auth-card">
                <p class="project-kicker">Account Settings</p>
                <h1>Simple Login System</h1>

                <form action="../Back-End/ChangePassword.php" method="POST">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo htmlentities(
                            $_SESSION["change_password_csrf_token"],
                            ENT_QUOTES,
                            "UTF-8"
                        ); ?>"
                    >

                    <div class="form-group">
                        <label>Username:</label>
                        <div class="session-value">
                            <?php echo htmlentities(
                                $_SESSION["username"],
                                ENT_QUOTES,
                                "UTF-8"
                            ); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="oldPassword">
                            Old Password:
                            <span class="required-star">*</span>
                        </label>

                        <div class="input-wrap">
                            <i class="fa-solid fa-key input-icon"></i>
                            <input
                                id="oldPassword"
                                type="password"
                                name="oldPassword"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">
                            New Password:
                            <span class="required-star">*</span>
                        </label>

                        <div class="input-wrap">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input
                                id="newPassword"
                                type="password"
                                name="newPassword"
                                required
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                title="Password must be at least 6 characters and include uppercase, lowercase, and a number."
                            >
                        </div>
                    </div>

                    <button class="primary-button" type="submit">
                        <i class="fa-solid fa-rotate"></i>
                        Change Password
                    </button>
                </form>

                <div class="auth-footer">
                    <span>Back to home?</span>
                    <a class="text-link" href="homepage.php">home page</a>
                    <span>|</span>
                    <a class="text-link" href="../Back-End/logout.php">logout</a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>

<?php
session_start();

if (empty($_SESSION["account_info_csrf_token"])) {
    $_SESSION["account_info_csrf_token"] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Account Info</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>

<body>
    <main class="auth-shell">
        <div class="auth-panel">
            <section class="auth-card">
                <p class="project-kicker">Account Settings</p>
                <h1>Simple Account Info Editing System</h1>

                <form action="../Back-End/ChangeAccountInfo.php" method="POST">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php
                            echo htmlentities(
                                $_SESSION["account_info_csrf_token"],
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>"
                    >

                    <div class="form-group">
                        <label for="email">Email</label>

                        <div class="input-wrap">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input id="email" type="text" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>

                        <div class="input-wrap">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input id="username" type="text" name="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Name:</label>

                        <div class="input-wrap">
                            <i class="fa-solid fa-id-card input-icon"></i>
                            <input id="name" type="text" name="name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number:</label>

                        <div class="input-wrap">
                            <i class="fa-solid fa-phone input-icon"></i>
                            <input id="phone" type="text" name="phone">
                        </div>
                    </div>

                    <button class="primary-button" type="submit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Submit
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

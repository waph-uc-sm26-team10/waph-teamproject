<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>miniFacebook Login</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>
<body>
    <main class="auth-shell">
        <div class="auth-panel">
            <section class="auth-card">
                <p class="project-kicker">miniFacebook</p>
                <h1>Simple Login System</h1>

                <form action="../Back-End/LoginUser.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input id="username" type="text" name="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-key input-icon"></i>
                            <input id="password" type="password" name="password">
                        </div>
                    </div>

                    <button class="primary-button" type="submit">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login
                    </button>
                </form>

                <div class="auth-footer">
                    <span>Not a user yet?</span>
                    <a class="text-link" href="registrationform.php">Join us</a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
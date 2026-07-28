<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 3 Login</title>
    <link rel="stylesheet" href="../../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css">
</head>
<body>
    <main class="auth-shell">
        <div class="auth-panel">
            <section class="auth-card">
                <p class="project-kicker">miniFacebook</p>
                <h1>Simple Account Registration System</h1>

                <form action="../Back-End/Registration.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input id="email" type="text" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input id="username" type="text" name="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">name: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-id-card input-icon"></i>
                            <input id="name" type="text" name="name">
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
                        <i class="fa-solid fa-user-plus"></i>
                        Submit
                    </button>
                </form>

                <div class="auth-footer">
                    <span>Already a User?</span>
                    <a class="text-link" href="loginform.php">Go and log in</a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
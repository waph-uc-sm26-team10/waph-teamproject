<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css">
</head>
<body>
    <main class="auth-shell">
        <div class="auth-panel">
            <section class="auth-card">
                <p class="project-kicker">Account Settings</p>
                <h1>Simple Login System</h1>

                <form action="../Back-End/ChangePassword.php" method="POST">
                    <div class="form-group">
                        <label>Username:</label>
                        <div class="session-value"><?php echo $_SESSION['username']?></div>
                    </div>

                    <div class="form-group">
                        <label for="oldPassword">Old Password: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-key input-icon"></i>
                            <input id="oldPassword" type="password" name="oldPassword">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input id="newPassword" type="password" name="newPassword">
                        </div>
                    </div>

                    <!-- for input validation and regular expression checking  
                    <label>Retype Password:</label>
                    <input type="password" name=""><br><br> 
                    -->
                    <button class="primary-button" type="submit">
                        <i class="fa-solid fa-rotate"></i>
                        Login
                    </button>
                </form>

                <div class="auth-footer">
                    <span>Back to home?</span>
                    <a class="text-link" href="homepage.php">home page</a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
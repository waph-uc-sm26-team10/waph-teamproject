<?php
session_start();

if (empty($_SESSION["registration_csrf_token"])) {
	$_SESSION["registration_csrf_token"] = bin2hex(random_bytes(32));
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="ui.css?v=<?php echo filemtime(__DIR__ . '/ui.css'); ?>">
</head>
<body>
    <main class="auth-shell">
        <div class="auth-panel">
            <section class="auth-card">
                <p class="project-kicker">miniFacebook</p>
                <h1>Simple Account Registration System</h1>

                <form action="../Back-End/Registration.php" method="POST">
		<input
			type="hidden"
			name="csrf_token"
			value="<?php echo htmlentities(
				$_SESSION["registration_csrf_token"],
				ENT_QUOTES,
				"UTF-8"
			); ?>"
		>
                    <div class="form-group">
                        <label for="email">Email <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input id="email" type="email" name="email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input id="username" type="text" name="username" required pattern="\w+" title="Username can contain only letters, numbers, and underscores.">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Name: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-id-card input-icon"></i>
                            <input id="name" type="text" name="name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password: <span class="required-star">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-key input-icon"></i>
                            <input id="password" type="password" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Password must be at least 6 characters and include uppercase, lowercase, and a number.">
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

<?php
// Front door for the application. This directory is the web root, so this is
// what visitors hit at https://waph-team10.minifacebook.com/
//
// It only routes: logged-in users go to their homepage, everyone else to the
// login form (which links on to registration).

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Relative targets so this works wherever the app is mounted in the web root.
if (!empty($_SESSION['userid'])) {
    header("Location: Front-End/homepage.php");
} else {
    header("Location: Front-End/loginform.php");
}
exit();

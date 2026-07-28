<?php
// Include at the very top of any page that only logged-in users may see.
// Sprint 1 requires that viewing posts is restricted to authenticated users.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['userid'])) {
    // Relative redirect: the browser resolves it against the requested page,
    // so this works no matter where the app is mounted in the web root.
    // Include this file only from pages in Front-End/.
    header("Location: loginform.php");
    exit();
}

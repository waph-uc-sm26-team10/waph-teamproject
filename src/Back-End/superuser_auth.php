<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['userid'])) {
    header("Location: ../Front-End/loginform.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'superuser') {
    http_response_code(403);
    $_SESSION['StatusMessage'] = "You do not have permission to view that page.";
    header("Location: ../Front-End/homepage.php");
    exit();
}

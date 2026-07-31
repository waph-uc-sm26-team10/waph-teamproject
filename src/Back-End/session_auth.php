<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['userid'])) {
    header("Location: ../Front-End/loginform.php");
    exit();
}

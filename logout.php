<?php
session_start();
session_destroy();

if (isset($_COOKIE['auth_token'])) {
    setcookie('auth_token', '', time() - 3600, "/");
}

header('Location: login.php');
exit;
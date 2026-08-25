<?php
include_once "helpers.php";
pokreni_sesiju();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $parametri = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $parametri['path'], $parametri['domain'], $parametri['secure'], $parametri['httponly']);
}

session_destroy();
header("Location: index.php");
exit();
?>

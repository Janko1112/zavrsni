<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['kod_kupona'])) {
    csrf_provjeri();
    $code = strtoupper(trim($_POST['kod_kupona']));
    $provjera = provjeri_kupon($conn, $code);

    if ($provjera['ok']) {
        $_SESSION['kupon'] = $code;
    } else {
        $_SESSION['kupon_greska'] = $provjera['poruka'];
        unset($_SESSION['kupon']);
    }
}

header("Location: cart.php");
exit();

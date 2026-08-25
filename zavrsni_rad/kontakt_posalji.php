<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kontakt.php");
    exit();
}

csrf_provjeri();

$ime = trim($_POST['ime'] ?? '');
$email = trim($_POST['email'] ?? '');
$poruka = trim($_POST['poruka'] ?? '');

if ($ime === '' || $poruka === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    header("Location: kontakt.php?greska=1");
    exit();
}

zabiljezi_poruku($conn, 'kontakt', $email, $ime, "Poruka s kontakt forme od " . $ime, $poruka);

header("Location: kontakt.php?poslano=1");
exit();

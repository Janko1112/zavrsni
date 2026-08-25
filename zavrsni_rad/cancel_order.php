<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT order_status FROM orders WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$narudzba = mysqli_fetch_assoc($res);
$statusi_bez_otkazivanja = ['poslana', 'dostavljena', 'otkazana', 'povrat'];

if (in_array($narudzba['order_status'], $statusi_bez_otkazivanja, true)) {
    die("Ova narudžba se više ne može otkazati jer je već poslana ili obrađena.");
}

if (!vrati_zalihu_i_promijeni_status($conn, $order_id, 'otkazana')) {
    die("Došlo je do pogreške prilikom otkazivanja narudžbe.");
}

header("Location: narudzba_detalji.php?id=" . $order_id);
exit();

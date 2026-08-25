<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT order_status FROM orders WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$narudzba = mysqli_fetch_assoc($res);

if (!in_array($narudzba['order_status'], ['čeka plaćanje', 'neuspješno plaćanje'], true)) {
    header("Location: narudzba_detalji.php?id=" . $order_id);
    exit();
}

vrati_zalihu_i_promijeni_status($conn, $order_id, 'otkazana');

header("Location: moje_narudzbe.php");
exit();

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

csrf_provjeri();

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT id, total_amount, order_status FROM orders WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$o = mysqli_fetch_assoc($res);

if (in_array($o['order_status'], ['plaćena', 'u obradi', 'poslana', 'dostavljena'], true)) {
    header("Location: narudzba_potvrda.php?order_id=" . $order_id);
    exit();
}

if ($o['order_status'] === 'otkazana') {
    die("Ova narudžba je otkazana pa se ne može platiti.");
}

$broj_kartice = preg_replace('/\s+/', '', $_POST['broj_kartice'] ?? '');
$uspjesno = ($broj_kartice === '4242424242424242');
$broj_kartice = null;

if ($uspjesno) {
    $stmt = mysqli_prepare($conn, "UPDATE orders SET payment_status = 'plaćena', order_status = 'u obradi' WHERE id = ? AND order_status IN ('čeka plaćanje', 'neuspješno plaćanje')");
    mysqli_stmt_bind_param($stmt, 'i', $order_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 1) {
        $stmt = mysqli_prepare($conn, "UPDATE payments SET status = 'plaćena' WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $order_id);
        mysqli_stmt_execute($stmt);

        posalji_potvrdu_narudzbe($conn, $order_id);
    }

    header("Location: narudzba_potvrda.php?order_id=" . $order_id);
    exit();
} else {
    $stmt = mysqli_prepare($conn, "UPDATE orders SET payment_status = 'neuspješno plaćanje', order_status = 'neuspješno plaćanje' WHERE id = ? AND order_status IN ('čeka plaćanje', 'neuspješno plaćanje')");
    mysqli_stmt_bind_param($stmt, 'i', $order_id);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($conn, "UPDATE payments SET status = 'neuspješno plaćanje' WHERE order_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $order_id);
    mysqli_stmt_execute($stmt);

    header("Location: placanje.php?order_id=" . $order_id . "&greska=1");
    exit();
}

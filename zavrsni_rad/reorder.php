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

$stmt = mysqli_prepare($conn, "SELECT id FROM orders WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) === 0) {
    die("Narudžba nije pronađena.");
}

$stmt = mysqli_prepare($conn, "SELECT oi.product_id, oi.quantity, p.quantity AS na_zalihi, p.max_per_order FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $order_id);
mysqli_stmt_execute($stmt);
$stavke = mysqli_stmt_get_result($stmt);

$preskoceno = [];

while ($row = mysqli_fetch_assoc($stavke)) {
    if ($row['na_zalihi'] <= 0) {
        $preskoceno[] = $row['product_id'];
        continue;
    }

    $stmt2 = mysqli_prepare($conn, "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($stmt2, 'ii', $user_id, $row['product_id']);
    mysqli_stmt_execute($stmt2);
    $postoji = mysqli_stmt_get_result($stmt2);
    $vec_u_kosarici = 0;
    $postoji_red = null;
    if (mysqli_num_rows($postoji) > 0) {
        $postoji_red = mysqli_fetch_assoc($postoji);
        $vec_u_kosarici = (int)$postoji_red['quantity'];
    }

    $dopusteno_jos = $row['max_per_order'] - $vec_u_kosarici;
    $kolicina = min($row['quantity'], $row['na_zalihi'], $dopusteno_jos);

    if ($kolicina <= 0) {
        $preskoceno[] = $row['product_id'];
        continue;
    }

    if ($postoji_red) {
        $stmt3 = mysqli_prepare($conn, "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt3, 'iii', $kolicina, $user_id, $row['product_id']);
        mysqli_stmt_execute($stmt3);
    } else {
        $stmt3 = mysqli_prepare($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt3, 'iii', $user_id, $row['product_id'], $kolicina);
        mysqli_stmt_execute($stmt3);
    }
}

header("Location: cart.php");
exit();

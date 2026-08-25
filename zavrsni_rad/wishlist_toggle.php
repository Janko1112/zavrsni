<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];
$redirect = sigurni_redirect(isset($_GET['redirect']) ? $_GET['redirect'] : null, 'katalog.php');

if ($product_id > 0) {
    if (je_na_listi_zelja($conn, $user_id, $product_id)) {
        $stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: " . $redirect);
exit();

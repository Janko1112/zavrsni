<?php
include_once "helpers.php";
pokreni_sesiju();

if (!isset($_SESSION['compare'])) {
    $_SESSION['compare'] = [];
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$redirect = sigurni_redirect(isset($_GET['redirect']) ? $_GET['redirect'] : null, 'katalog.php');

if ($product_id > 0) {
    $kljuc = array_search($product_id, $_SESSION['compare']);
    if ($kljuc !== false) {
        unset($_SESSION['compare'][$kljuc]);
        $_SESSION['compare'] = array_values($_SESSION['compare']);
    } elseif (count($_SESSION['compare']) < 4) {
        $_SESSION['compare'][] = $product_id;
    }
}

header("Location: " . $redirect);
exit();

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_provjeri();
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $user_id = $_SESSION['user_id'];

    if ($product_id > 0 && $rating >= 1 && $rating <= 5 && $comment !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iiis', $product_id, $user_id, $rating, $comment);
        mysqli_stmt_execute($stmt);
    }

    header("Location: article.php?id=" . $product_id);
    exit();
}

header("Location: index.php");
exit();

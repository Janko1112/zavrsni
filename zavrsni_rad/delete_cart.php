<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

if (isset($_GET['id'])) {
    $cart_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE id = '$cart_id' AND user_id = '$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: cart.php");
        exit();
    } else {
        die("Došlo je do pogreške prilikom uklanjanja artikla.");
    }
} else {
    header("Location: cart.php");
    exit();
}
?>

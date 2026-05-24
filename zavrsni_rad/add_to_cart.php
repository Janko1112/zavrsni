<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $username = $_SESSION['username'];

    $check_stock = "SELECT quantity, name FROM products WHERE id = '$product_id'";
    $stock_res = mysqli_query($conn, $check_stock);
    
    if (mysqli_num_rows($stock_res) > 0) {
        $product = mysqli_fetch_assoc($stock_res);
        
        if ($product['quantity'] <= 0) {
            echo "<script>alert('NEMA NA STANJU: Artikl " . $product['name'] . " trenutno nije dostupan.'); window.location.href = 'article.php?id=" . $product_id . "';</script>";
            exit();
        }
    } else {
        die("Artikl ne postoji.");
    }

    $check_cart = "SELECT * FROM cart WHERE user_username = '$username' AND product_id = '$product_id'";
    $cart_res = mysqli_query($conn, $check_cart);

    if (mysqli_num_rows($cart_res) > 0) {
        $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_username = '$username' AND product_id = '$product_id'";
    } else {
        $sql = "INSERT INTO cart (user_username, product_id, quantity) VALUES ('$username', '$product_id', '$quantity')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "
        <script>
        alert('Artikl " . $product['name'] . " je uspješno dodan u košaricu!');
        window.location.href = 'article.php?id=" . $product_id . "';
        </script>
        ";
    } else {
        echo "Greška: " . mysqli_error($conn);
    }
}
?>

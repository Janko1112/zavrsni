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
    
    $user_id = $_SESSION['user_id']; 

    if ($quantity <= 0) {
        die("Greška: Unesena je neispravna količina proizvoda.");
    }

    $check_stock = "SELECT quantity, name FROM products WHERE id = '$product_id'";
    $stock_res = mysqli_query($conn, $check_stock);
    
    if (mysqli_num_rows($stock_res) > 0) {
        $product = mysqli_fetch_assoc($stock_res);
        
        if ($product['quantity'] < $quantity) {
            echo "<script>alert('NEDOVOLJNO NA ZALIHI: Na skladištu trenutno nema tražene količine za artikl " . $product['name'] . ".'); window.location.href = 'article.php?id=" . $product_id . "';</script>";
            exit();
        }
    } else {
        die("Artikl ne postoji u sustavu.");
    }

    $check_cart = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $cart_res = mysqli_query($conn, $check_cart);

    if (mysqli_num_rows($cart_res) > 0) {
        $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
    } else {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "
        <script>
        window.location.href = 'article.php?id=" . $product_id . "';
        </script>
        ";
    } else {
        die("Došlo je do pogreške prilikom obrade zahtjeva.");
    }
}
?>

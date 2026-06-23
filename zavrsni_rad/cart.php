<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.id AS cart_id, cart.quantity AS cart_qty, products.* 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Vaša košarica</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="logo">PC Shop</div>

        <nav>
            <a href="index.php">Početna</a>
            <a href="komponente.php">Komponente</a>
            <a href="gaming.php">Gaming</a>
            <a href="laptopi.php">Laptopi</a>
            <a href="kontakt.php">Kontakt</a>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <a href="admin.php" class="admin_panel">Admin Panel</a>
            <?php endif; ?>
        </nav>

        <div class="header-buttons">
            <?php if(isset($_SESSION['username'])): ?>
                <span>Dobro došli, <?php echo $_SESSION['username']; ?></span>
                <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
            <?php else: ?>
                <button class="btn btn-login" onclick="window.location.href='login.php';">Prijava</button>
            <?php endif; ?>
            <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
        </div>
    </header>

    <section class="container cart-wrapper">
        <div class="cart-items-box">
            <h2>Artikli u košarici</h2>
            <br>
            <?php 
            $ukupno = 0;
            if (mysqli_num_rows($result) > 0): 
                while($row = mysqli_fetch_assoc($result)): 
                    $subtotal = $row['price'] * $row['cart_qty'];
                    $ukupno += $subtotal;
                ?>
                    <div class="cart-item">
                        <img src="<?php echo $row['image']; ?>" class="cart-item-img" alt="">
                        <div class="cart-item-info">
                            <h3><?php echo $row['name']; ?></h3>
                            <p>Cijena: <?php echo $row['price']; ?> €</p>
                            <p>Količina: <?php echo $row['cart_qty']; ?></p>
                        </div>
                        <div class="cart-subtotal">
                            <strong><?php echo $subtotal; ?> €</strong>
                            <a class="delete-cart" href="delete_cart.php?id=<?php echo $row['cart_id']; ?>" 
                            onclick="return confirm('Jeste li sigurni da želite ukloniti ovaj artikl?');">
                            Ukloni
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <h3 class="cart-total-price">Ukupno: <span><?php echo $ukupno; ?> €</span></h3>
            <?php else: ?>
                <p>Vaša košarica je prazna.</p>
                <br>
                <a href="index.php" class="btn">Natrag na trgovinu</a>
            <?php endif; ?>
        </div>

        <?php if ($ukupno > 0): ?>
        <div class="contact-box shipping-box">
            <h2>Podaci za dostavu</h2>
            <br>
            <form action="final_order.php" method="POST">
                <input type="text" name="ime" placeholder="Ime" required>
                <input type="text" name="prezime" placeholder="Prezime" required>
                <input type="text" name="adresa" placeholder="Adresa i kućni broj" required>
                <input type="text" name="grad" placeholder="Grad i poštanski broj" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="text" name="telefon" placeholder="Telefon" required>
                <label>Način plaćanja:</label>
                <input type="text" value="Plaćanje pouzećem" disabled>
                <button type="submit" class="btn btn-order">Naruči</button>
            </form>
        </div>
        <?php endif; ?>
    </section>
</body>
</html>

<?php
session_start();
include "db.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Artikl nije pronađen ili ID nije proslijeđen.");
}

$artikl_id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM products WHERE id = '$artikl_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    die("Traženi artikl ne postoji u bazi podataka.");
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['name']; ?> - Detalji</title>
    <link rel="stylesheet" href="style.css?v=">
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

<section class="product-details">
    <div class="product-wrapper">
        
        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">

        <div class="product-info-details">
            <h1><?php echo $row['name']; ?></h1>
            <p>Kategorija: <?php echo $row['category']; ?></p>
            <div class="price"><?php echo $row['price']; ?> €</div>
            
            <div class="specs">
                <h3>Opis:</h3>
                <p><?php echo $row['description']; ?></p>
            </div>


            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                
                <div class="quantity-box">
                    <label>Količina:</label>
                    <div class="quantity-controls">
                        <button type="button" onclick="decreaseQuantity()">-</button>
  
                        <input type="number" id="quantity" name="quantity" value="1" min="1" readonly>
                        <button type="button" onclick="increaseQuantity()">+</button>
                    </div>
                </div>

                <?php if(isset($_SESSION['username'])): ?>
                    <button type="submit" class="btn add-cart-btn">Dodaj u košaricu</button>
                <?php else: ?>
                    <button type="button" class="btn add-cart-btn" onclick="alert('Potrebna je prijava! Morate se ulogirati kako biste dodali artikl u košaricu.'); window.location.href='login.php?info=auth_required';">
                        Dodaj u košaricu
                    </button>   
                <?php endif; ?>

            </form>

        </div>

    </div>
</section>

<script src="js/quantity.js"></script>
</body>
</html>

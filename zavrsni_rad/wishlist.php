<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT p.id, p.name, p.price, p.image, p.quantity, p.low_stock_threshold FROM wishlist w JOIN products p ON p.id = w.product_id WHERE w.user_id = ? ORDER BY w.date_added DESC");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$stavke = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista želja</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
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

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="admin.php" class="admin_panel">Admin Panel</a>
        <?php endif; ?>
    </nav>

    <div class="header-buttons">
        <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
        <a href="profil.php" class="btn btn-login">Moj profil</a>
        <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
        <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
    </div>
</header>

<section class="page-banner">
    <div>
        <h1>Lista želja</h1>
    </div>
</section>

<section class="container">
    <div class="cards">
        <?php if (mysqli_num_rows($stavke) === 0): ?>
            <p>Vaša lista želja je prazna.</p>
        <?php endif; ?>
        <?php while ($p = mysqli_fetch_assoc($stavke)): ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                <div class="card-content">
                    <?php echo dostupnost_oznaka($p['quantity'], $p['low_stock_threshold']); ?>
                    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                    <div class="price"><?php echo htmlspecialchars($p['price']); ?>€</div>
                    <a href="article.php?id=<?php echo $p['id']; ?>" class="btn">Detalji</a>
                    <a href="wishlist_toggle.php?id=<?php echo $p['id']; ?>&redirect=<?php echo urlencode('wishlist.php'); ?>" class="btn btn-login">Ukloni</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>

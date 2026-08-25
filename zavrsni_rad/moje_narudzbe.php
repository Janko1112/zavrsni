<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT o.id, o.created_at, o.total_amount, o.shipping_cost, o.delivery_method, o.payment_method, o.payment_status, o.order_status, o.coupon_code, o.discount_amount, a.ime, a.prezime, a.adresa, a.grad FROM orders o JOIN addresses a ON a.id = o.address_id WHERE o.user_id = ? ORDER BY o.created_at DESC");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$narudzbe = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje narudžbe</title>
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
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="admin.php" class="admin_panel">Admin Panel</a>
        <?php endif; ?>
    </nav>
    <div class="header-buttons">
        <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="wishlist.php" class="btn btn-login">Lista želja</a>
        <a href="profil.php" class="btn btn-login">Moj profil</a>
        <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
        <a href="compare.php" class="btn btn-login">Usporedba</a>
        <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
    </div>
</header>

<section class="page-banner">
    <div>
        <h1>Moje narudžbe</h1>
    </div>
</section>

<section class="container">

    <?php if (mysqli_num_rows($narudzbe) === 0): ?>
        <p>Još nemate nijednu narudžbu.</p>
    <?php endif; ?>

    <?php while ($o = mysqli_fetch_assoc($narudzbe)): ?>
        <div class="order-card">
            <div class="order-card-header">
                <div>
                    <strong>Narudžba #<?php echo $o['id']; ?></strong>
                    <span class="order-date"><?php echo $o['created_at']; ?></span>
                </div>
                <span class="order-status-badge"><?php echo htmlspecialchars($o['order_status']); ?></span>
            </div>

            <p><strong>Način dostave:</strong> <?php echo htmlspecialchars(ucfirst($o['delivery_method'])); ?></p>
            <p><strong>Dostava na:</strong> <?php echo htmlspecialchars($o['ime'] . ' ' . $o['prezime']); ?><?php echo $o['delivery_method'] !== 'osobno preuzimanje' ? ', ' . htmlspecialchars($o['adresa']) . ', ' . htmlspecialchars($o['grad']) : ''; ?></p>
            <p><strong>Način plaćanja:</strong> <?php echo htmlspecialchars($o['payment_method']); ?> — <strong>Status plaćanja:</strong> <?php echo htmlspecialchars($o['payment_status']); ?></p>

            <?php
            $stmt2 = mysqli_prepare($conn, "SELECT product_name, quantity, price FROM order_items WHERE order_id = ?");
            mysqli_stmt_bind_param($stmt2, 'i', $o['id']);
            mysqli_stmt_execute($stmt2);
            $stavke = mysqli_stmt_get_result($stmt2);
            ?>
            <ul class="order-items-list">
                <?php while ($it = mysqli_fetch_assoc($stavke)): ?>
                    <li><?php echo htmlspecialchars($it['product_name']); ?> × <?php echo $it['quantity']; ?> — <?php echo $it['price']; ?>€</li>
                <?php endwhile; ?>
            </ul>

            <?php if ($o['discount_amount'] > 0): ?>
                <p>Kupon: <strong><?php echo htmlspecialchars($o['coupon_code']); ?></strong> (−<?php echo $o['discount_amount']; ?>€)</p>
            <?php endif; ?>
            <p class="order-total">Ukupno: <span><?php echo $o['total_amount']; ?>€</span> <small>(dostava: <?php echo $o['shipping_cost']; ?>€)</small></p>

            <div class="secondary-actions">
                <a href="narudzba_detalji.php?id=<?php echo $o['id']; ?>" class="btn btn-login">Detalji</a>
                <?php if (in_array($o['order_status'], ['čeka plaćanje', 'neuspješno plaćanje'], true) && $o['payment_method'] === 'Kartica'): ?>
                    <a href="placanje.php?order_id=<?php echo $o['id']; ?>" class="btn">Plati</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>

</section>

</body>
</html>

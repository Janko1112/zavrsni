<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT o.*, a.ime, a.prezime, a.adresa, a.grad, a.email, a.telefon FROM orders o JOIN addresses a ON a.id = o.address_id WHERE o.id = ? AND o.user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$o = mysqli_fetch_assoc($res);

$stmt = mysqli_prepare($conn, "SELECT product_id, product_name, quantity, price FROM order_items WHERE order_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $order_id);
mysqli_stmt_execute($stmt);
$stavke = mysqli_stmt_get_result($stmt);

$statusi_bez_otkazivanja = ['poslana', 'dostavljena', 'otkazana', 'povrat'];
$moze_se_otkazati = !in_array($o['order_status'], $statusi_bez_otkazivanja, true);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Narudžba #<?php echo $o['id']; ?></title>
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
        <h1>Narudžba #<?php echo $o['id']; ?></h1>
    </div>
</section>

<section class="container">

    <div class="order-card">
        <div class="order-card-header">
            <div>
                <strong>Datum:</strong> <?php echo $o['created_at']; ?>
            </div>
            <span class="order-status-badge"><?php echo htmlspecialchars($o['order_status']); ?></span>
        </div>

        <p><strong>Način dostave:</strong> <?php echo htmlspecialchars(ucfirst($o['delivery_method'])); ?></p>
        <p><strong>Dostava na:</strong> <?php echo htmlspecialchars($o['ime'] . ' ' . $o['prezime']); ?></p>
        <?php if ($o['delivery_method'] !== 'osobno preuzimanje'): ?>
            <p><strong>Adresa:</strong> <?php echo htmlspecialchars($o['adresa']); ?>, <?php echo htmlspecialchars($o['grad']); ?></p>
        <?php endif; ?>
        <p><strong>Kontakt:</strong> <?php echo htmlspecialchars($o['email']); ?> · <?php echo htmlspecialchars($o['telefon']); ?></p>
        <p><strong>Trošak dostave:</strong> <?php echo $o['shipping_cost'] > 0 ? $o['shipping_cost'] . ' €' : 'Besplatno'; ?></p>
        <p><strong>Način plaćanja:</strong> <?php echo htmlspecialchars($o['payment_method']); ?></p>
        <p><strong>Status plaćanja:</strong> <?php echo htmlspecialchars($o['payment_status']); ?></p>

        <h3>Stavke narudžbe</h3>
        <ul class="order-items-list">
            <?php while ($it = mysqli_fetch_assoc($stavke)): ?>
                <li><?php echo htmlspecialchars($it['product_name']); ?> × <?php echo $it['quantity']; ?> — <?php echo $it['price']; ?>€ / kom</li>
            <?php endwhile; ?>
        </ul>

        <?php if ($o['discount_amount'] > 0): ?>
            <p class="order-total">Kupon <?php echo htmlspecialchars($o['coupon_code']); ?>: −<?php echo $o['discount_amount']; ?>€</p>
        <?php endif; ?>
        <p class="order-total">Trošak dostave: <?php echo $o['shipping_cost']; ?>€</p>
        <p class="order-total">Ukupno: <span><?php echo $o['total_amount']; ?>€</span></p>

        <div class="secondary-actions">
            <a href="reorder.php?id=<?php echo $o['id']; ?>" class="btn">Ponovno naruči</a>
            <?php if (in_array($o['order_status'], ['čeka plaćanje', 'neuspješno plaćanje'], true) && $o['payment_method'] === 'Kartica'): ?>
                <a href="placanje.php?order_id=<?php echo $o['id']; ?>" class="btn">Plati</a>
            <?php endif; ?>
            <?php if ($moze_se_otkazati): ?>
                <a href="cancel_order.php?id=<?php echo $o['id']; ?>" class="btn btn-login" onclick="return confirm('Sigurno želite otkazati ovu narudžbu?');">Otkaži narudžbu</a>
            <?php endif; ?>
        </div>
    </div>

</section>

</body>
</html>

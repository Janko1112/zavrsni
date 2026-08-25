<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT o.*, a.ime, a.prezime, a.adresa, a.grad, a.email, a.telefon FROM orders o JOIN addresses a ON a.id = o.address_id WHERE o.id = ? AND o.user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$o = mysqli_fetch_assoc($res);

$stmt = mysqli_prepare($conn, "SELECT product_name, quantity, price FROM order_items WHERE order_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $order_id);
mysqli_stmt_execute($stmt);
$stavke = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Narudžba #<?php echo $o['id']; ?></title>
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
        <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
        <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
        <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
    </div>
</header>

<section class="container order-summary">
    <h2 class="order-heading">
        <?php echo $o['payment_status'] === 'plaćena' ? 'Plaćanje uspješno! Narudžba je zaprimljena.' : 'Narudžba je uspješno primljena!'; ?>
    </h2>
    <p>Broj narudžbe: <strong>#<?php echo $o['id']; ?></strong></p>
    <p>Hvala Vam na kupnji, <strong><?php echo htmlspecialchars($o['ime'] . " " . $o['prezime']); ?></strong>.</p>
    <p>Potvrda narudžbe s detaljima kupnje zabilježena je u sustavu i dostupna Vam je u svakom trenutku pod <a href="moje_narudzbe.php">Moje narudžbe</a>.</p>
    <br>
    <div>
        <h4>Podaci za dostavu:</h4>
        <p><strong>Način dostave:</strong> <?php echo htmlspecialchars(ucfirst($o['delivery_method'])); ?></p>
        <?php if ($o['delivery_method'] !== 'osobno preuzimanje'): ?>
            <p><strong>Adresa:</strong> <?php echo htmlspecialchars($o['adresa']); ?>, <?php echo htmlspecialchars($o['grad']); ?></p>
        <?php endif; ?>
        <p><strong>Telefon:</strong> <?php echo htmlspecialchars($o['telefon']); ?></p>
        <p><strong>Trošak dostave:</strong> <?php echo $o['shipping_cost'] > 0 ? $o['shipping_cost'] . ' €' : 'Besplatno'; ?></p>
        <p><strong>Način plaćanja:</strong> <?php echo $o['payment_method'] === 'Kartica' ? 'Kartica' : 'Gotovinom pri preuzimanju (Pouzećem)'; ?></p>
        <p><strong>Status plaćanja:</strong> <?php echo htmlspecialchars($o['payment_status']); ?></p>
        <p><strong>Status narudžbe:</strong> <?php echo htmlspecialchars($o['order_status']); ?></p>
    </div>
    <h4>Naručeni artikli:</h4>
    <pre><?php
        $tekst = "";
        while ($it = mysqli_fetch_assoc($stavke)) {
            $tekst .= "- " . $it['product_name'] . " x" . $it['quantity'] . " (" . ($it['price'] * $it['quantity']) . " €)\n";
        }
        echo htmlspecialchars(trim($tekst));
    ?></pre>
    <?php if ($o['discount_amount'] > 0): ?>
        <p>Kupon (<?php echo htmlspecialchars($o['coupon_code']); ?>): −<?php echo $o['discount_amount']; ?> €</p>
    <?php endif; ?>
    <h3>Ukupno<?php echo $o['payment_status'] === 'plaćena' ? ' plaćeno' : ' za platiti'; ?>: <span><?php echo $o['total_amount']; ?> €</span></h3>
    <br>
    <a href="index.php" class="btn-index">Natrag na početnu stranicu</a>
</section>
</body>
</html>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['promijeni_status'])) {
    csrf_provjeri();
    $novi_status = trim($_POST['order_status']);
    $tracking = trim($_POST['tracking_number'] ?? '');

    $stmt = mysqli_prepare($conn, "SELECT order_status, tracking_number FROM orders WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $stari = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($novi_status === 'povrat' && $stari['order_status'] !== 'povrat') {
        vrati_zalihu_i_promijeni_status($conn, $id, 'povrat');
    } elseif ($novi_status === 'poslana') {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET order_status = ?, tracking_number = ?, sent_at = NOW() WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'ssi', $novi_status, $tracking, $id);
        mysqli_stmt_execute($stmt);
    } elseif ($novi_status === 'dostavljena') {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET order_status = ?, delivered_at = NOW() WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $novi_status, $id);
        mysqli_stmt_execute($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET order_status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $novi_status, $id);
        mysqli_stmt_execute($stmt);
    }

    if ($novi_status === 'plaćena') {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET payment_status = 'plaćena' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $stmt = mysqli_prepare($conn, "UPDATE payments SET status = 'plaćena' WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    } elseif ($novi_status === 'povrat') {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET payment_status = 'povrat' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $stmt = mysqli_prepare($conn, "UPDATE payments SET status = 'povrat' WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    }

    zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Promijenio status narudžbe', "Narudžba #$id: '{$stari['order_status']}' → '$novi_status'" . ($tracking ? " (tracking: $tracking)" : ""));

    header("Location: admin_narudzba_detalji.php?id=" . $id . "&spremljeno=1");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT o.*, u.username, a.ime, a.prezime, a.adresa, a.grad, a.email, a.telefon FROM orders o JOIN users u ON u.id = o.user_id JOIN addresses a ON a.id = o.address_id WHERE o.id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$o = mysqli_fetch_assoc($res);

$stmt = mysqli_prepare($conn, "SELECT product_name, quantity, price FROM order_items WHERE order_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$stavke = mysqli_stmt_get_result($stmt);

$statusi = ['kreirana', 'čeka plaćanje', 'plaćena', 'u obradi', 'poslana', 'dostavljena', 'otkazana', 'povrat', 'neuspješno plaćanje'];

$naslov_stranice = "Narudžba #$id";
include "admin_header.php";
?>

<h2 class="section-title">Narudžba #<?php echo $o['id']; ?></h2>

<?php if (isset($_GET['spremljeno'])): ?>
    <p class="message message-success">Status je ažuriran.</p>
<?php endif; ?>

<div class="order-card">
    <p><strong>Korisnik:</strong> <?php echo htmlspecialchars($o['username']); ?></p>
    <p><strong>Datum:</strong> <?php echo $o['created_at']; ?></p>
    <p><strong>Način dostave:</strong> <?php echo htmlspecialchars(ucfirst($o['delivery_method'])); ?></p>
    <p><strong>Dostava na:</strong> <?php echo htmlspecialchars($o['ime'] . ' ' . $o['prezime']); ?><?php echo $o['delivery_method'] !== 'osobno preuzimanje' ? ', ' . htmlspecialchars($o['adresa']) . ', ' . htmlspecialchars($o['grad']) : ''; ?></p>
    <p><strong>Kontakt:</strong> <?php echo htmlspecialchars($o['email']); ?> · <?php echo htmlspecialchars($o['telefon']); ?></p>
    <p><strong>Način plaćanja:</strong> <?php echo htmlspecialchars($o['payment_method']); ?> — <strong>Status plaćanja:</strong> <?php echo htmlspecialchars($o['payment_status']); ?></p>
    <?php if ($o['tracking_number']): ?>
        <p><strong>Broj za praćenje pošiljke:</strong> <?php echo htmlspecialchars($o['tracking_number']); ?></p>
    <?php endif; ?>
    <?php if ($o['sent_at']): ?>
        <p><strong>Poslano:</strong> <?php echo $o['sent_at']; ?></p>
    <?php endif; ?>
    <?php if ($o['delivered_at']): ?>
        <p><strong>Dostavljeno:</strong> <?php echo $o['delivered_at']; ?></p>
    <?php endif; ?>

    <h3>Stavke</h3>
    <ul class="order-items-list">
        <?php while ($it = mysqli_fetch_assoc($stavke)): ?>
            <li><?php echo htmlspecialchars($it['product_name']); ?> × <?php echo $it['quantity']; ?> — <?php echo $it['price']; ?>€/kom</li>
        <?php endwhile; ?>
    </ul>

    <?php if ($o['discount_amount'] > 0): ?>
        <p class="order-total">Kupon <?php echo htmlspecialchars($o['coupon_code']); ?>: −<?php echo $o['discount_amount']; ?>€</p>
    <?php endif; ?>
    <p class="order-total">Trošak dostave: <?php echo $o['shipping_cost']; ?>€</p>
    <p class="order-total">Ukupno: <span><?php echo $o['total_amount']; ?>€</span></p>
</div>

<div class="contact-box">
    <h3>Promijeni status narudžbe</h3>
    <form method="POST">
        <?php echo csrf_polje(); ?>
        <select name="order_status" required>
            <?php foreach ($statusi as $s): ?>
                <option value="<?php echo htmlspecialchars($s); ?>" <?php echo $o['order_status'] === $s ? 'selected' : ''; ?>><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="tracking_number" placeholder="Broj za praćenje pošiljke (kod slanja)" value="<?php echo htmlspecialchars($o['tracking_number'] ?? ''); ?>">
        <button type="submit" name="promijeni_status" class="btn">Spremi status</button>
    </form>
</div>

<?php include "admin_footer.php"; ?>

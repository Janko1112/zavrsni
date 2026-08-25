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

$stmt = mysqli_prepare($conn, "SELECT id, total_amount, order_status, payment_status FROM orders WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Narudžba nije pronađena.");
}

$o = mysqli_fetch_assoc($res);

if (in_array($o['order_status'], ['plaćena', 'u obradi', 'poslana', 'dostavljena'], true)) {
    header("Location: narudzba_potvrda.php?order_id=" . $order_id);
    exit();
}

if ($o['order_status'] === 'otkazana') {
    die("Ova narudžba je otkazana pa se ne može platiti.");
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plaćanje narudžbe #<?php echo $o['id']; ?></title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <div class="logo">PC Shop</div>
    <div class="header-buttons">
        <span>🔒 Sigurno plaćanje</span>
    </div>
</header>

<section class="container">
    <div class="contact-box payment-box">

        <span class="payment-mode-badge">Testno okruženje</span>

        <h2 class="section-title">Plaćanje narudžbe #<?php echo $o['id']; ?></h2>
        <p class="payment-amount">Iznos za platiti: <strong><?php echo $o['total_amount']; ?> €</strong></p>

        <?php if (isset($_GET['greska'])): ?>
            <p class="message">Plaćanje nije uspjelo. Provjerite podatke kartice i pokušajte ponovno.</p>
        <?php endif; ?>

        <form action="placanje_obradi.php" method="POST" id="forma_placanja" onsubmit="return posaljiJednom();">
            <?php echo csrf_polje(); ?>
            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">

            <input type="text" name="broj_kartice" placeholder="Broj kartice" maxlength="19" required>
            <input type="text" name="isteklost" placeholder="MM/GG" maxlength="5" required>
            <input type="text" name="cvv" placeholder="CVV" maxlength="4" required>
            <input type="text" name="ime_na_kartici" placeholder="Ime i prezime na kartici" required>

            <button type="submit" class="btn btn-order">Plati <?php echo $o['total_amount']; ?> €</button>
        </form>

        <a href="placanje_odustani.php?order_id=<?php echo $o['id']; ?>" class="btn btn-login" onclick="return confirm('Sigurno želite odustati od ove narudžbe?');">Odustani i otkaži narudžbu</a>
    </div>
</section>

<script>
function posaljiJednom() {
    var gumb = document.querySelector('#forma_placanja button');
    if (gumb.disabled) {
        return false;
    }
    gumb.disabled = true;
    gumb.textContent = 'Obrada plaćanja...';
    return true;
}
</script>

</body>
</html>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT cart.id AS cart_id, cart.quantity AS cart_qty, products.* FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$stmt = mysqli_prepare($conn, "SELECT id, ime, prezime, adresa, grad, email, telefon FROM saved_addresses WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$spremljene_adrese = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="wishlist.php" class="btn btn-login">Lista želja</a>
                <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
                <a href="profil.php" class="btn btn-login">Moj profil</a>
                <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
            <?php else: ?>
                <button class="btn btn-login" onclick="window.location.href='login.php';">Prijava</button>
            <?php endif; ?>
            <a href="compare.php" class="btn btn-login">Usporedba</a>
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
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" class="cart-item-img" alt="">
                        <div class="cart-item-info">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
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
                <?php
                $popust = 0;
                $primijenjeni_kupon = null;
                if (!empty($_SESSION['kupon'])) {
                    $provjera_kupona = provjeri_kupon($conn, $_SESSION['kupon']);
                    if ($provjera_kupona['ok']) {
                        $primijenjeni_kupon = $provjera_kupona['kupon'];
                        $popust = izracunaj_popust($primijenjeni_kupon, $ukupno);
                    }
                }
                $trosak_dostave = izracunaj_trosak_dostave($ukupno, 'standardna dostava');
                $ukupno_s_popustom = $ukupno - $popust + $trosak_dostave;
                $pouzece_dostupno = pouzece_dopusteno($conn, $user_id, $ukupno);
                ?>

                <div class="coupon-box">
                    <?php if ($primijenjeni_kupon): ?>
                        <p>Primijenjen kupon <strong><?php echo htmlspecialchars($primijenjeni_kupon['code']); ?></strong> (−<?php echo $popust; ?>€)
                            <a href="ukloni_kupon.php" class="delete-cart">Ukloni</a>
                        </p>
                    <?php else: ?>
                        <form action="primijeni_kupon.php" method="POST" class="coupon-form">
                            <?php echo csrf_polje(); ?>
                            <input type="text" name="kod_kupona" placeholder="Kod kupona">
                            <button type="submit" class="btn btn-login">Primijeni kupon</button>
                        </form>
                        <?php if (!empty($_SESSION['kupon_greska'])): ?>
                            <p class="message"><?php echo htmlspecialchars($_SESSION['kupon_greska']); unset($_SESSION['kupon_greska']); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <h3 class="cart-total-price">
                    <?php if ($popust > 0): ?>
                        Međuzbroj: <?php echo $ukupno; ?> € · Popust: −<?php echo $popust; ?> €<br>
                    <?php endif; ?>
                    Dostava: <span id="prikaz_dostave"><?php echo $trosak_dostave > 0 ? $trosak_dostave . ' €' : 'besplatno'; ?></span><br>
                    Ukupno: <span id="prikaz_ukupno"><?php echo $ukupno_s_popustom; ?> €</span>
                </h3>
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

            <?php if (mysqli_num_rows($spremljene_adrese) > 0): ?>
                <label>Odaberi spremljenu adresu:</label>
                <select id="odabir_adrese" onchange="popuniAdresu(this.value)">
                    <option value="">— Unesi ručno —</option>
                    <?php while ($a = mysqli_fetch_assoc($spremljene_adrese)): ?>
                        <option value="<?php echo $a['id']; ?>">
                            <?php echo htmlspecialchars($a['ime'] . ' ' . $a['prezime'] . ' — ' . $a['adresa'] . ', ' . $a['grad']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <br><br>
            <?php endif; ?>

            <form action="final_order.php" method="POST" id="forma_dostave">
                <?php echo csrf_polje(); ?>

                <label>Način dostave:</label>
                <select name="nacin_dostave" id="polje_nacin_dostave" onchange="osvjeziDostavu()">
                    <option value="standardna dostava">Standardna dostava (5 €, besplatno iznad 100 €)</option>
                    <option value="ekspresna dostava">Ekspresna dostava (12 €)</option>
                    <option value="osobno preuzimanje">Osobno preuzimanje (besplatno)</option>
                </select>

                <input type="text" name="ime" id="polje_ime" placeholder="Ime" required>
                <input type="text" name="prezime" id="polje_prezime" placeholder="Prezime" required>
                <input type="text" name="adresa" id="polje_adresa" placeholder="Adresa i kućni broj" required>
                <input type="text" name="grad" id="polje_grad" placeholder="Grad i poštanski broj" required>
                <input type="email" name="email" id="polje_email" placeholder="E-mail" required>
                <input type="text" name="telefon" id="polje_telefon" placeholder="Telefon" required>

                <label>Način plaćanja:</label>
                <select name="nacin_placanja">
                    <?php if (!$pouzece_dostupno): ?>
                        <option value="Kartica" selected>Kartica</option>
                    <?php else: ?>
                        <option value="Pouzećem">Pouzećem (gotovina pri preuzimanju)</option>
                        <option value="Kartica">Kartica</option>
                    <?php endif; ?>
                </select>
                <?php if (!$pouzece_dostupno): ?>
                    <p class="payment-hint">Plaćanje pouzećem nije dostupno za ovu narudžbu (iznos preko <?php echo MAKS_IZNOS_ZA_POUZECE; ?> € ili sadrži proizvod koji zahtijeva kartično plaćanje) — dostupno je samo kartično plaćanje.</p>
                <?php endif; ?>

                <button type="submit" class="btn btn-order">Naruči</button>
            </form>

            <script>
            var spremljeneAdrese = <?php
                mysqli_data_seek($spremljene_adrese, 0);
                $niz = [];
                while ($a = mysqli_fetch_assoc($spremljene_adrese)) {
                    $niz[$a['id']] = $a;
                }
                echo json_encode($niz, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
            ?>;

            var medjuzbroj = <?php echo (float)$ukupno; ?>;
            var iznosPopusta = <?php echo (float)$popust; ?>;

            function osvjeziDostavu() {
                var nacin = document.getElementById('polje_nacin_dostave').value;
                var trosakDostave = 0;

                if (nacin === 'ekspresna dostava') {
                    trosakDostave = 12;
                } else if (nacin === 'standardna dostava') {
                    trosakDostave = (medjuzbroj >= 100) ? 0 : 5;
                }

                var ukupnoSDostavom = medjuzbroj - iznosPopusta + trosakDostave;

                document.getElementById('prikaz_dostave').textContent = trosakDostave > 0 ? (trosakDostave + ' €') : 'besplatno';
                document.getElementById('prikaz_ukupno').textContent = ukupnoSDostavom.toFixed(2) + ' €';

                var obavezno = (nacin !== 'osobno preuzimanje');
                document.getElementById('polje_adresa').required = obavezno;
                document.getElementById('polje_grad').required = obavezno;
                document.getElementById('polje_adresa').disabled = !obavezno;
                document.getElementById('polje_grad').disabled = !obavezno;
            }

            function popuniAdresu(id) {
                if (!id || !spremljeneAdrese[id]) {
                    return;
                }
                var a = spremljeneAdrese[id];
                document.getElementById('polje_ime').value = a.ime;
                document.getElementById('polje_prezime').value = a.prezime;
                document.getElementById('polje_adresa').value = a.adresa;
                document.getElementById('polje_grad').value = a.grad;
                document.getElementById('polje_email').value = a.email;
                document.getElementById('polje_telefon').value = a.telefon;
            }

            osvjeziDostavu();
            </script>
        </div>
        <?php endif; ?>
    </section>
</body>
</html>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$broj_proizvoda = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM products"))['n'];
$broj_narudzbi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM orders"))['n'];
$broj_korisnika = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM users WHERE role = 'user'"))['n'];
$broj_male_zalihe = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM products WHERE quantity <= low_stock_threshold"))['n'];
$broj_neuspjelih_placanja = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM orders WHERE payment_status = 'neuspješno plaćanje'"))['n'];
$broj_novih_narudzbi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM orders WHERE order_status = 'kreirana'"))['n'];

$naslov_stranice = "Nadzorna ploča";
include "admin_header.php";
?>

<h2 class="section-title">Nadzorna ploča</h2>

<div class="admin-stats">
    <a href="admin_proizvodi.php" class="admin-stat-card">
        <span class="admin-stat-broj"><?php echo $broj_proizvoda; ?></span>
        <span>Proizvoda u katalogu</span>
    </a>
    <a href="admin_narudzbe.php" class="admin-stat-card">
        <span class="admin-stat-broj"><?php echo $broj_narudzbi; ?></span>
        <span>Ukupno narudžbi</span>
    </a>
    <a href="admin_narudzbe.php?status=kreirana" class="admin-stat-card admin-stat-warn">
        <span class="admin-stat-broj"><?php echo $broj_novih_narudzbi; ?></span>
        <span>Nove narudžbe na čekanju</span>
    </a>
    <a href="admin_korisnici.php" class="admin-stat-card">
        <span class="admin-stat-broj"><?php echo $broj_korisnika; ?></span>
        <span>Registriranih korisnika</span>
    </a>
    <a href="admin_mala_zaliha.php" class="admin-stat-card admin-stat-warn">
        <span class="admin-stat-broj"><?php echo $broj_male_zalihe; ?></span>
        <span>Proizvoda s malom zalihom</span>
    </a>
    <a href="admin_neuspjela_placanja.php" class="admin-stat-card admin-stat-danger">
        <span class="admin-stat-broj"><?php echo $broj_neuspjelih_placanja; ?></span>
        <span>Neuspjelih plaćanja</span>
    </a>
</div>

<?php include "admin_footer.php"; ?>

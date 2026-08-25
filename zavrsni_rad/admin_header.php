<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($naslov_stranice) ? htmlspecialchars($naslov_stranice) . ' - ' : ''; ?>Admin Panel</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <div class="logo">PC Shop</div>
    <nav>
        <a href="index.php">Početna</a>
        <a href="admin.php" class="active admin_panel">Admin Panel</a>
    </nav>
    <div class="header-buttons">
        <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
    </div>
</header>

<section class="admin-subnav">
    <a href="admin.php">Nadzorna ploča</a>
    <a href="admin_proizvodi.php">Proizvodi</a>
    <a href="admin_kategorije.php">Kategorije</a>
    <a href="admin_korisnici.php">Korisnici</a>
    <a href="admin_narudzbe.php">Narudžbe</a>
    <a href="admin_neuspjela_placanja.php">Neuspjela plaćanja</a>
    <a href="admin_kuponi.php">Kuponi</a>
    <a href="admin_poruke.php">Poruke</a>
    <a href="admin_mala_zaliha.php">Mala zaliha</a>
    <a href="admin_dnevnik.php">Dnevnik promjena</a>
</section>

<section class="container admin-container">

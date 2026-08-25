<?php
include_once "helpers.php";
pokreni_sesiju();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
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
            <a href="kontakt.php" class="active">Kontakt</a>
            
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

    <section class="page-banner">
        <div>
            <h1>Kontakt</h1>
            <p>Kontaktiraj nas za dodatne informacije.</p>
        </div>
    </section>

    <section class="container">

        <h2 class="section-title">Pošalji poruku</h2>

        <div class="contact-box">

            <?php if (isset($_GET['poslano'])): ?>
                <p class="message message-success">Poruka je poslana. Javit ćemo Vam se u najkraćem roku.</p>
            <?php elseif (isset($_GET['greska'])): ?>
                <p class="message">Provjerite podatke — sva polja su obavezna, a email mora biti ispravnog formata.</p>
            <?php endif; ?>

            <form action="kontakt_posalji.php" method="POST">
                <?php echo csrf_polje(); ?>

                <input type="text" name="ime" placeholder="Ime i prezime" required>

                <input type="email" name="email" placeholder="Email adresa" required>

                <textarea name="poruka" placeholder="Vaša poruka" required></textarea>

                <button type="submit" class="btn">Pošalji</button>

            </form>

        </div>

    </section>

    <footer>
        <p>© 2026 PC Shop</p>
    </footer>

</body>
</html>
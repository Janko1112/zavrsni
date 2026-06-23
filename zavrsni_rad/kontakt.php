<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <link rel="stylesheet" href="style.css?v=">
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
                <span>Dobro došli, <?php echo $_SESSION['username']; ?></span>
                <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
            <?php else: ?>

                <button class="btn btn-login" onclick="window.location.href='login.php';">Prijava</button>
            <?php endif; ?>
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

            <form>

                <input type="text" placeholder="Ime i prezime">

                <input type="email" placeholder="Email adresa">

                <textarea placeholder="Vaša poruka"></textarea>

                <button class="btn">Pošalji</button>

            </form>

        </div>

    </section>

    <footer>
        <p>© 2026 PC Shop</p>
    </footer>

</body>
</html>
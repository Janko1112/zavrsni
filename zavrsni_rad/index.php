<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Shop</title>
    <link rel="stylesheet" href="style.css?v=">
</head>
<body>

    <header>
        <div class="logo">PC SHOP</div>

        <nav>
            <a href="index.php" class="active">Početna</a>
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
                <span>Dobro došli, <?php echo $_SESSION['username']; ?></span>
                <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
            <?php else: ?>

                <button class="btn btn-login" onclick="window.location.href='login.php';">Prijava</button>
            <?php endif; ?>
            <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
        </div>
    </header>

    <section class="hero">
        <div class="hero-text">
            <h1>Najbolje <span>računalne komponente</span> na jednom mjestu</h1>
            <p>
                Procesori, grafičke kartice, RAM, SSD i gaming oprema
                po najboljim cijenama.
            </p>
            <a href="#popular" class="hero-btn">Pogledaj ponudu</a>
        </div>

        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=1200&auto=format&fit=crop" alt="">
        </div>
    </section>

    <h2 class="section-title">Kategorije</h2>
    <section class="categories">
        <div class="category-card">
            <h3>Grafičke kartice</h3>
        </div>
        <div class="category-card">
            <h3>Procesori</h3>
        </div>
        <div class="category-card">
            <h3>Matične ploče</h3>
        </div>
        <div class="category-card">
            <h3>Gaming oprema</h3>
        </div>
    </section>

    <h2 class="section-title" id="popular">Popularni proizvodi</h2>
    <section class="products">

        <div class="product-card">
            <img src="https://images.unsplash.com/photo-1591488320449-011701bb6704?q=80&w=1200&auto=format&fit=crop" alt="">
            <div class="product-info">
                <h3>RTX 4070</h3>
                <p>Gaming grafička kartica nove generacije.</p>
                <div class="price">799€</div>
                
                <?php if(isset($_SESSION['username'])): ?>
                    <button class="buy-btn" onclick="window.location.href='komponente.php';">Pogledaj</button>
                <?php else: ?>
                    <button class="buy-btn" onclick="alert('Potrebna je prijava! Morate se ulogirati kako biste dodali artikl u košaricu.'); window.location.href='login.php?info=auth_required';">Dodaj u košaricu</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-card">
            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop" alt="">
            <div class="product-info">
                <h3>Ryzen 7 7800X</h3>
                <p>Odličan procesor za gaming i multitasking.</p>
                <div class="price">449€</div>
                
                <?php if(isset($_SESSION['username'])): ?>
                    <button class="buy-btn" onclick="window.location.href='komponente.php';">Pogledaj</button>
                <?php else: ?>
                    <button class="buy-btn" onclick="alert('Potrebna je prijava! Morate se ulogirati kako biste dodali artikl u košaricu.'); window.location.href='login.php?info=auth_required';">Dodaj u košaricu</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-card">
            <img src="https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?q=80&w=1200&auto=format&fit=crop" alt="">
            <div class="product-info">
                <h3>DDR5 RAM 32GB</h3>
                <p>Brza memorija za moderne konfiguracije.</p>
                <div class="price">129€</div>
                
                <?php if(isset($_SESSION['username'])): ?>
                    <button class="buy-btn" onclick="window.location.href='komponente.php';">Pogledaj</button>
                <?php else: ?>
                    <button class="buy-btn" onclick="alert('Potrebna je prijava! Morate se ulogirati kako biste dodali artikl u košaricu.'); window.location.href='login.php?info=auth_required';">Dodaj u košaricu</button>
                <?php endif; ?>
            </div>
        </div>

    </section>

    <footer>
        <p>© 2026 PC SHOP | Sva prava pridržana</p>
    </footer>

</body>
</html>

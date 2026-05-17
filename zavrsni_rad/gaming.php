<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming</title>
    <link rel="stylesheet" href="style.css?v=">
</head>
<body>

<?php
include "db.php";
?>
    <header>
        <div class="logo">PC SHOP</div>

        <nav>
            <a href="index.php">Početna</a>
            <a href="komponente.php">Komponente</a>
            <a href="gaming.php" class="active">Gaming</a>
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

    <section class="page-banner">
        <div>
            <h1>Gaming Oprema</h1>
            <p>Gaming periferija i oprema za maksimalne performanse.</p>
        </div>
    </section>

    <section class="container">

        <h2 class="section-title">Gaming proizvodi</h2>

        <div class="cards">

        <?php
        $sql = "SELECT * FROM products WHERE category='gaming' ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <div class="price"><?php echo $row['price']; ?>€</div>
                    <!-- Vodi na istu univerzalnu stranicu s detaljima -->
                    <a href="article.php?id=<?php echo $row['id']; ?>" class="btn">Detalji</a>
                </div>
            </div>
            <?php
        }
        ?>

        </div>

    </section>

    <footer>
        <p>© 2026 PC SHOP</p>
    </footer>

</body>
</html>
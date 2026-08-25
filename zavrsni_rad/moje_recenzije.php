<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT r.id, r.rating, r.comment, r.date_added, p.id AS product_id, p.name AS product_name FROM reviews r JOIN products p ON p.id = r.product_id WHERE r.user_id = ? ORDER BY r.date_added DESC");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$recenzije = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje recenzije</title>
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
        <a href="kontakt.php">Kontakt</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="admin.php" class="admin_panel">Admin Panel</a>
        <?php endif; ?>
    </nav>
    <div class="header-buttons">
        <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
        <a href="profil.php" class="btn btn-login">Moj profil</a>
        <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
        <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
    </div>
</header>

<section class="page-banner">
    <div>
        <h1>Moje recenzije</h1>
    </div>
</section>

<section class="container">

    <?php if (mysqli_num_rows($recenzije) === 0): ?>
        <p>Još niste ostavili nijednu recenziju.</p>
    <?php endif; ?>

    <?php while ($r = mysqli_fetch_assoc($recenzije)): ?>
        <div class="review">
            <strong><a href="article.php?id=<?php echo $r['product_id']; ?>"><?php echo htmlspecialchars($r['product_name']); ?></a></strong>
            <span class="rating"><?php echo zvjezdice($r['rating']); ?></span>
            <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
            <small><?php echo $r['date_added']; ?></small>
        </div>
    <?php endwhile; ?>

</section>

</body>
</html>

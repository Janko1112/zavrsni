<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Artikl nije pronađen ili ID nije proslijeđen.");
}

$artikl_id = (int)$_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?");
mysqli_stmt_bind_param($stmt, 'i', $artikl_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    die("Traženi artikl ne postoji u bazi podataka.");
}

$slike = [$row['image']];
$stmt = mysqli_prepare($conn, "SELECT image_url FROM product_images WHERE product_id = ? ORDER BY sort_order");
mysqli_stmt_bind_param($stmt, 'i', $artikl_id);
mysqli_stmt_execute($stmt);
$res_slike = mysqli_stmt_get_result($stmt);
while ($s = mysqli_fetch_assoc($res_slike)) {
    $slike[] = $s['image_url'];
}

$stmt = mysqli_prepare($conn, "SELECT spec_name, spec_value FROM product_specs WHERE product_id = ? ORDER BY sort_order");
mysqli_stmt_bind_param($stmt, 'i', $artikl_id);
mysqli_stmt_execute($stmt);
$specifikacije = mysqli_stmt_get_result($stmt);

$stmt = mysqli_prepare($conn, "SELECT id, name, price, image FROM products WHERE category_id = (SELECT category_id FROM products WHERE id = ?) AND id <> ? ORDER BY RAND() LIMIT 4");
mysqli_stmt_bind_param($stmt, 'ii', $artikl_id, $artikl_id);
mysqli_stmt_execute($stmt);
$slicni = mysqli_stmt_get_result($stmt);

$ocjena = ocjena_proizvoda($conn, $artikl_id);

$stmt = mysqli_prepare($conn, "SELECT r.rating, r.comment, r.date_added, u.username FROM reviews r JOIN users u ON u.id = r.user_id WHERE r.product_id = ? ORDER BY r.date_added DESC");
mysqli_stmt_bind_param($stmt, 'i', $artikl_id);
mysqli_stmt_execute($stmt);
$recenzije = mysqli_stmt_get_result($stmt);

$na_listi_zelja = isset($_SESSION['user_id']) ? je_na_listi_zelja($conn, $_SESSION['user_id'], $artikl_id) : false;
$u_usporedbi = isset($_SESSION['compare']) && in_array($artikl_id, $_SESSION['compare']);
$trenutna_stranica = urlencode('article.php?id=' . $artikl_id);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['name']); ?> - Detalji</title>
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
        <?php if (isset($_SESSION['username'])): ?>
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

<section class="product-details">
    <div class="product-wrapper">

        <div class="gallery">
            <img id="glavna-slika" src="<?php echo htmlspecialchars($slike[0]); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <?php if (count($slike) > 1): ?>
                <div class="thumbnails">
                    <?php foreach ($slike as $slika): ?>
                        <img src="<?php echo htmlspecialchars($slika); ?>" class="thumbnail" onclick="document.getElementById('glavna-slika').src=this.src">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-info-details">
            <?php echo dostupnost_oznaka($row['quantity'], $row['low_stock_threshold']); ?>
            <h1><?php echo htmlspecialchars($row['name']); ?></h1>
            <p>Proizvođač: <?php echo htmlspecialchars($row['manufacturer'] ?? ''); ?></p>
            <p>Kategorija: <?php echo htmlspecialchars($row['category_name']); ?></p>

            <?php if ($ocjena['broj'] > 0): ?>
                <p class="rating"><?php echo zvjezdice($ocjena['prosjek']); ?> (<?php echo round($ocjena['prosjek'], 1); ?>/5, <?php echo $ocjena['broj']; ?> recenzija)</p>
            <?php else: ?>
                <p class="rating">Još nema recenzija</p>
            <?php endif; ?>

            <div class="price"><?php echo htmlspecialchars($row['price']); ?> €</div>

            <div class="specs">
                <h3>Opis:</h3>
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            </div>

            <?php if (mysqli_num_rows($specifikacije) > 0): ?>
                <div class="specs">
                    <h3>Tehničke specifikacije:</h3>
                    <ul>
                        <?php while ($spec = mysqli_fetch_assoc($specifikacije)): ?>
                            <li><?php echo htmlspecialchars($spec['spec_name']); ?>: <?php echo htmlspecialchars($spec['spec_value']); ?></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="add_to_cart.php" method="POST">
                <?php echo csrf_polje(); ?>
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

                <div class="quantity-box">
                    <label>Količina (najviše <?php echo min($row['quantity'], $row['max_per_order']); ?> kom):</label>
                    <div class="quantity-controls">
                        <button type="button" onclick="decreaseQuantity()">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo min($row['quantity'], $row['max_per_order']); ?>" readonly>
                        <button type="button" onclick="increaseQuantity()">+</button>
                    </div>
                </div>

                <?php if (isset($_SESSION['username'])): ?>
                    <button type="submit" class="btn add-cart-btn">Dodaj u košaricu</button>
                <?php else: ?>
                    <button type="button" class="btn add-cart-btn" onclick="alert('Potrebna je prijava! Morate se ulogirati kako biste dodali artikl u košaricu.'); window.location.href='login.php?info=auth_required';">
                        Dodaj u košaricu
                    </button>
                <?php endif; ?>
            </form>

            <div class="secondary-actions">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="wishlist_toggle.php?id=<?php echo $row['id']; ?>&redirect=<?php echo $trenutna_stranica; ?>" class="btn btn-login">
                        <?php echo $na_listi_zelja ? 'Ukloni s liste želja' : 'Dodaj na listu želja'; ?>
                    </a>
                <?php else: ?>
                    <a href="login.php?info=auth_required" class="btn btn-login">Dodaj na listu želja</a>
                <?php endif; ?>

                <a href="compare_toggle.php?id=<?php echo $row['id']; ?>&redirect=<?php echo $trenutna_stranica; ?>" class="btn btn-login">
                    <?php echo $u_usporedbi ? 'Ukloni iz usporedbe' : 'Dodaj u usporedbu'; ?>
                </a>
            </div>

        </div>

    </div>

    <?php if (mysqli_num_rows($slicni) > 0): ?>
        <h2 class="section-title">Slični proizvodi</h2>
        <div class="cards">
            <?php while ($s = mysqli_fetch_assoc($slicni)): ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars($s['name']); ?>">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($s['name']); ?></h3>
                        <div class="price"><?php echo htmlspecialchars($s['price']); ?>€</div>
                        <a href="article.php?id=<?php echo $s['id']; ?>" class="btn">Detalji</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <h2 class="section-title">Recenzije</h2>
    <div class="reviews">
        <?php if (mysqli_num_rows($recenzije) === 0): ?>
            <p>Ovaj proizvod još nema recenzija.</p>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($recenzije)): ?>
            <div class="review">
                <strong><?php echo htmlspecialchars($r['username']); ?></strong>
                <span class="rating"><?php echo zvjezdice($r['rating']); ?></span>
                <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
                <small><?php echo $r['date_added']; ?></small>
            </div>
        <?php endwhile; ?>

        <?php if (isset($_SESSION['username'])): ?>
            <form action="add_review.php" method="POST" class="review-form">
                <?php echo csrf_polje(); ?>
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <label>Ocjena:</label>
                <select name="rating" required>
                    <option value="5">5 - Odlično</option>
                    <option value="4">4 - Vrlo dobro</option>
                    <option value="3">3 - Dobro</option>
                    <option value="2">2 - Loše</option>
                    <option value="1">1 - Vrlo loše</option>
                </select>
                <textarea name="comment" placeholder="Vaš komentar..." required></textarea>
                <button type="submit" class="btn">Objavi recenziju</button>
            </form>
        <?php else: ?>
            <p><a href="login.php?info=auth_required">Prijavite se</a> kako biste ostavili recenziju.</p>
        <?php endif; ?>
    </div>

</section>

<script src="js/quantity.js"></script>
</body>
</html>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategorija = isset($_GET['kategorija']) ? trim($_GET['kategorija']) : '';
$proizvodjac = isset($_GET['proizvodjac']) ? trim($_GET['proizvodjac']) : '';
$cijena_min = isset($_GET['cijena_min']) && $_GET['cijena_min'] !== '' ? (float)$_GET['cijena_min'] : null;
$cijena_max = isset($_GET['cijena_max']) && $_GET['cijena_max'] !== '' ? (float)$_GET['cijena_max'] : null;
$dostupnost = isset($_GET['dostupnost']) ? trim($_GET['dostupnost']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';

$where = [];
$params = [];
$types = '';

if ($q !== '') {
    $where[] = 'p.name LIKE ?';
    $params[] = '%' . $q . '%';
    $types .= 's';
}
if ($kategorija !== '') {
    $where[] = 'c.slug = ?';
    $params[] = $kategorija;
    $types .= 's';
}
if ($proizvodjac !== '') {
    $where[] = 'p.manufacturer = ?';
    $params[] = $proizvodjac;
    $types .= 's';
}
if ($cijena_min !== null) {
    $where[] = 'p.price >= ?';
    $params[] = $cijena_min;
    $types .= 'd';
}
if ($cijena_max !== null) {
    $where[] = 'p.price <= ?';
    $params[] = $cijena_max;
    $types .= 'd';
}
if ($dostupnost === 'available') {
    $where[] = 'p.quantity > p.low_stock_threshold';
} elseif ($dostupnost === 'low') {
    $where[] = 'p.quantity > 0 AND p.quantity <= p.low_stock_threshold';
} elseif ($dostupnost === 'out') {
    $where[] = 'p.quantity = 0';
}

$sort_map = [
    'newest' => 'p.created_at DESC',
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'name_asc' => 'p.name ASC',
    'name_desc' => 'p.name DESC',
];
$order_by = isset($sort_map[$sort]) ? $sort_map[$sort] : $sort_map['newest'];

$sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM products p JOIN categories c ON c.id = p.category_id";
if (count($where) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY ' . $order_by;

$stmt = mysqli_prepare($conn, $sql);
if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$kategorije = mysqli_query($conn, "SELECT slug, name FROM categories ORDER BY name");
$proizvodjaci = mysqli_query($conn, "SELECT DISTINCT manufacturer FROM products WHERE manufacturer IS NOT NULL AND manufacturer <> '' ORDER BY manufacturer");

$naslov_kategorije = 'Katalog proizvoda';
if ($kategorija !== '') {
    $stmt2 = mysqli_prepare($conn, "SELECT name FROM categories WHERE slug = ?");
    mysqli_stmt_bind_param($stmt2, 's', $kategorija);
    mysqli_stmt_execute($stmt2);
    $res2 = mysqli_stmt_get_result($stmt2);
    if ($row2 = mysqli_fetch_assoc($res2)) {
        $naslov_kategorije = $row2['name'];
    }
    mysqli_stmt_close($stmt2);
}

$broj_za_usporedbu = isset($_SESSION['compare']) ? count($_SESSION['compare']) : 0;
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($naslov_kategorije); ?></title>
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
            <a href="compare.php" class="btn btn-login">Usporedba (<?php echo $broj_za_usporedbu; ?>)</a>
            <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
        </div>
    </header>

    <section class="page-banner">
        <div>
            <h1><?php echo htmlspecialchars($naslov_kategorije); ?></h1>
            <p>Pretražite, filtrirajte i usporedite proizvode.</p>
        </div>
    </section>

    <section class="container">

        <form method="GET" class="filter-bar">
            <input type="text" name="q" placeholder="Pretraži po nazivu..." value="<?php echo htmlspecialchars($q); ?>">

            <select name="kategorija">
                <option value="">Sve kategorije</option>
                <?php while ($k = mysqli_fetch_assoc($kategorije)): ?>
                    <option value="<?php echo htmlspecialchars($k['slug']); ?>" <?php echo $kategorija === $k['slug'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($k['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="proizvodjac">
                <option value="">Svi proizvođači</option>
                <?php while ($p = mysqli_fetch_assoc($proizvodjaci)): ?>
                    <option value="<?php echo htmlspecialchars($p['manufacturer']); ?>" <?php echo $proizvodjac === $p['manufacturer'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p['manufacturer']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="number" step="0.01" name="cijena_min" placeholder="Cijena od" value="<?php echo $cijena_min !== null ? htmlspecialchars($cijena_min) : ''; ?>">
            <input type="number" step="0.01" name="cijena_max" placeholder="Cijena do" value="<?php echo $cijena_max !== null ? htmlspecialchars($cijena_max) : ''; ?>">

            <select name="dostupnost">
                <option value="">Sva dostupnost</option>
                <option value="available" <?php echo $dostupnost === 'available' ? 'selected' : ''; ?>>Dostupno</option>
                <option value="low" <?php echo $dostupnost === 'low' ? 'selected' : ''; ?>>Mala količina</option>
                <option value="out" <?php echo $dostupnost === 'out' ? 'selected' : ''; ?>>Nedostupno</option>
            </select>

            <select name="sort">
                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Najnovije</option>
                <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Cijena rastuće</option>
                <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Cijena padajuće</option>
                <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Naziv A-Z</option>
                <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Naziv Z-A</option>
            </select>

            <button type="submit" class="btn">Primijeni</button>
            <a href="katalog.php" class="btn btn-login">Poništi</a>
        </form>

        <div class="cards">

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="card-content">
                        <?php echo dostupnost_oznaka($row['quantity'], $row['low_stock_threshold']); ?>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['manufacturer'] ?? ''); ?> · <?php echo htmlspecialchars($row['category_name']); ?></p>
                        <div class="price"><?php echo htmlspecialchars($row['price']); ?>€</div>
                        <a href="article.php?id=<?php echo $row['id']; ?>" class="btn">Detalji</a>

                        <?php if ($row['quantity'] > 0): ?>
                            <form action="add_to_cart.php" method="POST" class="card-cart-form">
                                <?php echo csrf_polje(); ?>
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <?php if (isset($_SESSION['username'])): ?>
                                    <button type="submit" class="btn add-cart-btn">Dodaj u košaricu</button>
                                <?php else: ?>
                                    <button type="button" class="btn add-cart-btn" onclick="alert('Potrebna je prijava! Morate se ulogirati kako biste dodali artikl u košaricu.'); window.location.href='login.php?info=auth_required';">Dodaj u košaricu</button>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>

                        <a href="compare_toggle.php?id=<?php echo $row['id']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-login">Usporedi</a>
                    </div>
                </div>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($result) === 0): ?>
                <p>Nema proizvoda koji odgovaraju odabranim filterima.</p>
            <?php endif; ?>

        </div>

    </section>

    <footer>
        <p>© 2026 PC Shop | Sva prava pridržana</p>
    </footer>

    <script>
    document.querySelectorAll('.card-cart-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            var originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Dodavanje...';

            var podaci = new FormData(form);
            podaci.append('ajax', '1');

            fetch('add_to_cart.php', { method: 'POST', body: podaci })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.ok) {
                        btn.textContent = 'Dodano ✓';
                    } else {
                        alert(data.message || 'Greška prilikom dodavanja u košaricu.');
                        btn.textContent = originalText;
                    }
                    btn.disabled = false;
                    setTimeout(function () { btn.textContent = originalText; }, 1500);
                })
                .catch(function () {
                    alert('Greška prilikom dodavanja u košaricu.');
                    btn.textContent = originalText;
                    btn.disabled = false;
                });
        });
    });
    </script>

</body>
</html>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

$ids = isset($_SESSION['compare']) ? $_SESSION['compare'] : [];
$proizvodi = [];
$sve_specifikacije = [];

if (count($ids) > 0) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = mysqli_prepare($conn, "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id IN ($placeholders)");
    mysqli_stmt_bind_param($stmt, $types, ...$ids);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $stmt2 = mysqli_prepare($conn, "SELECT spec_name, spec_value FROM product_specs WHERE product_id = ? ORDER BY sort_order");
        mysqli_stmt_bind_param($stmt2, 'i', $row['id']);
        mysqli_stmt_execute($stmt2);
        $res2 = mysqli_stmt_get_result($stmt2);
        $specs = [];
        while ($s = mysqli_fetch_assoc($res2)) {
            $specs[$s['spec_name']] = $s['spec_value'];
            $sve_specifikacije[$s['spec_name']] = true;
        }
        $row['specs'] = $specs;
        $proizvodi[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usporedba proizvoda</title>
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
            <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
            <a href="profil.php" class="btn btn-login">Moj profil</a>
            <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
        <?php else: ?>
            <button class="btn btn-login" onclick="window.location.href='login.php';">Prijava</button>
        <?php endif; ?>
        <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
    </div>
</header>

<section class="page-banner">
    <div>
        <h1>Usporedba proizvoda</h1>
    </div>
</section>

<section class="container">

    <?php if (count($proizvodi) === 0): ?>
        <p>Niste odabrali nijedan proizvod za usporedbu. Dodajte proizvode klikom na "Usporedi" u katalogu.</p>
    <?php else: ?>

        <div class="compare-table-wrapper">
            <table class="compare-table">
                <tr>
                    <th>Proizvod</th>
                    <?php foreach ($proizvodi as $p): ?>
                        <td>
                            <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="compare-img">
                            <div><?php echo htmlspecialchars($p['name']); ?></div>
                            <a href="compare_toggle.php?id=<?php echo $p['id']; ?>&redirect=<?php echo urlencode('compare.php'); ?>" class="btn btn-login">Ukloni</a>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <th>Proizvođač</th>
                    <?php foreach ($proizvodi as $p): ?>
                        <td><?php echo htmlspecialchars($p['manufacturer'] ?? ''); ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <th>Kategorija</th>
                    <?php foreach ($proizvodi as $p): ?>
                        <td><?php echo htmlspecialchars($p['category_name']); ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <th>Cijena</th>
                    <?php foreach ($proizvodi as $p): ?>
                        <td><?php echo htmlspecialchars($p['price']); ?>€</td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <th>Dostupnost</th>
                    <?php foreach ($proizvodi as $p): ?>
                        <td><?php echo dostupnost_oznaka($p['quantity'], $p['low_stock_threshold']); ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php foreach (array_keys($sve_specifikacije) as $naziv_spec): ?>
                    <tr>
                        <th><?php echo htmlspecialchars($naziv_spec); ?></th>
                        <?php foreach ($proizvodi as $p): ?>
                            <td><?php echo isset($p['specs'][$naziv_spec]) ? htmlspecialchars($p['specs'][$naziv_spec]) : '-'; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <a href="compare_clear.php" class="btn btn-login">Isprazni usporedbu</a>

    <?php endif; ?>

</section>

</body>
</html>

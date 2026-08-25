<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$stmt = mysqli_prepare($conn, "SELECT p.id, p.name, p.manufacturer, p.price, p.quantity, p.low_stock_threshold, p.max_per_order, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC");
mysqli_stmt_execute($stmt);
$proizvodi = mysqli_stmt_get_result($stmt);

$naslov_stranice = "Proizvodi";
include "admin_header.php";
?>

<div class="admin-toolbar">
    <h2 class="section-title">Proizvodi</h2>
    <a href="admin_proizvod_dodaj.php" class="btn">+ Dodaj proizvod</a>
</div>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Naziv</th>
        <th>Proizvođač</th>
        <th>Kategorija</th>
        <th>Cijena</th>
        <th>Zaliha</th>
        <th>Maks. po narudžbi</th>
        <th>Akcije</th>
    </tr>
    <?php while ($p = mysqli_fetch_assoc($proizvodi)): ?>
        <tr>
            <td>#<?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['manufacturer'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($p['category_name']); ?></td>
            <td><?php echo $p['price']; ?>€</td>
            <td><?php echo dostupnost_oznaka($p['quantity'], $p['low_stock_threshold']); ?> (<?php echo $p['quantity']; ?>)</td>
            <td><?php echo $p['max_per_order']; ?></td>
            <td>
                <a href="admin_proizvod_uredi.php?id=<?php echo $p['id']; ?>" class="btn btn-login">Uredi</a>
                <a href="admin_proizvod_obrisi.php?id=<?php echo $p['id']; ?>" class="delete-cart" onclick="return confirm('Sigurno obrisati ovaj proizvod?');">Obriši</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

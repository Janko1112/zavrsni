<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$proizvodi = mysqli_query($conn, "SELECT p.id, p.name, p.quantity, p.low_stock_threshold, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.quantity <= p.low_stock_threshold ORDER BY p.quantity ASC");

$naslov_stranice = "Mala zaliha";
include "admin_header.php";
?>

<h2 class="section-title">Proizvodi s malom zalihom</h2>

<?php if (mysqli_num_rows($proizvodi) === 0): ?>
    <p>Trenutno nema proizvoda s malom zalihom.</p>
<?php endif; ?>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Naziv</th>
        <th>Kategorija</th>
        <th>Zaliha</th>
        <th>Prag</th>
        <th>Akcije</th>
    </tr>
    <?php while ($p = mysqli_fetch_assoc($proizvodi)): ?>
        <tr>
            <td>#<?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['category_name']); ?></td>
            <td><?php echo dostupnost_oznaka($p['quantity'], $p['low_stock_threshold']); ?> (<?php echo $p['quantity']; ?>)</td>
            <td><?php echo $p['low_stock_threshold']; ?></td>
            <td><a href="admin_proizvod_uredi.php?id=<?php echo $p['id']; ?>" class="btn btn-login">Uredi</a></td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

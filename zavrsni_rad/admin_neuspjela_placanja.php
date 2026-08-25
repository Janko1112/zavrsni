<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$narudzbe = mysqli_query($conn, "SELECT o.id, o.created_at, o.total_amount, o.payment_method, u.username FROM orders o JOIN users u ON u.id = o.user_id WHERE o.payment_status = 'neuspješno plaćanje' ORDER BY o.created_at DESC");

$naslov_stranice = "Neuspjela plaćanja";
include "admin_header.php";
?>

<h2 class="section-title">Neuspjela plaćanja</h2>

<?php if (mysqli_num_rows($narudzbe) === 0): ?>
    <p>Trenutno nema neuspjelih plaćanja.</p>
<?php endif; ?>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>ID narudžbe</th>
        <th>Korisnik</th>
        <th>Datum</th>
        <th>Iznos</th>
        <th>Način plaćanja</th>
        <th>Akcije</th>
    </tr>
    <?php while ($o = mysqli_fetch_assoc($narudzbe)): ?>
        <tr>
            <td>#<?php echo $o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['username']); ?></td>
            <td><?php echo $o['created_at']; ?></td>
            <td><?php echo $o['total_amount']; ?>€</td>
            <td><?php echo htmlspecialchars($o['payment_method']); ?></td>
            <td><a href="admin_narudzba_detalji.php?id=<?php echo $o['id']; ?>" class="btn btn-login">Detalji</a></td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

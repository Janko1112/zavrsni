<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$log = mysqli_query($conn, "SELECT l.id, l.akcija, l.detalji, l.created_at, u.username FROM admin_log l JOIN users u ON u.id = l.admin_id ORDER BY l.created_at DESC LIMIT 300");

$naslov_stranice = "Dnevnik promjena";
include "admin_header.php";
?>

<h2 class="section-title">Administratorski dnevnik promjena</h2>

<?php if (mysqli_num_rows($log) === 0): ?>
    <p>Dnevnik je prazan.</p>
<?php endif; ?>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>Datum i vrijeme</th>
        <th>Administrator</th>
        <th>Akcija</th>
        <th>Detalji</th>
    </tr>
    <?php while ($l = mysqli_fetch_assoc($log)): ?>
        <tr>
            <td><?php echo $l['created_at']; ?></td>
            <td><?php echo htmlspecialchars($l['username']); ?></td>
            <td><?php echo htmlspecialchars($l['akcija']); ?></td>
            <td><?php echo htmlspecialchars($l['detalji']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

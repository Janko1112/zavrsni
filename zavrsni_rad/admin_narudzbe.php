<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

$sql = "SELECT o.id, o.created_at, o.total_amount, o.order_status, o.payment_status, u.username FROM orders o JOIN users u ON u.id = o.user_id";
if ($status_filter !== '') {
    $sql .= " WHERE o.order_status = ?";
}
$sql .= " ORDER BY o.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
if ($status_filter !== '') {
    mysqli_stmt_bind_param($stmt, 's', $status_filter);
}
mysqli_stmt_execute($stmt);
$narudzbe = mysqli_stmt_get_result($stmt);

$statusi = ['kreirana', 'čeka plaćanje', 'plaćena', 'u obradi', 'poslana', 'dostavljena', 'otkazana', 'povrat', 'neuspješno plaćanje'];

$naslov_stranice = "Narudžbe";
include "admin_header.php";
?>

<h2 class="section-title">Sve narudžbe</h2>

<form method="GET" class="filter-bar">
    <select name="status" onchange="this.form.submit()">
        <option value="">Svi statusi</option>
        <?php foreach ($statusi as $s): ?>
            <option value="<?php echo htmlspecialchars($s); ?>" <?php echo $status_filter === $s ? 'selected' : ''; ?>><?php echo htmlspecialchars($s); ?></option>
        <?php endforeach; ?>
    </select>
</form>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Korisnik</th>
        <th>Datum</th>
        <th>Iznos</th>
        <th>Status plaćanja</th>
        <th>Status narudžbe</th>
        <th>Akcije</th>
    </tr>
    <?php while ($o = mysqli_fetch_assoc($narudzbe)): ?>
        <tr>
            <td>#<?php echo $o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['username']); ?></td>
            <td><?php echo $o['created_at']; ?></td>
            <td><?php echo $o['total_amount']; ?>€</td>
            <td><?php echo htmlspecialchars($o['payment_status']); ?></td>
            <td><span class="order-status-badge"><?php echo htmlspecialchars($o['order_status']); ?></span></td>
            <td><a href="admin_narudzba_detalji.php?id=<?php echo $o['id']; ?>" class="btn btn-login">Detalji</a></td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

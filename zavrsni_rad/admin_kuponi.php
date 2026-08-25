<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

if (isset($_POST['dodaj_kupon'])) {
    csrf_provjeri();
    $code = strtoupper(trim($_POST['code']));
    $tip = $_POST['tip'];
    $vrijednost = (float)$_POST['vrijednost'];
    $vrijedi_od = !empty($_POST['vrijedi_od']) ? $_POST['vrijedi_od'] : null;
    $vrijedi_do = !empty($_POST['vrijedi_do']) ? $_POST['vrijedi_do'] : null;
    $max_koristenja = !empty($_POST['max_koristenja']) ? (int)$_POST['max_koristenja'] : null;

    $stmt = mysqli_prepare($conn, "INSERT INTO coupons (code, tip, vrijednost, vrijedi_od, vrijedi_do, max_koristenja) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssdssi', $code, $tip, $vrijednost, $vrijedi_od, $vrijedi_do, $max_koristenja);

    if (mysqli_stmt_execute($stmt)) {
        zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Dodao kupon', "$code ($tip, $vrijednost)");
    } else {
        $greska = "Kupon s tim kodom već postoji.";
    }
}

if (isset($_GET['prekini'])) {
    $kupon_id = (int)$_GET['prekini'];
    $stmt = mysqli_prepare($conn, "UPDATE coupons SET aktivan = NOT aktivan WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $kupon_id);
    mysqli_stmt_execute($stmt);
    zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Promijenio status kupona', "Kupon #$kupon_id");
    header("Location: admin_kuponi.php");
    exit();
}

if (isset($_GET['obrisi'])) {
    $kupon_id = (int)$_GET['obrisi'];
    $stmt = mysqli_prepare($conn, "DELETE FROM coupons WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $kupon_id);
    mysqli_stmt_execute($stmt);
    zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Obrisao kupon', "Kupon #$kupon_id");
    header("Location: admin_kuponi.php");
    exit();
}

$kuponi = mysqli_query($conn, "SELECT * FROM coupons ORDER BY created_at DESC");

$naslov_stranice = "Kuponi";
include "admin_header.php";
?>

<h2 class="section-title">Kuponi i popusti</h2>

<?php if (isset($greska)): ?>
    <p class="message"><?php echo htmlspecialchars($greska); ?></p>
<?php endif; ?>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>Kod</th>
        <th>Tip</th>
        <th>Vrijednost</th>
        <th>Vrijedi od</th>
        <th>Vrijedi do</th>
        <th>Iskorišteno</th>
        <th>Status</th>
        <th>Akcije</th>
    </tr>
    <?php while ($k = mysqli_fetch_assoc($kuponi)): ?>
        <tr>
            <td><?php echo htmlspecialchars($k['code']); ?></td>
            <td><?php echo $k['tip'] === 'postotak' ? 'Postotak' : 'Fiksni iznos'; ?></td>
            <td><?php echo $k['vrijednost']; ?><?php echo $k['tip'] === 'postotak' ? '%' : '€'; ?></td>
            <td><?php echo $k['vrijedi_od'] ?? '—'; ?></td>
            <td><?php echo $k['vrijedi_do'] ?? '—'; ?></td>
            <td><?php echo $k['broj_koristenja']; ?><?php echo $k['max_koristenja'] ? ' / ' . $k['max_koristenja'] : ''; ?></td>
            <td><?php echo $k['aktivan'] ? '<span class="stock-badge stock-ok">Aktivan</span>' : '<span class="stock-badge stock-out">Neaktivan</span>'; ?></td>
            <td>
                <a href="admin_kuponi.php?prekini=<?php echo $k['id']; ?>" class="btn btn-login"><?php echo $k['aktivan'] ? 'Deaktiviraj' : 'Aktiviraj'; ?></a>
                <a href="admin_kuponi.php?obrisi=<?php echo $k['id']; ?>" class="delete-cart" onclick="return confirm('Obrisati kupon?');">Obriši</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<div class="contact-box">
    <h3>Dodaj novi kupon</h3>
    <form method="POST">
        <?php echo csrf_polje(); ?>
        <input type="text" name="code" placeholder="Kod kupona (npr. LJETO25)" required>
        <select name="tip" required>
            <option value="postotak">Postotak (%)</option>
            <option value="iznos">Fiksni iznos (€)</option>
        </select>
        <input type="number" step="0.01" name="vrijednost" placeholder="Vrijednost popusta" required>
        <label>Vrijedi od (nije obavezno):</label>
        <input type="datetime-local" name="vrijedi_od">
        <label>Vrijedi do (nije obavezno):</label>
        <input type="datetime-local" name="vrijedi_do">
        <input type="number" name="max_koristenja" placeholder="Maksimalan broj korištenja (prazno = neograničeno)">
        <button type="submit" name="dodaj_kupon" class="btn">Dodaj kupon</button>
    </form>
</div>

<?php include "admin_footer.php"; ?>

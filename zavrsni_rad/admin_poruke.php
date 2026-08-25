<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$poruke = mysqli_query($conn, "SELECT * FROM poruke ORDER BY created_at DESC LIMIT 300");

$naslov_stranice = "Poruke";
include "admin_header.php";
?>

<h2 class="section-title">Poruke</h2>

<?php if (mysqli_num_rows($poruke) === 0): ?>
    <p>Nema zabilježenih poruka.</p>
<?php endif; ?>

<?php while ($p = mysqli_fetch_assoc($poruke)): ?>
    <div class="order-card">
        <div class="order-card-header">
            <div>
                <strong><?php echo htmlspecialchars($p['predmet']); ?></strong>
                <span class="order-date"><?php echo $p['created_at']; ?></span>
            </div>
            <span class="order-status-badge"><?php echo $p['tip'] === 'kontakt' ? 'Kontakt forma' : 'Potvrda narudžbe'; ?></span>
        </div>
        <p><strong>Od:</strong> <?php echo htmlspecialchars($p['ime'] ?? ''); ?> (<?php echo htmlspecialchars($p['email']); ?>)</p>
        <?php if ($p['order_id']): ?>
            <p><strong>Narudžba:</strong> <a href="admin_narudzba_detalji.php?id=<?php echo $p['order_id']; ?>">#<?php echo $p['order_id']; ?></a></p>
        <?php endif; ?>
        <pre><?php echo htmlspecialchars($p['sadrzaj']); ?></pre>
    </div>
<?php endwhile; ?>

<?php include "admin_footer.php"; ?>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$korisnici = mysqli_query($conn, "SELECT u.id, u.username, u.ime, u.prezime, u.email, u.role, COUNT(o.id) AS broj_narudzbi FROM users u LEFT JOIN orders o ON o.user_id = u.id GROUP BY u.id ORDER BY u.id DESC");

$naslov_stranice = "Korisnici";
include "admin_header.php";
?>

<h2 class="section-title">Korisnici</h2>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Korisničko ime</th>
        <th>Ime i prezime</th>
        <th>Email</th>
        <th>Uloga</th>
        <th>Broj narudžbi</th>
    </tr>
    <?php while ($u = mysqli_fetch_assoc($korisnici)): ?>
        <tr>
            <td>#<?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars(trim(($u['ime'] ?? '') . ' ' . ($u['prezime'] ?? ''))); ?></td>
            <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($u['role']); ?></td>
            <td><?php echo $u['broj_narudzbi']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

if (isset($_POST['dodaj_kategoriju'])) {
    csrf_provjeri();
    $naziv = trim($_POST['naziv']);
    $slug = trim($_POST['slug']);

    $stmt = mysqli_prepare($conn, "INSERT INTO categories (slug, name) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $slug, $naziv);
    if (mysqli_stmt_execute($stmt)) {
        zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Dodao kategoriju', "$naziv ($slug)");
    }
    header("Location: admin_kategorije.php");
    exit();
}

if (isset($_GET['prekini_pouzece'])) {
    $kat_id = (int)$_GET['prekini_pouzece'];
    $stmt = mysqli_prepare($conn, "UPDATE categories SET kartica_obavezna = NOT kartica_obavezna WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $kat_id);
    mysqli_stmt_execute($stmt);
    zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Promijenio pravilo plaćanja kategorije', "Kategorija #$kat_id");
    header("Location: admin_kategorije.php");
    exit();
}

if (isset($_GET['obrisi'])) {
    $kat_id = (int)$_GET['obrisi'];

    $stmt = mysqli_prepare($conn, "SELECT name FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $kat_id);
    mysqli_stmt_execute($stmt);
    $kat = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $kat_id);

    if (mysqli_stmt_execute($stmt)) {
        zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Obrisao kategoriju', $kat ? $kat['name'] : "#$kat_id");
        header("Location: admin_kategorije.php");
        exit();
    } else {
        $greska_brisanja = "Kategorija se ne može obrisati jer još postoje proizvodi u njoj. Premjestite ih u drugu kategoriju prije brisanja.";
    }
}

$kategorije = mysqli_query($conn, "SELECT c.id, c.slug, c.name, c.kartica_obavezna, COUNT(p.id) AS broj_proizvoda FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id ORDER BY c.name");

$naslov_stranice = "Kategorije";
include "admin_header.php";
?>

<h2 class="section-title">Kategorije</h2>

<?php if (isset($greska_brisanja)): ?>
    <p class="message"><?php echo htmlspecialchars($greska_brisanja); ?></p>
<?php endif; ?>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>Naziv</th>
        <th>Slug</th>
        <th>Broj proizvoda</th>
        <th>Samo kartica</th>
        <th>Akcije</th>
    </tr>
    <?php while ($k = mysqli_fetch_assoc($kategorije)): ?>
        <tr>
            <td><?php echo htmlspecialchars($k['name']); ?></td>
            <td><?php echo htmlspecialchars($k['slug']); ?></td>
            <td><?php echo $k['broj_proizvoda']; ?></td>
            <td><?php echo $k['kartica_obavezna'] ? '<span class="stock-badge stock-low">Da</span>' : '<span class="stock-badge stock-ok">Ne</span>'; ?></td>
            <td>
                <a href="admin_kategorije.php?prekini_pouzece=<?php echo $k['id']; ?>" class="btn btn-login"><?php echo $k['kartica_obavezna'] ? 'Dopusti pouzeće' : 'Zahtijevaj karticu'; ?></a>
                <a href="admin_kategorije.php?obrisi=<?php echo $k['id']; ?>" class="delete-cart" onclick="return confirm('Obrisati kategoriju?');">Obriši</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</div>

<div class="contact-box">
    <h3>Dodaj novu kategoriju</h3>
    <form method="POST">
        <?php echo csrf_polje(); ?>
        <input type="text" name="naziv" placeholder="Naziv (npr. Periferija)" required>
        <input type="text" name="slug" placeholder="Slug bez razmaka (npr. periferija)" pattern="[a-z0-9-]+" required>
        <button type="submit" name="dodaj_kategoriju" class="btn">Dodaj kategoriju</button>
    </form>
</div>

<?php include "admin_footer.php"; ?>

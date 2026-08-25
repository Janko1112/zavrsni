<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT name FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    header("Location: admin_proizvodi.php");
    exit();
}

$proizvod = mysqli_fetch_assoc($res);

$stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Obrisao proizvod', "Proizvod #$id ({$proizvod['name']})");
    header("Location: admin_proizvodi.php");
    exit();
} else {
    $naslov_stranice = "Greška";
    include "admin_header.php";
    echo '<p class="message">Proizvod "' . htmlspecialchars($proizvod['name']) . '" se ne može obrisati jer je dio postojećih narudžbi. Umjesto brisanja, postavite mu zalihu na 0.</p>';
    echo '<a href="admin_proizvodi.php" class="btn">Natrag na popis proizvoda</a>';
    include "admin_footer.php";
}

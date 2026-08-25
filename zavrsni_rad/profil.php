<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

$user_id = $_SESSION['user_id'];
$poruka = "";
$greska = "";

if (isset($_POST['spremi_podatke'])) {
    csrf_provjeri();
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $email = trim($_POST['email']);

    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $greska = "Email adresa nije ispravnog formata.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET ime = ?, prezime = ?, email = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'sssi', $ime, $prezime, $email, $user_id);
        mysqli_stmt_execute($stmt);

        $poruka = "Osobni podaci su spremljeni.";
    }
}

if (isset($_POST['promijeni_lozinku'])) {
    csrf_provjeri();
    $trenutna = $_POST['trenutna_lozinka'];
    $nova = $_POST['nova_lozinka'];
    $potvrda = $_POST['potvrda_lozinke'];

    $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);

    if (!provjeri_lozinku_i_nadogradi($conn, $row, $trenutna)) {
        $greska = "Trenutna lozinka nije ispravna.";
    } elseif (strlen($nova) < 5) {
        $greska = "Nova lozinka mora imati barem 5 znakova.";
    } elseif ($nova !== $potvrda) {
        $greska = "Nova lozinka i potvrda lozinke se ne podudaraju.";
    } else {
        $nova_hash = password_hash($nova, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $nova_hash, $user_id);
        mysqli_stmt_execute($stmt);
        $poruka = "Lozinka je uspješno promijenjena.";
    }
}

if (isset($_POST['dodaj_adresu'])) {
    csrf_provjeri();
    $a_ime = trim($_POST['a_ime']);
    $a_prezime = trim($_POST['a_prezime']);
    $a_adresa = trim($_POST['a_adresa']);
    $a_grad = trim($_POST['a_grad']);
    $a_email = trim($_POST['a_email']);
    $a_telefon = trim($_POST['a_telefon']);

    if ($a_ime === '' || $a_prezime === '' || $a_adresa === '' || $a_grad === '' || $a_telefon === '' || filter_var($a_email, FILTER_VALIDATE_EMAIL) === false) {
        $greska = "Provjerite podatke adrese — email mora biti ispravnog formata, a sva polja popunjena.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO saved_addresses (user_id, ime, prezime, adresa, grad, email, telefon) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'issssss', $user_id, $a_ime, $a_prezime, $a_adresa, $a_grad, $a_email, $a_telefon);
        mysqli_stmt_execute($stmt);

        $poruka = "Adresa je spremljena.";
    }
}

if (isset($_GET['obrisi_adresu'])) {
    $address_id = (int)$_GET['obrisi_adresu'];
    $stmt = mysqli_prepare($conn, "DELETE FROM saved_addresses WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $address_id, $user_id);
    mysqli_stmt_execute($stmt);
    header("Location: profil.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT ime, prezime, email, username FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$korisnik = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$stmt = mysqli_prepare($conn, "SELECT id, ime, prezime, adresa, grad, email, telefon FROM saved_addresses WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$adrese = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moj profil</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <div class="logo">PC Shop</div>
    <nav>
        <a href="index.php">Početna</a>
        <a href="komponente.php">Komponente</a>
        <a href="gaming.php">Gaming</a>
        <a href="laptopi.php">Laptopi</a>
        <a href="kontakt.php">Kontakt</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="admin.php" class="admin_panel">Admin Panel</a>
        <?php endif; ?>
    </nav>
    <div class="header-buttons">
        <span>Dobro došli, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
        <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
        <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
    </div>
</header>

<section class="page-banner">
    <div>
        <h1>Moj profil</h1>
    </div>
</section>

<section class="container profile-container">

    <?php if ($poruka): ?><p class="message message-success"><?php echo htmlspecialchars($poruka); ?></p><?php endif; ?>
    <?php if ($greska): ?><p class="message"><?php echo htmlspecialchars($greska); ?></p><?php endif; ?>

    <div class="profile-links">
        <a href="wishlist.php" class="btn btn-login">Lista želja</a>
        <a href="moje_narudzbe.php" class="btn btn-login">Moje narudžbe</a>
        <a href="moje_recenzije.php" class="btn btn-login">Moje recenzije</a>
    </div>

    <div class="contact-box">
        <h2 class="section-title">Osobni podaci</h2>
        <form method="POST">
            <?php echo csrf_polje(); ?>
            <input type="text" value="<?php echo htmlspecialchars($korisnik['username']); ?>" disabled>
            <input type="text" name="ime" placeholder="Ime" value="<?php echo htmlspecialchars($korisnik['ime'] ?? ''); ?>">
            <input type="text" name="prezime" placeholder="Prezime" value="<?php echo htmlspecialchars($korisnik['prezime'] ?? ''); ?>">
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($korisnik['email'] ?? ''); ?>">
            <button type="submit" name="spremi_podatke" class="btn">Spremi podatke</button>
        </form>
    </div>

    <div class="contact-box">
        <h2 class="section-title">Promjena lozinke</h2>
        <form method="POST">
            <?php echo csrf_polje(); ?>
            <input type="password" name="trenutna_lozinka" placeholder="Trenutna lozinka" required>
            <input type="password" name="nova_lozinka" placeholder="Nova lozinka" required>
            <input type="password" name="potvrda_lozinke" placeholder="Potvrda nove lozinke" required>
            <button type="submit" name="promijeni_lozinku" class="btn">Promijeni lozinku</button>
        </form>
    </div>

    <div class="contact-box">
        <h2 class="section-title">Spremljene adrese za dostavu</h2>

        <?php while ($a = mysqli_fetch_assoc($adrese)): ?>
            <div class="saved-address">
                <p><strong><?php echo htmlspecialchars($a['ime'] . ' ' . $a['prezime']); ?></strong></p>
                <p><?php echo htmlspecialchars($a['adresa']); ?>, <?php echo htmlspecialchars($a['grad']); ?></p>
                <p><?php echo htmlspecialchars($a['email']); ?> · <?php echo htmlspecialchars($a['telefon']); ?></p>
                <a href="profil.php?obrisi_adresu=<?php echo $a['id']; ?>" class="delete-cart" onclick="return confirm('Obrisati ovu adresu?');">Obriši</a>
            </div>
        <?php endwhile; ?>

        <h3>Dodaj novu adresu</h3>
        <form method="POST">
            <?php echo csrf_polje(); ?>
            <input type="text" name="a_ime" placeholder="Ime" required>
            <input type="text" name="a_prezime" placeholder="Prezime" required>
            <input type="text" name="a_adresa" placeholder="Adresa i kućni broj" required>
            <input type="text" name="a_grad" placeholder="Grad i poštanski broj" required>
            <input type="email" name="a_email" placeholder="E-mail" required>
            <input type="text" name="a_telefon" placeholder="Telefon" required>
            <button type="submit" name="dodaj_adresu" class="btn">Dodaj adresu</button>
        </form>
    </div>

</section>

</body>
</html>

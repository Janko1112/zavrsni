<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$poruka = "";
if (isset($_GET['info']) && $_GET['info'] == 'auth_required') {
    $poruka = "Molimo prijavite se kako biste mogli dodavati artikle u košaricu!";
}
if (!empty($_SESSION['istekla_sesija'])) {
    $poruka = "Vaša sesija je istekla zbog neaktivnosti. Prijavite se ponovno.";
    unset($_SESSION['istekla_sesija']);
}

if (isset($_POST['login'])) {
    csrf_provjeri();

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT *, (locked_until IS NOT NULL AND locked_until > NOW()) AS je_zakljucan FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($user['je_zakljucan']) {
            $poruka = "Ovaj je račun privremeno zaključan zbog previše neuspjelih pokušaja prijave. Pokušajte kasnije.";
        } elseif (provjeri_lozinku_i_nadogradi($conn, $user, $password)) {
            ponisti_neuspjele_pokusaje($conn, $user['id']);
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            if ($user['role'] == 'admin') {
                $_SESSION['role'] = 'user';
                $_SESSION['ceka_admin_pin'] = true;
                $_SESSION['pending_admin_id'] = $user['id'];
                header("Location: admin_2fa.php");
            } else {
                $_SESSION['role'] = $user['role'];
                header("Location: index.php");
            }
            exit();
        } else {
            zabiljezi_neuspjeli_pokusaj($conn, $user['id'], $user['failed_attempts']);
            $poruka = "Pogrešno korisničko ime ili lozinka.";
        }
    } else {
        $poruka = "Pogrešno korisničko ime ili lozinka.";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
<section class="container">
    <div class="contact-box">
        <h2 class="section-title">Prijava</h2>
        <?php if (!empty($poruka)): ?>
            <p class="message"><?php echo htmlspecialchars($poruka); ?></p>
        <?php endif; ?>
        <form method="POST">
            <?php echo csrf_polje(); ?>
            <input type="text" name="username" placeholder="Korisničko ime" required>
            <input type="password" name="password" placeholder="Lozinka" required>
            <button class="btn" name="login">Prijava</button>
        </form>
    </div>
</section>
</body>
</html>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['ceka_admin_pin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['pin_pokusaji'])) {
    $_SESSION['pin_pokusaji'] = 0;
}

$greska = "";

if ($_SESSION['pin_pokusaji'] >= 5) {
    session_destroy();
    header("Location: login.php?info=auth_required");
    exit();
}

if (isset($_POST['potvrdi_pin'])) {
    csrf_provjeri();

    $pin = trim($_POST['pin']);

    $stmt = mysqli_prepare($conn, "SELECT id, username, role, admin_pin_hash FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['pending_admin_id']);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && !empty($user['admin_pin_hash']) && password_verify($pin, $user['admin_pin_hash'])) {
        session_regenerate_id(true);
        $_SESSION['role'] = 'admin';
        unset($_SESSION['ceka_admin_pin']);
        unset($_SESSION['pending_admin_id']);
        unset($_SESSION['pin_pokusaji']);
        header("Location: admin.php");
        exit();
    } else {
        $_SESSION['pin_pokusaji']++;
        $greska = "Netočan PIN.";
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodatna provjera identiteta</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
<section class="container">
    <div class="contact-box">
        <h2 class="section-title">Dodatna provjera identiteta</h2>
        <p class="payment-hint">Prijavljujete se na administratorski račun. Unesite sigurnosni PIN za dodatnu potvrdu identiteta.</p>
        <?php if ($greska): ?>
            <p class="message"><?php echo htmlspecialchars($greska); ?></p>
        <?php endif; ?>
        <form method="POST">
            <?php echo csrf_polje(); ?>
            <input type="password" name="pin" placeholder="Sigurnosni PIN" inputmode="numeric" required autofocus>
            <button type="submit" name="potvrdi_pin" class="btn">Potvrdi</button>
        </form>
    </div>
</section>
</body>
</html>

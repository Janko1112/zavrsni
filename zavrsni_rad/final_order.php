<?php
session_start();
include "db.php";

if (!isset($_SESSION['username']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$ime = mysqli_real_escape_string($conn, $_POST['ime']);
$prezime = mysqli_real_escape_string($conn, $_POST['prezime']);
$adresa = mysqli_real_escape_string($conn, $_POST['adresa']);
$grad = mysqli_real_escape_string($conn, $_POST['grad']);
$email_kupca = mysqli_real_escape_string($conn, $_POST['email']);
$telefon = mysqli_real_escape_string($conn, $_POST['telefon']);

$sql_cart = "SELECT cart.product_id, cart.quantity AS kupljena_kol, products.name, products.price, products.quantity AS trenutno_u_bazi 
             FROM cart 
             JOIN products ON cart.product_id = products.id 
             WHERE cart.user_username = '$username'";
$cart_res = mysqli_query($conn, $sql_cart);

if (mysqli_num_rows($cart_res) == 0) {
    die("Vaša košarica je prazna.");
}

$detalji_artikala_za_mail = "";
$ukupni_iznos = 0;

mysqli_begin_transaction($conn);

try {
    while ($row = mysqli_fetch_assoc($cart_res)) {
        $product_id = $row['product_id'];
        $kupljena_kol = $row['kupljena_kol'];
        $novo_stanje = $row['trenutno_u_bazi'] - $kupljena_kol;

        if ($novo_stanje < 0) {
            throw new Exception("Artikl " . $row['name'] . " nema dovoljno zaliha.");
        }

        $update_stock = "UPDATE products SET quantity = '$novo_stanje' WHERE id = '$product_id'";
        mysqli_query($conn, $update_stock);

        $iznos_artikla = $row['price'] * $kupljena_kol;
        $ukupni_iznos += $iznos_artikla;
        
        $detalji_artikala_za_mail .= "- " . $row['name'] . " x" . $kupljena_kol . " (" . $iznos_artikla . " €)\n";
    }

    $clear_cart = "DELETE FROM cart WHERE user_username = '$username'";
    mysqli_query($conn, $clear_cart);

    mysqli_commit($conn);

    ?>
    <!DOCTYPE html>
    <html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>Narudžba Uspješna!</title>
        <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>">
    </head>
    <body>
    
    <header>
        <div class="logo">PC SHOP</div>

        <nav>
            <a href="index.php">Početna</a>
            <a href="komponente.php">Komponente</a>
            <a href="gaming.php">Gaming</a>
            <a href="laptopi.php">Laptopi</a>
            <a href="kontakt.php">Kontakt</a>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <a href="admin.php" class="admin_panel">Admin Panel</a>
            <?php endif; ?>
        </nav>

        <div class="header-buttons">
            <?php if(isset($_SESSION['username'])): ?>
                <span>Dobro došli, <?php echo $_SESSION['username']; ?></span>
                <button class="btn btn-login logout" onclick="window.location.href='logout.php';">Odjava</button>
            <?php else: ?>

                <button class="btn btn-login" onclick="window.location.href='login.php';">Prijava</button>
            <?php endif; ?>
            <button class="btn btn-cart" onclick="window.location.href='cart.php';">Košarica</button>
        </div>
    </header>

    <section class="container order-summary">
        <h2 class="order-heading">Narudžba je uspješno primljena!</h2>
        
        <p>Hvala Vam na kupnji, <strong><?php echo $ime . " " . $prezime; ?></strong>.</p>
        <p>Na Vašu e-mail adresu <strong><?php echo $email_kupca; ?></strong> poslana je obavijest o detaljima kupnje.</p>
        <br>
        
        <div>
            <h4>Podaci za dostavu:</h4>
            <p><strong>Adresa:</strong> <?php echo $adresa; ?>, <?php echo $grad; ?></p>
            <p><strong>Telefon:</strong> <?php echo $telefon; ?></p>
            <p><strong>Način plaćanja:</strong> Gotovinom pri preuzimanju (Pouzećem)</p>
        </div>
        
        <h4>Naručeni artikli:</h4>
        <pre><?php echo trim($detalji_artikala_za_mail); ?></pre>
        
        <h3>Ukupno za platiti: <span><?php echo $ukupni_iznos; ?> €</span></h3>
        <br>
        
        <a href="index.php" class="btn-index">Natrag na početnu stranicu</a>
    </section>
    
    </body>
    </html>
    <?php
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "
    <script>
    alert('Greška prilikom narudžbe: " . $e->getMessage() . "');
    window.location.href = 'cart.php';
    </script>
    ";
}
?>

<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['username']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

csrf_provjeri();

$user_id = $_SESSION['user_id'];
$ime = trim($_POST['ime']);
$prezime = trim($_POST['prezime']);
$adresa = trim($_POST['adresa'] ?? '');
$grad = trim($_POST['grad'] ?? '');
$email_kupca = trim($_POST['email']);
$telefon = trim($_POST['telefon']);

$nacin_dostave = trim($_POST['nacin_dostave'] ?? '');
if (!in_array($nacin_dostave, dopusteni_nacini_dostave(), true)) {
    die("Odabran je neispravan način dostave.");
}

if ($nacin_dostave === 'osobno preuzimanje') {
    $adresa = 'Osobno preuzimanje u trgovini';
    $grad = '—';
} elseif (strlen($adresa) < 3 || strlen($grad) < 2) {
    die("Provjerite adresu dostave — adresa i grad su obavezni za odabrani način dostave.");
}

if ($ime === '' || $prezime === '' || $telefon === '' || filter_var($email_kupca, FILTER_VALIDATE_EMAIL) === false) {
    die("Provjerite podatke za dostavu — email adresa mora biti ispravnog formata, a sva polja moraju biti popunjena.");
}

$stmt = mysqli_prepare($conn, "SELECT cart.product_id, cart.quantity AS kupljena_kol, products.name, products.price, products.quantity AS trenutno_u_bazi, products.max_per_order FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$cart_res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($cart_res) == 0) {
    die("Vaša košarica je prazna.");
}

$stavke = [];
$ukupni_iznos = 0;
$detalji_artikala_za_mail = "";

while ($row = mysqli_fetch_assoc($cart_res)) {
    $stavke[] = $row;
}

mysqli_begin_transaction($conn);

try {
    foreach ($stavke as $row) {
        $product_id = $row['product_id'];
        $kupljena_kol = $row['kupljena_kol'];

        if ($kupljena_kol <= 0 || $kupljena_kol != (int)$kupljena_kol) {
            throw new Exception("Neispravna količina za artikl " . $row['name'] . ".");
        }

        if ($kupljena_kol > $row['max_per_order']) {
            throw new Exception("Za artikl " . $row['name'] . " dopušteno je najviše " . $row['max_per_order'] . " kom po narudžbi. Izmijenite košaricu.");
        }

        $update_stock = mysqli_prepare($conn, "UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
        mysqli_stmt_bind_param($update_stock, 'iii', $kupljena_kol, $product_id, $kupljena_kol);
        mysqli_stmt_execute($update_stock);

        if (mysqli_stmt_affected_rows($update_stock) !== 1) {
            throw new Exception("Artikl " . $row['name'] . " u međuvremenu je rasprodan ili nema dovoljno zaliha.");
        }

        $iznos_artikla = $row['price'] * $kupljena_kol;
        $ukupni_iznos += $iznos_artikla;

        $detalji_artikala_za_mail .= "- " . $row['name'] . " x" . $kupljena_kol . " (" . $iznos_artikla . " €)\n";
    }

    $shipping_cost = izracunaj_trosak_dostave($ukupni_iznos, $nacin_dostave);
    $payment_method = ($_POST['nacin_placanja'] ?? 'Pouzećem') === 'Kartica' ? 'Kartica' : 'Pouzećem';

    if ($payment_method === 'Pouzećem' && !pouzece_dopusteno($conn, $user_id, $ukupni_iznos)) {
        throw new Exception("Plaćanje pouzećem nije dostupno za ovu narudžbu (iznos preko " . MAKS_IZNOS_ZA_POUZECE . " € ili sadrži proizvod koji zahtijeva kartično plaćanje). Odaberite kartično plaćanje.");
    }

    $popust = 0;
    $kupon_kod = null;
    $primijenjeni_kupon = null;

    if (!empty($_SESSION['kupon'])) {
        $provjera_kupona = provjeri_kupon($conn, $_SESSION['kupon']);
        if ($provjera_kupona['ok']) {
            $primijenjeni_kupon = $provjera_kupona['kupon'];
            $popust = izracunaj_popust($primijenjeni_kupon, $ukupni_iznos);
            $kupon_kod = $primijenjeni_kupon['code'];
        }
    }

    $ukupni_iznos_s_dostavom = $ukupni_iznos - $popust + $shipping_cost;

    $insert_address = mysqli_prepare($conn, "INSERT INTO addresses (user_id, ime, prezime, adresa, grad, email, telefon) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($insert_address, 'issssss', $user_id, $ime, $prezime, $adresa, $grad, $email_kupca, $telefon);
    mysqli_stmt_execute($insert_address);
    $address_id = mysqli_insert_id($conn);

    $insert_order = mysqli_prepare($conn, "INSERT INTO orders (user_id, address_id, total_amount, shipping_cost, delivery_method, payment_method, payment_status, coupon_code, discount_amount, order_status) VALUES (?, ?, ?, ?, ?, ?, 'čeka plaćanje', ?, ?, 'čeka plaćanje')");
    mysqli_stmt_bind_param($insert_order, 'iiddsssd', $user_id, $address_id, $ukupni_iznos_s_dostavom, $shipping_cost, $nacin_dostave, $payment_method, $kupon_kod, $popust);
    mysqli_stmt_execute($insert_order);
    $order_id = mysqli_insert_id($conn);

    if ($primijenjeni_kupon) {
        $update_kupon = mysqli_prepare($conn, "UPDATE coupons SET broj_koristenja = broj_koristenja + 1 WHERE id = ?");
        mysqli_stmt_bind_param($update_kupon, 'i', $primijenjeni_kupon['id']);
        mysqli_stmt_execute($update_kupon);
    }

    foreach ($stavke as $row) {
        $insert_item = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_item, 'iisid', $order_id, $row['product_id'], $row['name'], $row['kupljena_kol'], $row['price']);
        mysqli_stmt_execute($insert_item);
    }

    $insert_payment = mysqli_prepare($conn, "INSERT INTO payments (order_id, method, status, amount) VALUES (?, ?, 'čeka plaćanje', ?)");
    mysqli_stmt_bind_param($insert_payment, 'isd', $order_id, $payment_method, $ukupni_iznos_s_dostavom);
    mysqli_stmt_execute($insert_payment);

    $clear_cart = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($clear_cart, 'i', $user_id);
    mysqli_stmt_execute($clear_cart);

    mysqli_commit($conn);
    unset($_SESSION['kupon']);

    if ($payment_method === 'Kartica') {
        header("Location: placanje.php?order_id=" . $order_id);
    } else {
        posalji_potvrdu_narudzbe($conn, $order_id);
        header("Location: narudzba_potvrda.php?order_id=" . $order_id);
    }
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "
    <script>
    alert('Greška prilikom narudžbe: " . addslashes($e->getMessage()) . "');
    window.location.href = 'cart.php';
    </script>
    ";
}
?>

<?php

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

define('MAKSIMALNA_RAZUMNA_KOLICINA', 100);
define('RATE_LIMIT_SEKUNDE', 2);
define('MAX_NEAKTIVNOST_SEKUNDE', 900);
define('MAX_POKUSAJA_PRIJAVE', 5);
define('TRAJANJE_ZAKLJUCAVANJA_SEKUNDE', 900);

function pokreni_sesiju() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $sigurno = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'secure' => $sigurno,
        'samesite' => 'Lax'
    ]);

    session_start();

    if (isset($_SESSION['zadnja_aktivnost']) && (time() - $_SESSION['zadnja_aktivnost']) > MAX_NEAKTIVNOST_SEKUNDE) {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['istekla_sesija'] = true;
    }

    $_SESSION['zadnja_aktivnost'] = time();
}

function zahtijevaj_prijavu() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?info=auth_required");
        exit();
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_polje() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_provjeri() {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die("Sigurnosna provjera nije uspjela. Vratite se na prethodnu stranicu i pokušajte ponovno.");
    }
}

function provjeri_lozinku_i_nadogradi($conn, $user, $lozinka) {
    $spremljeno = $user['password'];

    if (password_verify($lozinka, $spremljeno)) {
        return true;
    }

    if (strlen($spremljeno) === 32 && ctype_xdigit($spremljeno) && md5($lozinka) === $spremljeno) {
        $novi_hash = password_hash($lozinka, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $novi_hash, $user['id']);
        mysqli_stmt_execute($stmt);
        return true;
    }

    return false;
}

function zabiljezi_neuspjeli_pokusaj($conn, $user_id, $trenutni_broj) {
    $novi_broj = $trenutni_broj + 1;

    if ($novi_broj >= MAX_POKUSAJA_PRIJAVE) {
        $trajanje = TRAJANJE_ZAKLJUCAVANJA_SEKUNDE;
        $stmt = mysqli_prepare($conn, "UPDATE users SET failed_attempts = 0, locked_until = DATE_ADD(NOW(), INTERVAL ? SECOND) WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $trajanje, $user_id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET failed_attempts = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $novi_broj, $user_id);
    }

    mysqli_stmt_execute($stmt);
}

function ponisti_neuspjele_pokusaje($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
}

function obradi_upload_slike($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'putanja' => null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'poruka' => 'Greška prilikom prijenosa datoteke.'];
    }

    $maks_velicina = 5 * 1024 * 1024;
    if ($file['size'] > $maks_velicina) {
        return ['ok' => false, 'poruka' => 'Datoteka je prevelika (maksimalno 5 MB).'];
    }

    $dopusteni_tipovi = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $stvarni_tip = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($dopusteni_tipovi[$stvarni_tip])) {
        return ['ok' => false, 'poruka' => 'Dopuštene su samo slike u formatu JPG, PNG, WEBP ili GIF.'];
    }

    if (@getimagesize($file['tmp_name']) === false) {
        return ['ok' => false, 'poruka' => 'Datoteka nije valjana slikovna datoteka.'];
    }

    $ekstenzija = $dopusteni_tipovi[$stvarni_tip];
    $sigurni_naziv = bin2hex(random_bytes(16)) . '.' . $ekstenzija;
    $odrediste = __DIR__ . '/uploads/' . $sigurni_naziv;

    if (!move_uploaded_file($file['tmp_name'], $odrediste)) {
        return ['ok' => false, 'poruka' => 'Pohrana datoteke na poslužitelj nije uspjela.'];
    }

    return ['ok' => true, 'putanja' => 'uploads/' . $sigurni_naziv];
}

function validiraj_kolicinu($sirovi_unos, $na_zalihi, $max_po_narudzbi, $vec_u_kosarici = 0) {
    $kolicina = filter_var($sirovi_unos, FILTER_VALIDATE_INT);

    if ($kolicina === false) {
        return ['ok' => false, 'poruka' => 'Količina mora biti cijeli broj.'];
    }
    if ($kolicina <= 0) {
        return ['ok' => false, 'poruka' => 'Količina mora biti veća od nule.'];
    }
    if ($kolicina > MAKSIMALNA_RAZUMNA_KOLICINA) {
        return ['ok' => false, 'poruka' => 'Zatražena količina je nerazumno velika.'];
    }
    if (($vec_u_kosarici + $kolicina) > $max_po_narudzbi) {
        return ['ok' => false, 'poruka' => 'Za ovaj proizvod moguće je naručiti najviše ' . $max_po_narudzbi . ' kom po narudžbi.'];
    }
    if (($vec_u_kosarici + $kolicina) > $na_zalihi) {
        return ['ok' => false, 'poruka' => 'Na zalihi nema tražene količine.'];
    }

    return ['ok' => true, 'kolicina' => $kolicina];
}

function provjeri_rate_limit($product_id) {
    if (!isset($_SESSION['zadnji_dodatak_u_kosaricu'])) {
        $_SESSION['zadnji_dodatak_u_kosaricu'] = [];
    }

    $sada = time();
    $zadnji_put = isset($_SESSION['zadnji_dodatak_u_kosaricu'][$product_id]) ? $_SESSION['zadnji_dodatak_u_kosaricu'][$product_id] : 0;

    if (($sada - $zadnji_put) < RATE_LIMIT_SEKUNDE) {
        return false;
    }

    $_SESSION['zadnji_dodatak_u_kosaricu'][$product_id] = $sada;
    return true;
}

function vrati_zalihu_i_promijeni_status($conn, $order_id, $novi_status) {
    mysqli_begin_transaction($conn);

    try {
        $stmt = mysqli_prepare($conn, "SELECT product_id, quantity FROM order_items WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $order_id);
        mysqli_stmt_execute($stmt);
        $stavke = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($stavke)) {
            $stmt2 = mysqli_prepare($conn, "UPDATE products SET quantity = quantity + ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt2, 'ii', $row['quantity'], $row['product_id']);
            mysqli_stmt_execute($stmt2);
        }

        $stmt = mysqli_prepare($conn, "UPDATE orders SET order_status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $novi_status, $order_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return false;
    }
}

define('MAKS_IZNOS_ZA_POUZECE', 500);
define('BESPLATNA_DOSTAVA_IZNAD', 100);
define('STANDARDNI_TROSAK_DOSTAVE', 5);
define('EKSPRESNI_TROSAK_DOSTAVE', 12);

function pouzece_dopusteno($conn, $user_id, $ukupni_iznos) {
    if ($ukupni_iznos > MAKS_IZNOS_ZA_POUZECE) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS n FROM cart c JOIN products p ON p.id = c.product_id JOIN categories cat ON cat.id = p.category_id WHERE c.user_id = ? AND cat.kartica_obavezna = 1");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    return $row['n'] == 0;
}

function dopusteni_nacini_dostave() {
    return ['osobno preuzimanje', 'standardna dostava', 'ekspresna dostava'];
}

function izracunaj_trosak_dostave($iznos_narudzbe, $nacin_dostave) {
    if ($nacin_dostave === 'osobno preuzimanje') {
        return 0;
    }
    if ($nacin_dostave === 'ekspresna dostava') {
        return EKSPRESNI_TROSAK_DOSTAVE;
    }
    return $iznos_narudzbe >= BESPLATNA_DOSTAVA_IZNAD ? 0 : STANDARDNI_TROSAK_DOSTAVE;
}

function provjeri_kupon($conn, $code) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM coupons WHERE code = ? AND aktivan = 1");
    mysqli_stmt_bind_param($stmt, 's', $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) === 0) {
        return ['ok' => false, 'poruka' => 'Kupon ne postoji ili nije aktivan.'];
    }

    $kupon = mysqli_fetch_assoc($res);
    $sada = time();

    if ($kupon['vrijedi_od'] && strtotime($kupon['vrijedi_od']) > $sada) {
        return ['ok' => false, 'poruka' => 'Kupon još nije aktivan.'];
    }
    if ($kupon['vrijedi_do'] && strtotime($kupon['vrijedi_do']) < $sada) {
        return ['ok' => false, 'poruka' => 'Kupon je istekao.'];
    }
    if ($kupon['max_koristenja'] !== null && $kupon['broj_koristenja'] >= $kupon['max_koristenja']) {
        return ['ok' => false, 'poruka' => 'Kupon je iskorišten maksimalan broj puta.'];
    }

    return ['ok' => true, 'kupon' => $kupon];
}

function izracunaj_popust($kupon, $iznos) {
    if ($kupon['tip'] === 'postotak') {
        $popust = $iznos * ($kupon['vrijednost'] / 100);
    } else {
        $popust = $kupon['vrijednost'];
    }
    return round(min($popust, $iznos), 2);
}

function zabiljezi_poruku($conn, $tip, $email, $ime, $predmet, $sadrzaj, $order_id = null) {
    $stmt = mysqli_prepare($conn, "INSERT INTO poruke (tip, ime, email, predmet, sadrzaj, order_id) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssssi', $tip, $ime, $email, $predmet, $sadrzaj, $order_id);
    mysqli_stmt_execute($stmt);
}

function posalji_potvrdu_narudzbe($conn, $order_id) {
    $stmt = mysqli_prepare($conn, "SELECT o.total_amount, a.email, a.ime, a.prezime FROM orders o JOIN addresses a ON a.id = o.address_id WHERE o.id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $order_id);
    mysqli_stmt_execute($stmt);
    $o = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$o) {
        return;
    }

    $stmt = mysqli_prepare($conn, "SELECT product_name, quantity, price FROM order_items WHERE order_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $order_id);
    mysqli_stmt_execute($stmt);
    $stavke_res = mysqli_stmt_get_result($stmt);

    $sadrzaj = "Poštovani/a " . $o['ime'] . " " . $o['prezime'] . ",\n\nVaša narudžba #" . $order_id . " je zaprimljena.\n\nNaručeni artikli:\n";
    while ($it = mysqli_fetch_assoc($stavke_res)) {
        $sadrzaj .= "- " . $it['product_name'] . " x" . $it['quantity'] . " (" . ($it['price'] * $it['quantity']) . " €)\n";
    }
    $sadrzaj .= "\nUkupno: " . $o['total_amount'] . " €\n\nHvala na kupnji!\nPC Shop";

    zabiljezi_poruku($conn, 'potvrda_narudzbe', $o['email'], $o['ime'] . ' ' . $o['prezime'], "Potvrda narudžbe #" . $order_id, $sadrzaj, $order_id);
}

function zabiljezi_admin_promjenu($conn, $admin_id, $akcija, $detalji = '') {
    $stmt = mysqli_prepare($conn, "INSERT INTO admin_log (admin_id, akcija, detalji) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iss', $admin_id, $akcija, $detalji);
    mysqli_stmt_execute($stmt);
}

function zahtijevaj_admina() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: login.php");
        exit();
    }
}

function dostupnost_oznaka($quantity, $threshold) {
    if ($quantity <= 0) {
        return '<span class="stock-badge stock-out">Nedostupno</span>';
    } elseif ($quantity <= $threshold) {
        return '<span class="stock-badge stock-low">Dostupna mala količina</span>';
    } else {
        return '<span class="stock-badge stock-ok">Dostupno</span>';
    }
}

function ocjena_proizvoda($conn, $product_id) {
    $stmt = mysqli_prepare($conn, "SELECT AVG(rating) AS prosjek, COUNT(*) AS broj FROM reviews WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

function zvjezdice($prosjek) {
    $prosjek = round($prosjek);
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $prosjek ? '★' : '☆';
    }
    return $html;
}

function je_na_listi_zelja($conn, $user_id, $product_id) {
    $stmt = mysqli_prepare($conn, "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $postoji = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    return $postoji;
}

function sigurni_redirect($target, $default) {
    $dozvoljene = ['index.php', 'katalog.php', 'article.php', 'wishlist.php', 'compare.php', 'komponente.php', 'gaming.php', 'laptopi.php'];
    if (!$target) {
        return $default;
    }
    $putanja = parse_url($target, PHP_URL_PATH);
    if ($putanja !== null && in_array($putanja, $dozvoljene, true)) {
        return $target;
    }
    return $default;
}

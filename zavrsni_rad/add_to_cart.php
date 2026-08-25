<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php?info=auth_required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrf_provjeri();

    $is_ajax = isset($_POST['ajax']) && $_POST['ajax'] === '1';

    $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
    $user_id = $_SESSION['user_id'];

    if ($product_id === false || $product_id <= 0) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Neispravan proizvod.']);
            exit();
        }
        die("Greška: Neispravan proizvod.");
    }

    $redirect = sigurni_redirect($_POST['redirect'] ?? null, 'article.php?id=' . $product_id);

    if (!provjeri_rate_limit($product_id)) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Prebrzo šaljete zahtjeve za isti proizvod. Pričekajte trenutak i pokušajte ponovno.']);
            exit();
        }
        echo "<script>alert('Prebrzo šaljete zahtjeve za isti proizvod. Pričekajte trenutak i pokušajte ponovno.'); window.location.href = '" . addslashes($redirect) . "';</script>";
        exit();
    }

    $stmt = mysqli_prepare($conn, "SELECT quantity, max_per_order, name FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);
    $stock_res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($stock_res) === 0) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Artikl ne postoji u sustavu.']);
            exit();
        }
        die("Artikl ne postoji u sustavu.");
    }
    $product = mysqli_fetch_assoc($stock_res);

    $stmt = mysqli_prepare($conn, "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $cart_res = mysqli_stmt_get_result($stmt);
    $vec_u_kosarici = 0;
    $postojeci_red = null;
    if (mysqli_num_rows($cart_res) > 0) {
        $postojeci_red = mysqli_fetch_assoc($cart_res);
        $vec_u_kosarici = (int)$postojeci_red['quantity'];
    }

    $provjera = validiraj_kolicinu($_POST['quantity'] ?? '', $product['quantity'], $product['max_per_order'], $vec_u_kosarici);

    if (!$provjera['ok']) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => $provjera['poruka']]);
            exit();
        }
        echo "<script>alert('" . addslashes($provjera['poruka']) . "'); window.location.href = '" . addslashes($redirect) . "';</script>";
        exit();
    }

    $kolicina = $provjera['kolicina'];

    if ($postojeci_red) {
        $sql = mysqli_prepare($conn, "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($sql, 'iii', $kolicina, $user_id, $product_id);
    } else {
        $sql = mysqli_prepare($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($sql, 'iii', $user_id, $product_id, $kolicina);
    }

    if (mysqli_stmt_execute($sql)) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'message' => 'Artikl je dodan u košaricu.']);
            exit();
        }
        echo "
        <script>
        window.location.href = '" . addslashes($redirect) . "';
        </script>
        ";
    } else {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Došlo je do pogreške prilikom obrade zahtjeva.']);
            exit();
        }
        die("Došlo je do pogreške prilikom obrade zahtjeva.");
    }
}
?>

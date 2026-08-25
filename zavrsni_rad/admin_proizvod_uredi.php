<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Proizvod nije pronađen.");
}

$proizvod = mysqli_fetch_assoc($res);
$greska = "";

if (isset($_POST['spremi'])) {
    csrf_provjeri();

    $name = trim($_POST['name']);
    $manufacturer = trim($_POST['manufacturer']);
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $low_stock_threshold = (int)$_POST['low_stock_threshold'];
    $max_per_order = (int)$_POST['max_per_order'];
    $category_id = (int)$_POST['category_id'];
    $image = trim($_POST['image']);
    $description = trim($_POST['description']);

    $upload_glavne = obradi_upload_slike($_FILES['slika_datoteka'] ?? null);
    if (!$upload_glavne['ok']) {
        $greska = $upload_glavne['poruka'];
    }
    if ($upload_glavne['ok'] && $upload_glavne['putanja']) {
        $image = $upload_glavne['putanja'];
    }

    if ($name === '' || $price < 0 || $quantity < 0 || $max_per_order < 1 || $category_id <= 0) {
        $greska = $greska ?: "Provjerite podatke — cijena, količina i kategorija moraju biti ispravno popunjeni.";
    }

    if ($greska === "") {
        $promjene = [];
        if ($price != $proizvod['price']) {
            $promjene[] = "cijena: {$proizvod['price']}€ → {$price}€";
        }
        if ($quantity != $proizvod['quantity']) {
            $promjene[] = "zaliha: {$proizvod['quantity']} → {$quantity}";
        }
        if ($max_per_order != $proizvod['max_per_order']) {
            $promjene[] = "maks. po narudžbi: {$proizvod['max_per_order']} → {$max_per_order}";
        }
        if ($name !== $proizvod['name']) {
            $promjene[] = "naziv: '{$proizvod['name']}' → '{$name}'";
        }

        $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, manufacturer=?, price=?, quantity=?, low_stock_threshold=?, max_per_order=?, category_id=?, image=?, description=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'ssdiiiissi', $name, $manufacturer, $price, $quantity, $low_stock_threshold, $max_per_order, $category_id, $image, $description, $id);
        mysqli_stmt_execute($stmt);

        if (!empty($_FILES['nove_slike_datoteke']['name'][0])) {
            $broj_datoteka = count($_FILES['nove_slike_datoteke']['name']);
            for ($i = 0; $i < $broj_datoteka; $i++) {
                $pojedinacna = [
                    'name' => $_FILES['nove_slike_datoteke']['name'][$i],
                    'type' => $_FILES['nove_slike_datoteke']['type'][$i],
                    'tmp_name' => $_FILES['nove_slike_datoteke']['tmp_name'][$i],
                    'error' => $_FILES['nove_slike_datoteke']['error'][$i],
                    'size' => $_FILES['nove_slike_datoteke']['size'][$i],
                ];
                $rezultat = obradi_upload_slike($pojedinacna);
                if ($rezultat['ok'] && $rezultat['putanja']) {
                    $stmt2 = mysqli_prepare($conn, "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmt2, 'is', $id, $rezultat['putanja']);
                    mysqli_stmt_execute($stmt2);
                }
            }
            $promjene[] = "dodane nove slike (upload)";
        }

        if (!empty($_POST['nove_slike'])) {
            $linije = preg_split('/\r\n|\r|\n/', trim($_POST['nove_slike']));
            foreach ($linije as $linija) {
                $linija = trim($linija);
                if ($linija !== '') {
                    $stmt2 = mysqli_prepare($conn, "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmt2, 'is', $id, $linija);
                    mysqli_stmt_execute($stmt2);
                }
            }
            $promjene[] = "dodane nove slike (URL)";
        }

        if (count($promjene) > 0) {
            zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Uredio proizvod', "Proizvod #$id ({$proizvod['name']}): " . implode(', ', $promjene));
        }

        header("Location: admin_proizvod_uredi.php?id=" . $id . "&spremljeno=1");
        exit();
    }
}

if (isset($_GET['obrisi_sliku'])) {
    $image_id = (int)$_GET['obrisi_sliku'];
    $stmt = mysqli_prepare($conn, "DELETE FROM product_images WHERE id = ? AND product_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $image_id, $id);
    mysqli_stmt_execute($stmt);
    header("Location: admin_proizvod_uredi.php?id=" . $id);
    exit();
}

$kategorije_admin = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");

$stmt = mysqli_prepare($conn, "SELECT id, image_url FROM product_images WHERE product_id = ? ORDER BY sort_order");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$slike = mysqli_stmt_get_result($stmt);

$naslov_stranice = "Uredi proizvod";
include "admin_header.php";
?>

<h2 class="section-title">Uredi proizvod #<?php echo $proizvod['id']; ?></h2>

<?php if (isset($_GET['spremljeno'])): ?>
    <p class="message message-success">Promjene su spremljene.</p>
<?php endif; ?>
<?php if ($greska): ?>
    <p class="message"><?php echo htmlspecialchars($greska); ?></p>
<?php endif; ?>

<div class="contact-box">
    <form method="POST" enctype="multipart/form-data">
        <?php echo csrf_polje(); ?>
        <input type="text" name="name" value="<?php echo htmlspecialchars($proizvod['name']); ?>" required>
        <input type="text" name="manufacturer" value="<?php echo htmlspecialchars($proizvod['manufacturer'] ?? ''); ?>" required>
        <input type="number" step="0.01" name="price" value="<?php echo $proizvod['price']; ?>" required>
        <input type="number" name="quantity" value="<?php echo $proizvod['quantity']; ?>" required>
        <input type="number" name="low_stock_threshold" value="<?php echo $proizvod['low_stock_threshold']; ?>" required>
        <input type="number" name="max_per_order" value="<?php echo $proizvod['max_per_order']; ?>" required>

        <select name="category_id" required>
            <?php while ($kat = mysqli_fetch_assoc($kategorije_admin)): ?>
                <option value="<?php echo $kat['id']; ?>" <?php echo $kat['id'] == $proizvod['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($kat['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Glavna slika — trenutna:</label>
        <img src="<?php echo htmlspecialchars($proizvod['image']); ?>" alt="" class="admin-current-image">
        <label>Zamijeni uploadom nove datoteke:</label>
        <input type="file" name="slika_datoteka" accept="image/png,image/jpeg,image/webp,image/gif">
        <label>...ili zamijeni URL-om:</label>
        <input type="text" name="image" value="<?php echo htmlspecialchars($proizvod['image']); ?>" required>

        <textarea name="description"><?php echo htmlspecialchars($proizvod['description']); ?></textarea>

        <label>Dodaj nove dodatne slike — upload datoteka:</label>
        <input type="file" name="nove_slike_datoteke[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple>
        <label>...ili URL-ovi, jedan po retku:</label>
        <textarea name="nove_slike" placeholder="Dodaj nove dodatne slike, jedan URL po retku"></textarea>

        <button type="submit" name="spremi" class="btn">Spremi promjene</button>
    </form>
</div>

<div class="contact-box">
    <h3>Dodatne slike</h3>
    <div class="admin-image-grid">
        <?php while ($s = mysqli_fetch_assoc($slike)): ?>
            <div class="admin-image-item">
                <img src="<?php echo htmlspecialchars($s['image_url']); ?>" alt="">
                <a href="admin_proizvod_uredi.php?id=<?php echo $id; ?>&obrisi_sliku=<?php echo $s['id']; ?>" class="delete-cart" onclick="return confirm('Obrisati ovu sliku?');">Obriši</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include "admin_footer.php"; ?>

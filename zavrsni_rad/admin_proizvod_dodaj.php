<?php
include_once "helpers.php";
pokreni_sesiju();
include_once "db.php";
zahtijevaj_admina();

$greska = "";

if (isset($_POST['add'])) {
    csrf_provjeri();

    $name = trim($_POST['name']);
    $manufacturer = trim($_POST['manufacturer']);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    $low_stock_threshold = filter_var($_POST['low_stock_threshold'], FILTER_VALIDATE_INT);
    $max_per_order = filter_var($_POST['max_per_order'], FILTER_VALIDATE_INT);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $image_url = trim($_POST['image']);
    $description = trim($_POST['description']);

    $upload_glavne = obradi_upload_slike($_FILES['slika_datoteka'] ?? null);

    if ($name === '' || $price === false || $price < 0 || $quantity === false || $quantity < 0 || $low_stock_threshold === false || $max_per_order === false || $max_per_order < 1 || $category_id === false) {
        $greska = "Provjerite podatke — cijena, količina i kategorija moraju biti ispravno popunjeni.";
    } elseif (!$upload_glavne['ok']) {
        $greska = $upload_glavne['poruka'];
    } elseif (!$upload_glavne['putanja'] && $image_url === '') {
        $greska = "Potrebno je uploadati glavnu sliku ili upisati URL slike.";
    } else {
        $image = $upload_glavne['putanja'] ?: $image_url;

        $stmt = mysqli_prepare($conn, "INSERT INTO products (name, manufacturer, price, quantity, low_stock_threshold, max_per_order, category_id, image, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssdiiiiss', $name, $manufacturer, $price, $quantity, $low_stock_threshold, $max_per_order, $category_id, $image, $description);
        mysqli_stmt_execute($stmt);
        $novi_id = mysqli_insert_id($conn);

        if (!empty($_FILES['dodatne_slike_datoteke']['name'][0])) {
            $broj_datoteka = count($_FILES['dodatne_slike_datoteke']['name']);
            $redoslijed = 0;
            for ($i = 0; $i < $broj_datoteka; $i++) {
                $pojedinacna = [
                    'name' => $_FILES['dodatne_slike_datoteke']['name'][$i],
                    'type' => $_FILES['dodatne_slike_datoteke']['type'][$i],
                    'tmp_name' => $_FILES['dodatne_slike_datoteke']['tmp_name'][$i],
                    'error' => $_FILES['dodatne_slike_datoteke']['error'][$i],
                    'size' => $_FILES['dodatne_slike_datoteke']['size'][$i],
                ];
                $rezultat = obradi_upload_slike($pojedinacna);
                if ($rezultat['ok'] && $rezultat['putanja']) {
                    $stmt2 = mysqli_prepare($conn, "INSERT INTO product_images (product_id, image_url, sort_order) VALUES (?, ?, ?)");
                    mysqli_stmt_bind_param($stmt2, 'isi', $novi_id, $rezultat['putanja'], $redoslijed);
                    mysqli_stmt_execute($stmt2);
                    $redoslijed++;
                }
            }
        }

        if (!empty($_POST['dodatne_slike'])) {
            $linije = preg_split('/\r\n|\r|\n/', trim($_POST['dodatne_slike']));
            $redoslijed = 100;
            foreach ($linije as $linija) {
                $linija = trim($linija);
                if ($linija !== '') {
                    $stmt2 = mysqli_prepare($conn, "INSERT INTO product_images (product_id, image_url, sort_order) VALUES (?, ?, ?)");
                    mysqli_stmt_bind_param($stmt2, 'isi', $novi_id, $linija, $redoslijed);
                    mysqli_stmt_execute($stmt2);
                    $redoslijed++;
                }
            }
        }

        if (!empty($_POST['specifikacije'])) {
            $linije = preg_split('/\r\n|\r|\n/', trim($_POST['specifikacije']));
            $redoslijed = 0;
            foreach ($linije as $linija) {
                $linija = trim($linija);
                if ($linija !== '' && strpos($linija, ':') !== false) {
                    list($naziv, $vrijednost) = explode(':', $linija, 2);
                    $naziv = trim($naziv);
                    $vrijednost = trim($vrijednost);
                    $stmt2 = mysqli_prepare($conn, "INSERT INTO product_specs (product_id, spec_name, spec_value, sort_order) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt2, 'issi', $novi_id, $naziv, $vrijednost, $redoslijed);
                    mysqli_stmt_execute($stmt2);
                    $redoslijed++;
                }
            }
        }

        zabiljezi_admin_promjenu($conn, $_SESSION['user_id'], 'Dodao proizvod', "Proizvod #$novi_id ($name)");

        header("Location: admin_proizvodi.php");
        exit();
    }
}

$kategorije_admin = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");

$naslov_stranice = "Dodaj proizvod";
include "admin_header.php";
?>

<h2 class="section-title">Dodaj proizvod</h2>

<?php if ($greska): ?>
    <p class="message"><?php echo htmlspecialchars($greska); ?></p>
<?php endif; ?>

<div class="contact-box">
    <form method="POST" enctype="multipart/form-data">
        <?php echo csrf_polje(); ?>

        <input type="text" name="name" placeholder="Naziv artikla" required>
        <input type="text" name="manufacturer" placeholder="Proizvođač" required>
        <input type="number" step="0.01" name="price" placeholder="Cijena" required>
        <input type="number" name="quantity" placeholder="Količina" required>
        <input type="number" name="low_stock_threshold" placeholder="Prag za 'mala količina'" value="5" required>
        <input type="number" name="max_per_order" placeholder="Maksimalna količina po narudžbi" value="5" required>

        <select name="category_id" required>
            <option value="">Odaberite kategoriju</option>
            <?php while ($kat = mysqli_fetch_assoc($kategorije_admin)): ?>
                <option value="<?php echo $kat['id']; ?>"><?php echo htmlspecialchars($kat['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Glavna slika — uploadaj datoteku:</label>
        <input type="file" name="slika_datoteka" accept="image/png,image/jpeg,image/webp,image/gif">
        <label>...ili upiši URL (koristi se samo ako datoteka nije uploadana):</label>
        <input type="text" name="image" placeholder="URL glavne slike">

        <label>Dodatne slike — uploadaj datoteke:</label>
        <input type="file" name="dodatne_slike_datoteke[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple>
        <label>...ili dodaj URL-ove, jedan po retku:</label>
        <textarea name="dodatne_slike" placeholder="Dodatne slike, jedan URL po retku"></textarea>

        <textarea name="description" placeholder="Opis proizvoda"></textarea>
        <textarea name="specifikacije" placeholder="Tehničke specifikacije, format: Naziv: Vrijednost (jedan po retku)"></textarea>

        <button class="btn" name="add">Dodaj artikl</button>
    </form>
</div>

<?php include "admin_footer.php"; ?>

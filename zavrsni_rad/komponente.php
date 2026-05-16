<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komponente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include "db.php";
?>

<header>
    <div class="logo">PC SHOP</div>

    <nav>
        <a href="index.php">Početna</a>
        <a href="komponente.php" class="active">Komponente</a>
        <a href="gaming.php">Gaming</a>
        <a href="laptopi.php">Laptopi</a>
        <a href="kontakt.html">Kontakt</a>
    </nav>
</header>

<section class="page-banner">
    <div>
        <h1>Komponente</h1>
        <p>Najnovije računalne komponente za gaming i profesionalce.</p>
    </div>
</section>

<section class="container">

    <div class="cards">

        <?php
            $sql = "SELECT * FROM products WHERE category='komponente' ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="card">

                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">

                    <div class="card-content">
                        <h3><?php echo $row['name']; ?></h3>
                        <p><?php echo $row['description']; ?></p>
                        <div class="price"><?php echo $row['price']; ?>€</div>
                        
                        <a href="article.php?id=<?php echo $row['id']; ?>" class="btn">Detalji</a>
                    </div>
                </div>
                <?php
            }
        ?>

    </div>

</section>

</body>
</html>

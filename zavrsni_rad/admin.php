<?php
session_start();
include "db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

if(isset($_POST['add'])){

    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $description = $_POST['description'];

    $sql = "INSERT INTO products
    (name,price,quantity,category,image,description)

    VALUES(
    '$name',
    '$price',
    '$quantity',
    '$category',
    '$image',
    '$description'
    )";

    mysqli_query($conn,$sql);

    echo "
    <script>
    alert('Artikl dodan!');
    window.location.href = window.location.href; // Osvježava stranicu da se spriječi ponovno slanje na F5
    </script>
    ";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


       <header>
          <div class="logo">PC SHOP</div>

          <nav>
              <a href="index.php">Početna</a>
              <a href="komponente.php">Komponente</a>
              <a href="gaming.php">Gaming</a>
              <a href="laptopi.php">Laptopi</a>
              <a href="kontakt.html">Kontakt</a>
              <a href="admin.php" class="active" style="color: #ffc107;">Admin Panel</a>
          </nav>

          <div class="header-buttons" style="display: flex; align-items: center; gap: 10px;">
              <span style="color: #fff;">Bok, admin</span>
              <button class="btn btn-login" style="background-color: #dc3545;" onclick="window.location.href='logout.php';">Odjava</button>
              <button class="btn btn-cart">Košarica</button>
          </div>
       </header>

       <section class="container">

              <div class="contact-box">

                     <h2 class="section-title">Dodaj artikl</h2>

                            <form method="POST">

                            <input type="text"
                                   name="name"
                                   placeholder="Naziv artikla"
                                   required>

                            <input type="number"
                                   name="price"
                                   placeholder="Cijena"
                                   required>

                            <input type="number"
                                   name="quantity"
                                   placeholder="Količina"
                                   required>

                            <input type="text"
                                   name="category"
                                   placeholder="Kategorija"
                                   required>

                            <input type="text"
                                   name="image"
                                   placeholder="Image URL"
                                   required>

                            <textarea name="description"
                            placeholder="Opis proizvoda"></textarea>

                            <button class="btn"
                                   name="add">
                            Dodaj artikl
                            </button>

                     </form>

              </div>

       </section>

</body>
</html>

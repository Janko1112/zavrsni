<?php
session_start();
include "db.php";

// Zaštita: Ako korisnik nije ulogiran, preusmjeri ga
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Provjeravamo je li proslijeđen ID stavke iz košarice
if (isset($_GET['id'])) {
    $cart_id = mysqli_real_escape_string($conn, $_GET['id']);
    $username = $_SESSION['username'];

    // Sigurnosni upit: Brišemo artikl samo ako pripada tom korisniku
    $sql = "DELETE FROM cart WHERE id = '$cart_id' AND user_username = '$username'";
    
    if (mysqli_query($conn, $sql)) {
        echo "
        <script>
        alert('Artikl je uklonjen iz košarice.');
        window.location.href = 'cart.php';
        </script>
        ";
    } else {
        echo "Greška prilikom brisanja: " . mysqli_error($conn);
    }
} else {
    header("Location: cart.php");
}
?>

<?php
include_once "helpers.php";
pokreni_sesiju();
unset($_SESSION['kupon']);
unset($_SESSION['kupon_greska']);
header("Location: cart.php");
exit();

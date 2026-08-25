<?php
include_once "helpers.php";
pokreni_sesiju();
$_SESSION['compare'] = [];
header("Location: compare.php");
exit();

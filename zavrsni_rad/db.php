<?php

mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect(
    "sql104.infinityfree.com",
    "if0_42728150",
    "D0mi190504",
    "if0_42728150_pc_shop"
);

if(!$conn){
    die("Connection failed");
}

mysqli_set_charset($conn, "utf8mb4");

?>


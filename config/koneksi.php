<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_koni";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal bre: " . mysqli_connect_error());
}

// Base URL 
$base_url = "http://localhost/koni-app/";
?>
<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { exit; }

$type = $_GET['type'];   // atlet / pelatih / wasit
$action = $_GET['action']; // verify / reject
$id = $_GET['id'];

$status = ($action == 'verify') ? 'verified' : 'rejected';

// Tentukan tabel dan primary key-nya
if ($type == 'atlet') {
    $table = 'atlet';
    $pk = 'id_atlet';
} elseif ($type == 'pelatih') {
    $table = 'pelatih';
    $pk = 'id_pelatih';
} elseif ($type == 'wasit') {
    $table = 'wasit';
    $pk = 'id_wasit';
} else {
    die("Tipe data tidak valid!");
}

$query = "UPDATE $table SET status_verifikasi = '$status' WHERE $pk = '$id'";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Status berhasil diupdate!'); window.location='index.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
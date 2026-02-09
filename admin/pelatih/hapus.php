<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];

// Langsung hapus (program latihan bakal kehapus otomatis karena cascade)
$delete = mysqli_query($conn, "DELETE FROM pelatih WHERE id_pelatih = '$id'");

if ($delete) {
    echo "<script>alert('Data pelatih dan program latihannya berhasil dihapus!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal hapus!'); window.location='index.php';</script>";
}
?>
<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];

if (mysqli_query($conn, "DELETE FROM cabor WHERE id_cabor = '$id'")) {
    echo "<script>alert('Cabor berhasil dihapus! Data terkait ikut terhapus.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal hapus cabor!'); window.location='index.php';</script>";
}
?>
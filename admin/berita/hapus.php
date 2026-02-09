<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];

// Hapus gambar fisik
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM berita WHERE id_berita = '$id'"));
if ($data['gambar'] && file_exists('../../uploads/berita/' . $data['gambar'])) {
    unlink('../../uploads/berita/' . $data['gambar']);
}

// Hapus data
if (mysqli_query($conn, "DELETE FROM berita WHERE id_berita = '$id'")) {
    echo "<script>alert('Berita dihapus!'); window.location='index.php';</script>";
}
?>
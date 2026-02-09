<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];

// Hapus file foto
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto_profil FROM wasit WHERE id_wasit = '$id'"));
if ($data['foto_profil'] && file_exists('../../uploads/foto_profil/' . $data['foto_profil'])) {
    unlink('../../uploads/foto_profil/' . $data['foto_profil']);
}

// Hapus data
if (mysqli_query($conn, "DELETE FROM wasit WHERE id_wasit = '$id'")) {
    echo "<script>alert('Wasit berhasil dihapus!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal hapus!'); window.location='index.php';</script>";
}
?>
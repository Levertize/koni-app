<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];

// 1. Ambil nama file biar bisa dihapus dari folder
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT file_lampiran FROM pengumuman WHERE id_pengumuman = '$id'"));

// 2. Hapus file fisik di folder uploads/pengumuman/
if ($data['file_lampiran'] && file_exists('../../uploads/pengumuman/' . $data['file_lampiran'])) {
    unlink('../../uploads/pengumuman/' . $data['file_lampiran']);
}

// 3. Hapus data di database
if (mysqli_query($conn, "DELETE FROM pengumuman WHERE id_pengumuman = '$id'")) {
    echo "<script>alert('Pengumuman berhasil dihapus!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus!'); window.location='index.php';</script>";
}
?>
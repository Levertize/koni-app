<?php
session_start();
require_once '../../config/koneksi.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil data dulu buat dapet nama file foto
    $query_select = mysqli_query($conn, "SELECT foto_profil FROM atlet WHERE id_atlet = '$id'");
    $data = mysqli_fetch_assoc($query_select);

    // 2. Hapus file foto fisik di folder uploads kalo ada
    if ($data['foto_profil'] != null) {
        $path_foto = '../../uploads/foto_profil/' . $data['foto_profil'];
        if (file_exists($path_foto)) {
            unlink($path_foto); // Perintah hapus file
        }
    }

    // 3. Hapus data di database
    $delete = mysqli_query($conn, "DELETE FROM atlet WHERE id_atlet = '$id'");

    if ($delete) {
        echo "<script>alert('Data atlet berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>
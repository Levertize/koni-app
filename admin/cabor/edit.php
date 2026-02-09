<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cabor WHERE id_cabor = '$id'"));

if (!$data) { echo "<script>alert('Data ga ketemu!'); window.location='index.php';</script>"; exit; }

if (isset($_POST['update'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $lokasi = htmlspecialchars($_POST['lokasi']);

    $query = "UPDATE cabor SET nama_cabor='$nama', lokasi_latihan='$lokasi' WHERE id_cabor='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Cabor Berhasil Diupdate!'); window.location='index.php';</script>";
    } else {
        $error_msg = "Gagal update: " . mysqli_error($conn);
    }
}

$title = "Edit Cabor";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-purple-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Cabor</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Update Data</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Cabang Olahraga</label>
                <input type="text" name="nama" value="<?= $data['nama_cabor'] ?>" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Latihan / Info SK</label>
                <textarea name="lokasi" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition"><?= $data['lokasi_latihan'] ?></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="index.php" class="px-6 py-3 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" name="update" class="px-8 py-3 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-700 transition shadow-lg shadow-purple-200">Update</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
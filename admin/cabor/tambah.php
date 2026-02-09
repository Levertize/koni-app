<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

if (isset($_POST['tambah'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $lokasi = htmlspecialchars($_POST['lokasi']);

    // Cek duplikat
    $cek = mysqli_query($conn, "SELECT * FROM cabor WHERE nama_cabor = '$nama'");
    if(mysqli_num_rows($cek) > 0) {
        $error_msg = "Cabor ini udah ada, bre!";
    } else {
        $query = "INSERT INTO cabor (nama_cabor, lokasi_latihan) VALUES ('$nama', '$lokasi')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Cabor berhasil ditambahkan!'); window.location='index.php';</script>";
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
    }
}

$title = "Tambah Cabor";
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
            <h1 class="text-2xl font-bold text-gray-800">Tambah Cabor</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Input Cabang Olahraga Baru</p>
        </div>
    </div>

    <?php if(isset($error_msg)): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Cabang Olahraga</label>
                <input type="text" name="nama" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Contoh: Sepak Bola, Renang...">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Latihan / Info SK</label>
                <textarea name="lokasi" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition" placeholder="Lokasi pemusatan latihan atau Nomor SK..."></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="index.php" class="px-6 py-3 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" name="tambah" class="px-8 py-3 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-700 transition shadow-lg shadow-purple-200">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
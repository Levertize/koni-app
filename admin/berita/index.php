<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$title = "Kelola Berita";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

$query = "SELECT * FROM berita ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Artikel & Berita</h1>
        <p class="text-sm text-gray-500">Publish informasi terbaru ke halaman depan.</p>
    </div>
    <a href="tambah.php" class="bg-pink-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:bg-pink-700 transition flex items-center gap-2 font-bold text-sm">
        <i class="fa-solid fa-pen-nib"></i> Tulis Berita
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition">
        <div class="h-48 overflow-hidden relative">
            <?php if($row['gambar']): ?>
                <img src="../../uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            <?php else: ?>
                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                    <i class="fa-regular fa-image text-4xl"></i>
                </div>
            <?php endif; ?>
            <div class="absolute top-3 left-3">
                <span class="bg-white/90 backdrop-blur text-pink-600 text-[10px] font-bold px-2 py-1 rounded uppercase shadow-sm">
                    <?= $row['kategori'] ?>
                </span>
            </div>
        </div>
        <div class="p-5">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                <i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($row['created_at'])) ?>
            </div>
            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 h-12 leading-snug"><?= $row['judul'] ?></h3>
            <p class="text-xs text-gray-500 line-clamp-3 mb-4 h-12"><?= strip_tags($row['isi_berita']) ?></p>
            
            <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                <a href="edit.php?id=<?= $row['id_berita'] ?>" class="text-xs font-bold text-blue-600 hover:underline">Edit</a>
                <a href="hapus.php?id=<?= $row['id_berita'] ?>" onclick="return confirm('Hapus berita ini?')" class="text-xs font-bold text-red-600 hover:underline">Hapus</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php require_once '../../layouts/footer.php'; ?>
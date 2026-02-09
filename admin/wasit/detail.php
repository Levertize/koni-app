<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];

// 1. Ambil Data Wasit
$query = "SELECT wasit.*, cabor.nama_cabor 
          FROM wasit 
          LEFT JOIN cabor ON wasit.id_cabor = cabor.id_cabor 
          WHERE id_wasit = '$id'";
$wasit = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$wasit) { echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>"; exit; }

$title = "Detail Wasit";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// --- DATA PREPARATION (SAFE MODE) ---
$hp = $wasit['no_hp'] ?? $wasit['nohp'] ?? $wasit['telepon'] ?? '';
$nik = $wasit['nik'] ?? '-';
$alamat = $wasit['alamat'] ?? $wasit['alamat_lengkap'] ?? '-';
$sertifikasi = $wasit['sertifikasi'] ?? $wasit['lisensi'] ?? '-';
$gender = $wasit['jenis_kelamin'] ?? 'L';

// Cek TTL (handle nama kolom beda-beda)
$tempat = $wasit['tempat_lahir'] ?? $wasit['tmp_lahir'] ?? '';
$tanggal = $wasit['tanggal_lahir'] ?? $wasit['tgl_lahir'] ?? null;

if ($tanggal && $tanggal != '0000-00-00') {
    $ttl_show = $tempat . ', ' . date('d M Y', strtotime($tanggal));
} else {
    $ttl_show = ($tempat ? $tempat : '-') . ', -';
}
?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-yellow-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profil Wasit</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Detail Personil & Sertifikasi</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="h-24 bg-gradient-to-r from-yellow-400 to-orange-500"></div>
                
                <div class="px-6 relative">
                    <div class="w-20 h-20 rounded-full bg-white p-1 absolute -top-10 left-6 shadow-md">
                        <?php if(!empty($wasit['foto_profil'])): ?>
                            <img src="../../uploads/foto_profil/<?= $wasit['foto_profil'] ?>" class="w-full h-full rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-400">
                                <?= substr($wasit['nama_lengkap'], 0, 1) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-12 px-6 pb-6">
                    <h2 class="text-xl font-bold text-gray-800"><?= $wasit['nama_lengkap'] ?></h2>
                    <p class="text-sm text-gray-500 mb-4 font-medium"><?= $wasit['nama_cabor'] ?? 'Tanpa Cabor' ?></p>

                    <div class="space-y-3 pt-2 border-t border-gray-50">
                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">NIK</span>
                            <span class="text-xs font-bold text-gray-700 w-2/3 text-right"><?= $nik ?></span>
                        </div>
                        
                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Jenis Kelamin</span>
                            <span class="text-xs font-bold text-gray-700 w-2/3 text-right">
                                <?= ($gender == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Sertifikasi</span>
                            <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded w-fit ml-auto border border-yellow-100">
                                <?= $sertifikasi ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">TTL</span>
                            <span class="text-xs font-bold text-gray-700 w-2/3 text-right"><?= $ttl_show ?></span>
                        </div>

                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Alamat</span>
                            <span class="text-xs font-medium text-gray-700 w-2/3 text-right leading-tight">
                                <?= $alamat ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-2">
                        <a href="edit.php?id=<?= $wasit['id_wasit'] ?>" class="flex-1 bg-gray-50 text-gray-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-gray-100 transition">Edit Profil</a>
                        
                        <?php if($hp): ?>
                            <a href="https://wa.me/<?= $hp ?>?text=Halo Wasit <?= $wasit['nama_lengkap'] ?>" target="_blank" class="flex-1 bg-green-50 text-green-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-green-100 transition">
                                <i class="fa-brands fa-whatsapp"></i> Chat
                            </a>
                        <?php else: ?>
                            <button disabled class="flex-1 bg-gray-100 text-gray-400 py-2.5 rounded-xl text-xs font-bold text-center cursor-not-allowed">
                                No HP -
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-flag-checkered text-yellow-500 text-xl"></i>
                <h3 class="font-bold text-gray-800 text-lg">Riwayat Penugasan</h3>
            </div>

            <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-clipboard-list text-3xl text-gray-300"></i>
                </div>
                <h4 class="font-bold text-gray-600">Belum Ada Riwayat Tugas</h4>
                <p class="text-xs text-gray-400 mt-1">Data penugasan pertandingan belum tersedia.</p>
                
                <div class="mt-6">
                    <button disabled class="bg-gray-100 text-gray-400 px-4 py-2 rounded-lg text-xs font-bold cursor-not-allowed border border-gray-200">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Tugas (Segera Hadir)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];

// 1. Ambil Data Cabor Utama
$cabor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cabor WHERE id_cabor = '$id'"));
if(!$cabor) { echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>"; exit; }

// 2. Ambil List Personil
$atlet = mysqli_query($conn, "SELECT * FROM atlet WHERE id_cabor='$id' ORDER BY nama_lengkap ASC");
$pelatih = mysqli_query($conn, "SELECT * FROM pelatih WHERE id_cabor='$id' ORDER BY nama_lengkap ASC");
$wasit = mysqli_query($conn, "SELECT * FROM wasit WHERE id_cabor='$id' ORDER BY nama_lengkap ASC");

// Hitung Total
$jml_atlet = mysqli_num_rows($atlet);
$jml_pelatih = mysqli_num_rows($pelatih);
$jml_wasit = mysqli_num_rows($wasit);

$title = "Detail Cabor " . $cabor['nama_cabor'];
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-purple-600 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Cabor: <?= $cabor['nama_cabor'] ?></h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                    Ketua: <?= $cabor['ketua_cabor'] ?? '-' ?> • Lokasi: <?= $cabor['lokasi_latihan'] ?? '-' ?>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[500px]">
            <div class="p-4 border-b border-gray-100 bg-red-50 rounded-t-2xl flex justify-between items-center">
                <h3 class="font-bold text-red-700 flex items-center gap-2">
                    <i class="fa-solid fa-running"></i> Data Atlet
                </h3>
                <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $jml_atlet ?></span>
            </div>
            
            <div class="flex-1 overflow-y-auto p-2 space-y-2 custom-scrollbar">
                <?php if($jml_atlet > 0): ?>
                    <?php while($a = mysqli_fetch_assoc($atlet)): ?>
                    <div class="flex items-center gap-3 p-3 hover:bg-red-50 rounded-xl border border-transparent hover:border-red-100 transition group cursor-default">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                            <?php if(!empty($a['foto_profil'])): ?>
                                <img src="../../uploads/foto_profil/<?= $a['foto_profil'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold"><?= substr($a['nama_lengkap'],0,1) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-800 truncate"><?= $a['nama_lengkap'] ?></h4>
                            <p class="text-[10px] text-gray-500 truncate">NIK: <?= $a['nik'] ?></p>
                        </div>
                        <a href="../atlet/detail.php?id=<?= $a['id_atlet'] ?>" class="text-gray-300 hover:text-red-600 transition" title="Lihat Detail">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="h-full flex flex-col items-center justify-center text-gray-300">
                        <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                        <p class="text-xs font-bold">Belum ada atlet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[500px]">
            <div class="p-4 border-b border-gray-100 bg-blue-50 rounded-t-2xl flex justify-between items-center">
                <h3 class="font-bold text-blue-700 flex items-center gap-2">
                    <i class="fa-solid fa-user-tie"></i> Data Pelatih
                </h3>
                <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $jml_pelatih ?></span>
            </div>
            
            <div class="flex-1 overflow-y-auto p-2 space-y-2 custom-scrollbar">
                <?php if($jml_pelatih > 0): ?>
                    <?php while($p = mysqli_fetch_assoc($pelatih)): ?>
                    <div class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-xl border border-transparent hover:border-blue-100 transition group cursor-default">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                            <?php if(!empty($p['foto_profil'])): ?>
                                <img src="../../uploads/foto_profil/<?= $p['foto_profil'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold"><?= substr($p['nama_lengkap'],0,1) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-800 truncate"><?= $p['nama_lengkap'] ?></h4>
                            <p class="text-[10px] text-gray-500 truncate">
                                <?= !empty($p['lisensi_grade']) ? 'Lic: '.$p['lisensi_grade'] : 'Tanpa Lisensi' ?>
                            </p>
                        </div>
                        <a href="../pelatih/detail.php?id=<?= $p['id_pelatih'] ?>" class="text-gray-300 hover:text-blue-600 transition" title="Lihat Detail">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="h-full flex flex-col items-center justify-center text-gray-300">
                        <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                        <p class="text-xs font-bold">Belum ada pelatih</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[500px]">
            <div class="p-4 border-b border-gray-100 bg-yellow-50 rounded-t-2xl flex justify-between items-center">
                <h3 class="font-bold text-yellow-700 flex items-center gap-2">
                    <i class="fa-solid fa-flag-checkered"></i> Data Wasit
                </h3>
                <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $jml_wasit ?></span>
            </div>
            
            <div class="flex-1 overflow-y-auto p-2 space-y-2 custom-scrollbar">
                <?php if($jml_wasit > 0): ?>
                    <?php while($w = mysqli_fetch_assoc($wasit)): ?>
                    <div class="flex items-center gap-3 p-3 hover:bg-yellow-50 rounded-xl border border-transparent hover:border-yellow-100 transition group cursor-default">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                            <?php if(!empty($w['foto_profil'])): ?>
                                <img src="../../uploads/foto_profil/<?= $w['foto_profil'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold"><?= substr($w['nama_lengkap'],0,1) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-800 truncate"><?= $w['nama_lengkap'] ?></h4>
                            <p class="text-[10px] text-gray-500 truncate">
                                <?= !empty($w['lisensi_grade']) ? 'Sert: '.$w['lisensi_grade'] : 'Tanpa Sertifikat' ?>
                            </p>
                        </div>
                        <a href="../wasit/detail.php?id=<?= $w['id_wasit'] ?>" class="text-gray-300 hover:text-yellow-600 transition" title="Lihat Detail">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="h-full flex flex-col items-center justify-center text-gray-300">
                        <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                        <p class="text-xs font-bold">Belum ada wasit</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<?php require_once '../../layouts/footer.php'; ?>
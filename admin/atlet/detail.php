<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];

// 1. Ambil Data Atlet
$atlet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT atlet.*, cabor.nama_cabor FROM atlet LEFT JOIN cabor ON atlet.id_cabor = cabor.id_cabor WHERE id_atlet = '$id'"));

if (!$atlet) { echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>"; exit; }

// 2. Ambil Riwayat Prestasi
$prestasi = mysqli_query($conn, "SELECT * FROM prestasi WHERE id_atlet = '$id' ORDER BY tahun DESC");

$title = "Detail Atlet";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// --- LOGIC PERBAIKAN DATA (SAFE DATA) ---
// Cek nama kolom (jaga-jaga kalo beda nama di database)
$tempat = $atlet['tempat_lahir'] ?? $atlet['tmp_lahir'] ?? '-'; 
$tanggal = $atlet['tanggal_lahir'] ?? $atlet['tgl_lahir'] ?? null;

// Format Tanggal (Kalo datanya ada)
if ($tanggal && $tanggal != '0000-00-00') {
    $ttl_show = $tempat . ', ' . date('d M Y', strtotime($tanggal));
} else {
    $ttl_show = $tempat . ', -'; // Kalo tanggal kosong
}

// Data lain (Safe Check)
$nik = $atlet['nik'] ?? '-';
$alamat = $atlet['alamat'] ?? '-';
$hp = $atlet['no_hp'] ?? $atlet['nohp'] ?? ''; // Cek no_hp atau nohp
$status_verifikasi = $atlet['status_verifikasi'] ?? 'pending';
$gender = $atlet['jenis_kelamin'] ?? 'L';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-red-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profil & Prestasi</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Monitoring Perkembangan Atlet</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="h-24 bg-gradient-to-r from-red-600 to-orange-600"></div>
                
                <div class="px-6 relative">
                    <div class="w-20 h-20 rounded-full bg-white p-1 absolute -top-10 left-6 shadow-md">
                        <?php if(!empty($atlet['foto_profil'])): ?>
                            <img src="../../uploads/foto_profil/<?= $atlet['foto_profil'] ?>" class="w-full h-full rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-400">
                                <?= substr($atlet['nama_lengkap'], 0, 1) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-12 px-6 pb-6">
                    <h2 class="text-xl font-bold text-gray-800"><?= $atlet['nama_lengkap'] ?></h2>
                    <p class="text-sm text-gray-500 mb-4 font-medium"><?= $atlet['nama_cabor'] ?? 'Tanpa Cabor' ?></p>

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

                        <div class="flex justify-between items-center py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Status</span>
                            <span class="text-xs font-bold <?= $status_verifikasi == 'verified' ? 'text-green-600' : 'text-orange-500' ?> w-2/3 text-right">
                                <?= ucfirst($status_verifikasi) ?>
                            </span>
                        </div>

                    </div>
                    
                    <div class="mt-6 flex gap-2">
                        <a href="edit.php?id=<?= $atlet['id_atlet'] ?>" class="flex-1 bg-gray-50 text-gray-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-gray-100 transition">Edit Profil</a>
                        
                        <?php if($hp): ?>
                            <a href="https://wa.me/<?= $hp ?>?text=Halo Atlet <?= $atlet['nama_lengkap'] ?>" target="_blank" class="flex-1 bg-green-50 text-green-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-green-100 transition">
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
                <i class="fa-solid fa-trophy text-yellow-500 text-xl"></i>
                <h3 class="font-bold text-gray-800 text-lg">Riwayat Prestasi</h3>
            </div>

            <?php if(mysqli_num_rows($prestasi) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php while($p = mysqli_fetch_assoc($prestasi)): ?>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400 group-hover:bg-yellow-500 transition"></div>
                        <div class="pl-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-base line-clamp-1"><?= $p['nama_kejuaraan'] ?></h4>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="text-[10px] font-bold bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded border border-yellow-100">
                                            <i class="fa-solid fa-medal mr-1"></i> <?= $p['peringkat'] ?>
                                        </span>
                                        <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">
                                            <?= $p['tingkat'] ?>
                                        </span>
                                        <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded">
                                            <?= $p['tahun'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-50">
                                <a href="../../uploads/sertifikat/<?= $p['file_sertifikat'] ?>" target="_blank" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-file-contract"></i> Lihat Sertifikat
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
                    <i class="fa-solid fa-medal text-4xl text-gray-300 mb-3"></i>
                    <h4 class="font-bold text-gray-600">Belum Ada Prestasi</h4>
                    <p class="text-xs text-gray-400 mt-1">Atlet ini belum mengunggah data kejuaraan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
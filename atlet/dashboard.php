<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'atlet') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 1. Ambil Data Atlet (Safe Mode)
$q_profil = mysqli_query($conn, "SELECT atlet.*, cabor.nama_cabor FROM atlet LEFT JOIN cabor ON atlet.id_cabor = cabor.id_cabor WHERE id_atlet = '$id_user'");
$data = ($q_profil) ? mysqli_fetch_assoc($q_profil) : ['nama_lengkap' => 'User', 'nama_cabor' => '-', 'status_verifikasi' => '-'];

// 2. Hitung Statistik (Safe Mode)
$q_prestasi = mysqli_query($conn, "SELECT * FROM prestasi WHERE id_atlet='$id_user'");
$total_prestasi = ($q_prestasi) ? mysqli_num_rows($q_prestasi) : 0;

$title = "Dashboard Atlet";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="mb-10 relative group">
    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white shadow-2xl shadow-blue-500/40 overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold mb-2 tracking-tight">Halo, <?= explode(' ', $data['nama_lengkap'])[0] ?>! 👋</h2>
            <p class="text-blue-100 mb-6 text-lg font-medium">Fokus latihan, raih prestasi tertinggi!</p>
            
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl font-bold shadow-lg">
                    <i class="fa-solid fa-medal mr-2 text-blue-200"></i> <?= $data['nama_cabor'] ?>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl font-bold shadow-lg">
                    <i class="fa-solid fa-shield-halved mr-2 text-blue-200"></i> Status: <?= ucfirst($data['status_verifikasi']) ?>
                </div>
            </div>
        </div>
        <i class="fa-solid fa-person-running absolute -right-6 -bottom-8 text-[12rem] text-white/10 rotate-12 transition-transform duration-500 group-hover:scale-110"></i>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    
    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-yellow-100 border border-yellow-50 hover:-translate-y-2 transition-all duration-300 group">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-3xl shadow-inner group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Prestasi</h3>
                <p class="text-3xl font-black text-gray-800 group-hover:text-yellow-600 transition-colors"><?= $total_prestasi ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-indigo-100 border border-indigo-50 hover:-translate-y-2 transition-all duration-300 group">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                <i class="fa-solid fa-medal"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Cabang Olahraga</h3>
                <p class="text-xl font-black text-gray-800 group-hover:text-indigo-600 transition-colors truncate w-32"><?= $data['nama_cabor'] ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-green-100 border border-green-50 hover:-translate-y-2 transition-all duration-300 group">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-3xl shadow-inner group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Verifikasi</h3>
                <p class="text-xl font-black text-gray-800 uppercase group-hover:text-green-600 transition-colors"><?= $data['status_verifikasi'] ?></p>
            </div>
        </div>
    </div>
</div>

<div class="relative group cursor-pointer">
    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-2xl blur opacity-30 group-hover:opacity-70 transition duration-200"></div>
    <div class="relative bg-white rounded-2xl p-8 text-center border border-gray-100">
        <h3 class="font-bold text-gray-800 text-xl mb-2">Punya Prestasi Baru?</h3>
        <p class="text-gray-500 mb-6">Segera laporkan prestasimu agar tercatat di database KONI.</p>
        <a href="prestasi.php" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-105 transition-all duration-300">
            <i class="fa-solid fa-trophy"></i> Input Prestasi
        </a>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
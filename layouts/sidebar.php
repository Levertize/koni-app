<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'guest';
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$root = (isset($base_url)) ? $base_url : "/koni-app/";
?>

<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity opacity-0"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto flex flex-col flex-shrink-0">
    
    <div class="p-6 border-b border-gray-100 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
        <img src="/koni-app/assets/img/logo.png" alt="Logo KONI" class="h-8 w-auto" alt="Logo">
            <div>
                <h1 class="font-bold text-gray-900 leading-none tracking-tight">SiMALT</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">KONI Banyumas</p>
            </div>
        </div>
        
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-red-500 transition">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-1">
        
        <?php if ($role == 'super_admin' || $role == 'admin_cabor'): ?>
            
            <p class="text-[10px] font-bold text-gray-400 px-3 py-2 uppercase tracking-widest mb-1">Panel Admin</p>
            
            <a href="<?= $root ?>admin/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'dashboard.php' ? 'bg-red-50 text-red-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
            </a>

            <a href="<?= $root ?>admin/verifikasi/index.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'verifikasi' ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-user-check w-5 text-center"></i> Verifikasi Data
                </div>
                <?php if(isset($total_pending) && $total_pending > 0): ?>
                    <span class="bg-orange-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold shadow-sm animate-pulse"><?= $total_pending ?></span>
                <?php endif; ?>
            </a>

            <p class="text-[10px] font-bold text-gray-400 px-3 py-2 mt-6 uppercase tracking-widest mb-1">Konten Website</p>
            
            <a href="<?= $root ?>admin/berita/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'berita' ? 'bg-pink-50 text-pink-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-newspaper w-5 text-center text-pink-500"></i> Publikasi Berita
            </a>

            <a href="<?= $root ?>admin/pengumuman/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'pengumuman' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-bullhorn w-5 text-center text-blue-500"></i> Pengumuman
            </a>        

            <p class="text-[10px] font-bold text-gray-400 px-3 py-2 mt-6 uppercase tracking-widest mb-1">Master Data</p>
            
            <a href="<?= $root ?>admin/atlet/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'atlet' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-person-running w-5 text-center text-red-500"></i> Data Atlet
            </a>
            
            <a href="<?= $root ?>admin/pelatih/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'pelatih' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-user-tie w-5 text-center text-blue-500"></i> Data Pelatih
            </a>
            
            <a href="<?= $root ?>admin/wasit/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'wasit' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-flag-checkered w-5 text-center text-yellow-500"></i> Data Wasit
            </a>

            <a href="<?= $root ?>admin/cabor/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_dir == 'cabor' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-medal w-5 text-center text-purple-500"></i> Data Cabor
            </a>

        <?php elseif ($role == 'atlet'): ?>
            <p class="text-[10px] font-bold text-gray-400 px-3 py-2 uppercase tracking-widest mb-1">Menu Atlet</p>
            <a href="<?= $root ?>atlet/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'dashboard.php' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-house w-5 text-center"></i> Dashboard</a>
            <a href="<?= $root ?>atlet/prestasi.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'prestasi.php' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-trophy w-5 text-center"></i> Prestasi Saya</a>
            <a href="<?= $root ?>atlet/profil.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'profil.php' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-user-gear w-5 text-center"></i> Edit Profil</a>

        <?php elseif ($role == 'pelatih'): ?>
            <p class="text-[10px] font-bold text-gray-400 px-3 py-2 uppercase tracking-widest mb-1">Menu Pelatih</p>
            <a href="<?= $root ?>pelatih/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'dashboard.php' ? 'bg-emerald-50 text-emerald-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-chalkboard-user w-5 text-center"></i> Dashboard</a>
            <a href="<?= $root ?>pelatih/program.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'program.php' ? 'bg-emerald-50 text-emerald-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-clipboard-list w-5 text-center"></i> Program Latihan</a>
            
            <a href="<?= $root ?>pelatih/lisensi.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'lisensi.php' ? 'bg-emerald-50 text-emerald-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-certificate w-5 text-center"></i> Lisensi Kepelatihan</a>

            <a href="<?= $root ?>pelatih/profil.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'profil.php' ? 'bg-emerald-50 text-emerald-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>"><i class="fa-solid fa-user-gear w-5 text-center"></i> Edit Profil</a>

        <?php elseif ($role == 'wasit'): ?>
            <p class="text-[10px] font-bold text-gray-400 px-3 py-2 uppercase tracking-widest mb-1">Menu Wasit</p>
            
            <a href="<?= $root ?>wasit/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'dashboard.php' ? 'bg-yellow-50 text-yellow-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
            </a>

            <a href="<?= $root ?>wasit/tugas.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'tugas.php' ? 'bg-yellow-50 text-yellow-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i> Riwayat Tugas
            </a>
            
            <a href="<?= $root ?>wasit/lisensi.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'lisensi.php' ? 'bg-yellow-50 text-yellow-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-id-card w-5 text-center"></i> Lisensi Wasit
            </a>

            <a href="<?= $root ?>wasit/profil.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition <?= $current_page == 'profil.php' ? 'bg-yellow-50 text-yellow-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?>">
                <i class="fa-solid fa-user-gear w-5 text-center"></i> Edit Profil
            </a>
        <?php endif; ?>

    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="<?= $root ?>logout.php" onclick="return confirm('Yakin mau logout?')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-red-500 hover:bg-red-50 transition group">
            <i class="fa-solid fa-right-from-bracket w-5 text-center group-hover:translate-x-1 transition-transform"></i> 
            Keluar
        </a>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        // Cek apakah sidebar sedang tersembunyi
        if (sidebar.classList.contains('-translate-x-full')) {
            // Buka Sidebar
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            // Tutup Sidebar
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }
</script>
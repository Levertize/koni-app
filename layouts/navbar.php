<div class="flex-1 flex flex-col min-w-0 overflow-hidden">
    <!-- Top Navbar -->
     
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0 relative z-20">
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-red-600 transition">
    <i class="fa-solid fa-bars"></i>
</button>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest hidden md:block">
                <?= $title ?? 'Sistem Informasi Manajemen Atlet' ?>
            </h2>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden md:flex flex-col text-right">
                <p class="text-sm font-bold text-gray-800 leading-none"><?= $_SESSION['nama'] ?></p>
                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-tight"><?= $_SESSION['role'] ?? 'Administrator' ?></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-800 flex items-center justify-center text-white font-bold shadow-lg">
                <?= substr($_SESSION['nama'], 0, 1) ?>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-8">
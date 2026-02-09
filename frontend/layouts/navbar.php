<?php
// Pastikan koneksi database udah dipanggil di file induk sebelum navbar ini diload
// Kalau belum ada $conn, script di bawah bakal error pas narik headline
?>

<div id="preloader">
    <div id="chibi-icon" class="chibi-bounce mb-4">
        <i class="fa-solid fa-basketball"></i>
    </div>
    <h3 class="font-heading text-xl font-bold text-gray-700 tracking-widest uppercase animate-pulse">
        Memuat Data...
    </h3>
    <p class="text-xs text-gray-400 mt-2">SiMALT KONI Banyumas</p>
</div>

<header class="fixed w-full z-50 top-0 shadow-md">
    
    <div class="bg-[#ffcc00] h-9 flex items-center justify-between px-4 md:px-12">
        <div class="hidden md:flex text-xs font-bold text-yellow-900 gap-6">
            <span><i class="fa-solid fa-phone mr-1"></i> (0281) 636xxx</span>
            <span><i class="fa-solid fa-envelope mr-1"></i> admin@konibanyumas.or.id</span>
        </div>
        <div class="flex gap-4 text-yellow-900 text-sm w-full md:w-auto justify-end md:justify-end justify-center">
            <a href="#" class="hover:text-red-700 transition"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="hover:text-red-700 transition"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="hover:text-red-700 transition"><i class="fa-brands fa-youtube"></i></a>
        </div>
    </div>

    <nav class="bg-white nav-pattern border-b border-gray-200 relative">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex justify-between items-center h-20">
                
                <a href="/koni-app/index.php" class="flex items-center gap-3 group min-w-max">
                <img src="/koni-app/assets/img/logo.png" alt="Logo KONI" class="h-10 md:h-12">
                    <div class="leading-none hidden lg:block">
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tighter uppercase">KONI</h1>
                        <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Kabupaten Banyumas</p>
                    </div>
                    <div class="leading-none lg:hidden">
                        <h1 class="text-xl font-bold text-gray-900 uppercase">KONI</h1>
                        <p class="text-[8px] font-bold text-gray-500 uppercase">Banyumas</p>
                    </div>
                </a>

                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 hover:text-red-600 focus:outline-none p-2">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>

                <div class="hidden lg:flex items-center space-x-2">
                    <div class="relative group h-14 flex items-center">
                        <button class="px-3 py-2 text-sm font-bold text-gray-700 hover:text-red-600 flex items-center gap-1 transition uppercase">
                            PROFIL <i class="fa-solid fa-chevron-down text-[10px] mt-0.5 ml-1"></i>
                        </button>
                        <div class="absolute left-0 top-10 pt-4 w-56 hidden group-hover:block z-50">
                            <div class="bg-white border border-gray-100 rounded shadow-xl mt-2 overflow-hidden">
                                <a href="/koni-app/pages/tentang.php" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-red-50 hover:text-red-600 border-b border-gray-100 uppercase">Tentang KONI</a>
                                <a href="/koni-app/pages/struktur.php" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-red-50 hover:text-red-600 border-b border-gray-100 uppercase">Struktur Organisasi</a>
                                <a href="/koni-app/pages/pengurus.php" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-red-50 hover:text-red-600 border-b border-gray-100 uppercase">Pengurus</a>
                                <a href="/koni-app/pages/atlet.php" class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-red-50 hover:text-red-600 uppercase">Atlet</a>
                            </div>
                        </div>
                    </div>
                    <a href="/koni-app/pages/pengumuman.php" class="px-3 py-2 text-sm font-bold text-gray-700 hover:text-red-600 transition uppercase">PENGUMUMAN</a>
                    <a href="/koni-app/pages/berita.php" class="px-3 py-2 text-sm font-bold text-gray-700 hover:text-red-600 transition uppercase">BERITA & KEGIATAN</a>
                    <a href="/koni-app/pages/statistik.php" class="px-3 py-2 text-sm font-bold text-gray-700 hover:text-red-600 transition uppercase">STATISTIK</a>
                    <a href="/koni-app/pages/kontak.php" class="px-3 py-2 text-sm font-bold text-gray-700 hover:text-red-600 transition uppercase">HUBUNGI KAMI</a>
                    
                    <a href="/koni-app/login.php" class="ml-4 bg-red-700 text-white px-6 py-2 rounded font-bold text-xs hover:bg-red-800 transition shadow flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> LOGIN
                    </a>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200 absolute w-full left-0 z-50 shadow-xl">
            <div class="flex flex-col p-4 space-y-2">
                <div class="border-b border-gray-100 pb-2">
                    <button id="mobile-profil-btn" class="flex justify-between items-center w-full px-4 py-3 text-sm font-bold text-gray-700 uppercase hover:bg-gray-50 rounded">
                        PROFIL <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                    <div id="mobile-profil-menu" class="hidden pl-6 space-y-2 mt-2 bg-gray-50 rounded py-2">
                        <a href="/koni-app/pages/tentang.php" class="block px-4 py-2 text-xs font-bold text-gray-600 hover:text-red-600 uppercase">Tentang KONI</a>
                        <a href="/koni-app/pages/struktur.php" class="block px-4 py-2 text-xs font-bold text-gray-600 hover:text-red-600 uppercase">STRUKTUR ORGANISASI</a>
                        <a href="/koni-app/pages/pengurus.php" class="block px-4 py-2 text-xs font-bold text-gray-600 hover:text-red-600 uppercase">Pengurus</a>
                        <a href="/koni-app/pages/atlet.php" class="block px-4 py-2 text-xs font-bold text-gray-600 hover:text-red-600 uppercase">Atlet</a>
                    </div>
                </div>
                <a href="/koni-app/pages/pengumuman.php" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded uppercase">PENGUMUMAN</a>
                <a href="/koni-app/pages/berita.php" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded uppercase">BERITA</a>
                <a href="/koni-app/pages/kontak.php" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded uppercase">HUBUNGI KAMI</a>
                <a href="/koni-app/login.php" class="block text-center mt-4 bg-red-700 text-white px-6 py-3 rounded font-bold text-sm hover:bg-red-800 transition shadow">
                    <i class="fa-solid fa-lock mr-2"></i> LOGIN SISTEM
                </a>
            </div>
        </div>
    </nav>

    <div class="bg-gray-100 border-b border-gray-200 h-10 flex relative z-40">
        <div class="bg-[#b91c1c] text-white px-3 md:px-6 flex items-center font-heading font-bold text-xs md:text-sm tracking-wide uppercase min-w-max relative z-10">
            HEADLINE
            <div class="absolute right-[-10px] top-0 border-l-[10px] border-l-[#b91c1c] border-t-[40px] border-t-transparent h-full hidden md:block"></div>
        </div>
        <div class="flex-1 flex items-center overflow-hidden marquee-container text-xs md:text-sm font-medium text-gray-700">
            <div class="marquee-content flex gap-12 items-center">
                <?php 
                $q_headline = mysqli_query($conn, "SELECT * FROM berita ORDER BY created_at DESC LIMIT 5");
                if($q_headline && mysqli_num_rows($q_headline) > 0):
                    while($h = mysqli_fetch_assoc($q_headline)): 
                ?>
                    <a href="/koni-app/pages/detail_berita.php?id=<?= $h['id_berita'] ?>" class="flex items-center gap-2 hover:text-red-600 transition group cursor-pointer">
                        <i class="fa-solid fa-bullhorn text-red-600"></i>
                        <strong class="uppercase text-red-700">[<?= $h['kategori'] ?>]</strong> 
                        <?= $h['judul'] ?>
                    </a>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-600"></i>
                        Selamat Datang di Website Resmi KONI Kabupaten Banyumas.
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
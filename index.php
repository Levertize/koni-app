<?php
// 1. KONEKSI DATABASE
require_once 'config/koneksi.php';

// 2. FUNGSI HITUNG DATA
function hitungData($conn, $tabel) {
    $cek = mysqli_query($conn, "SHOW TABLES LIKE '$tabel'");
    if(mysqli_num_rows($cek) > 0){
        $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM $tabel");
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }
    return 0;
}

$jml_atlet  = hitungData($conn, 'atlet');
$jml_pelatih = hitungData($conn, 'pelatih');
$jml_cabor   = hitungData($conn, 'cabor');
$jml_wasit   = hitungData($conn, 'wasit');

// Query Berita & Pengumuman
$query_berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY created_at DESC LIMIT 3"); 
$query_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY created_at DESC LIMIT 5");

// 3. LOAD LAYOUT
require_once 'frontend/layouts/header.php';
require_once 'frontend/layouts/navbar.php';
?>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .news-card:hover .news-img { transform: scale(1.05); }
    .news-card:hover .news-content { transform: translateY(-5px); }
    .scroll-custom::-webkit-scrollbar { width: 4px; }
    .scroll-custom::-webkit-scrollbar-track { background: transparent; }
    .scroll-custom::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .scroll-custom::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<main class="pt-[140px] bg-gray-50 overflow-x-hidden">

    <div class="relative w-full h-[400px] md:h-[600px] mb-16">
        <div class="swiper mySwiper w-full h-full">
            <div class="swiper-wrapper">
                <div class="swiper-slide relative">
                    <img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/40 to-transparent flex items-center">
                        <div class="container mx-auto px-6 md:px-12 mt-10">
                            <div class="max-w-2xl animate-fade-in-up">
                                <span class="bg-red-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4 inline-block shadow-lg shadow-red-600/40">Official Website</span>
                                <h1 class="text-3xl md:text-6xl font-heading font-bold text-white leading-tight mb-6">
                                    Mewujudkan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-yellow-500">Generasi Emas</span><br> Olahraga Banyumas
                                </h1>
                                <p class="text-gray-300 text-sm md:text-lg mb-8 leading-relaxed max-w-lg">
                                    Wadah pembinaan prestasi olahraga yang terintegrasi, profesional, dan transparan menuju pentas dunia.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide relative">
                    <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex items-end">
                        <div class="container mx-auto px-6 md:px-12 pb-20 text-center">
                            <span class="bg-blue-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4 inline-block">Fasilitas Unggulan</span>
                            <h2 class="text-3xl md:text-6xl font-heading font-bold text-white mb-4">GOR Satria Purwokerto</h2>
                            <p class="text-gray-300">Pusat pelatihan terpadu dengan standar nasional.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination !bottom-10"></div>
        </div>
    </div>

    <section class="mb-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 h-full">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="font-heading font-bold text-xl text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-bell text-red-600 animate-pulse"></i> Info Terbaru
                            </h3>
                            <a href="pages/pengumuman.php" class="text-[10px] font-bold text-red-600 hover:bg-red-50 px-3 py-1 rounded-full transition uppercase tracking-wide">Lihat Semua</a>
                        </div>
                        
                        <div class="scroll-custom max-h-[400px] overflow-y-auto pr-2 pl-5 pb-4">
                            <div class="relative border-l-2 border-gray-100 ml-2 space-y-8">
                                <?php if(mysqli_num_rows($query_pengumuman) > 0): ?>
                                    <?php while($p = mysqli_fetch_assoc($query_pengumuman)): ?>
                                    <div class="relative pl-6 group cursor-pointer">
                                        <span class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-white border-[3px] border-red-500 group-hover:scale-125 group-hover:bg-red-500 transition duration-300 z-10"></span>
                                        <a href="pages/pengumuman.php" class="block group-hover:-translate-y-1 transition duration-300">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">
                                                <?= date('d M Y', strtotime($p['created_at'])) ?>
                                            </span>
                                            <h4 class="text-sm font-bold text-gray-800 leading-snug group-hover:text-red-600 transition">
                                                <?= $p['judul'] ?>
                                            </h4>
                                            <?php if($p['file_lampiran']): ?>
                                            <div class="mt-2 inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 text-gray-500 px-2 py-1 rounded text-[10px] font-bold uppercase group-hover:bg-red-50 group-hover:text-red-600 group-hover:border-red-100 transition">
                                                <i class="fa-solid fa-paperclip"></i> Lampiran
                                            </div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="pl-6 text-sm text-gray-400 italic">Belum ada informasi terbaru.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div class="relative h-full min-h-[350px] rounded-2xl overflow-hidden shadow-lg group">
                        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-8 md:p-12">
                            <span class="bg-red-600 w-fit text-white px-3 py-1 rounded text-[10px] font-bold uppercase mb-3 shadow">Profil Organisasi</span>
                            <h2 class="text-2xl md:text-4xl font-heading font-bold text-white mb-4">KONI Kabupaten Banyumas</h2>
                            <p class="text-gray-200 text-sm md:text-base line-clamp-2 md:line-clamp-none mb-6 max-w-2xl leading-relaxed">
                                Satu-satunya organisasi yang berwenang dan bertanggung jawab mengelola, membina, mengembangkan, dan mengkoordinasikan seluruh pelaksanaan kegiatan olahraga prestasi setiap anggota di Kabupaten Banyumas.
                            </p>
                            <a href="pages/tentang.php" class="inline-flex items-center gap-3 text-white font-bold hover:text-red-400 transition group/btn">
                                <span class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur group-hover/btn:bg-red-600 transition">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </span>
                                <span>Baca Selengkapnya</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="berita" class="mb-0 bg-white py-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <span class="text-red-600 font-bold tracking-widest text-xs uppercase mb-2 block">Update Terkini</span>
                    <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-800">Berita & Kegiatan</h2>
                </div>
                <a href="pages/berita.php" class="text-sm font-bold text-gray-600 hover:text-red-600 flex items-center gap-2 transition px-4 py-2 bg-gray-50 rounded-full shadow-sm hover:shadow-md border border-gray-200">
                    Lihat Arsip Berita <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <?php if(mysqli_num_rows($query_berita) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while($b = mysqli_fetch_assoc($query_berita)): ?>
                    <div class="bg-gray-50 rounded-2xl overflow-hidden shadow hover:shadow-xl transition duration-300 news-card flex flex-col h-full group border border-gray-100">
                        <div class="h-56 overflow-hidden relative">
                            <?php if($b['gambar']): ?>
                                <img src="uploads/berita/<?= $b['gambar'] ?>" class="w-full h-full object-cover news-img transition duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400"><i class="fa-regular fa-image text-3xl"></i></div>
                            <?php endif; ?>
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur rounded-lg px-3 py-1.5 shadow text-center">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase"><?= date('M', strtotime($b['created_at'])) ?></span>
                                <span class="block text-lg font-bold text-red-600 leading-none"><?= date('d', strtotime($b['created_at'])) ?></span>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col news-content transition duration-300">
                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-600"></span> <?= $b['kategori'] ?>
                            </span>
                            <h3 class="font-bold text-lg text-gray-800 mb-3 leading-snug hover:text-red-600 transition line-clamp-2">
                                <a href="pages/detail_berita.php?id=<?= $b['id_berita'] ?>"><?= $b['judul'] ?></a>
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-1">
                                <?= strip_tags($b['isi_berita']) ?>
                            </p>
                            <a href="pages/detail_berita.php?id=<?= $b['id_berita'] ?>" class="text-xs font-bold text-gray-800 hover:text-red-600 flex items-center gap-2 mt-auto uppercase tracking-wide">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right-long text-red-600"></i>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-gray-500 italic">Belum ada berita terbaru.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="relative py-24 bg-fixed bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=1920&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-red-900/90"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16 text-white">
                <span class="text-red-300 font-bold tracking-[0.2em] text-xs uppercase mb-2 block">Database Keolahragaan</span>
                <h2 class="text-3xl md:text-5xl font-heading font-bold">Kekuatan Kami</h2>
                <div class="w-20 h-1 bg-white mx-auto mt-6 opacity-30"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm mb-6 border border-white/20 group-hover:bg-white group-hover:text-red-900 text-white transition duration-500 transform group-hover:scale-110">
                        <i class="fa-solid fa-running text-3xl"></i>
                    </div>
                    <h3 class="text-4xl md:text-6xl font-bold text-white counter mb-2"><?= $jml_atlet ?></h3>
                    <p class="text-sm font-bold text-red-200 uppercase tracking-widest">Atlet Binaan</p>
                </div>

                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm mb-6 border border-white/20 group-hover:bg-white group-hover:text-red-900 text-white transition duration-500 transform group-hover:scale-110">
                        <i class="fa-solid fa-user-tie text-3xl"></i>
                    </div>
                    <h3 class="text-4xl md:text-6xl font-bold text-white counter mb-2"><?= $jml_pelatih ?></h3>
                    <p class="text-sm font-bold text-red-200 uppercase tracking-widest">Pelatih Resmi</p>
                </div>

                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm mb-6 border border-white/20 group-hover:bg-white group-hover:text-red-900 text-white transition duration-500 transform group-hover:scale-110">
                        <i class="fa-solid fa-medal text-3xl"></i>
                    </div>
                    <h3 class="text-4xl md:text-6xl font-bold text-white counter mb-2"><?= $jml_cabor ?></h3>
                    <p class="text-sm font-bold text-red-200 uppercase tracking-widest">Cabor Aktif</p>
                </div>

                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm mb-6 border border-white/20 group-hover:bg-white group-hover:text-red-900 text-white transition duration-500 transform group-hover:scale-110">
                        <i class="fa-solid fa-flag-checkered text-3xl"></i>
                    </div>
                    <h3 class="text-4xl md:text-6xl font-bold text-white counter mb-2"><?= $jml_wasit ?></h3>
                    <p class="text-sm font-bold text-red-200 uppercase tracking-widest">Wasit Berlisensi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto px-6 text-center mb-8">
             <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Didukung Oleh Cabang Olahraga</p>
        </div>
        <div class="relative overflow-hidden w-full">
            <div class="absolute top-0 left-0 w-20 h-full bg-gradient-to-r from-white to-transparent z-10"></div>
            <div class="absolute top-0 right-0 w-20 h-full bg-gradient-to-l from-white to-transparent z-10"></div>
            
            <div class="flex items-center gap-16 marquee-content animate-marquee opacity-40 grayscale hover:grayscale-0 transition duration-500">
                <i class="fa-solid fa-futbol text-5xl"></i>
                <i class="fa-solid fa-basketball text-5xl"></i>
                <i class="fa-solid fa-volleyball text-5xl"></i>
                <i class="fa-solid fa-table-tennis-paddle-ball text-5xl"></i>
                <i class="fa-person-swimming text-5xl"></i>
                <i class="fa-person-running text-5xl"></i>
                <i class="fa-solid fa-baseball-bat-ball text-5xl"></i>
                <i class="fa-solid fa-bowling-ball text-5xl"></i>
                <i class="fa-solid fa-futbol text-5xl"></i>
                <i class="fa-solid fa-basketball text-5xl"></i>
                <i class="fa-solid fa-volleyball text-5xl"></i>
                <i class="fa-solid fa-table-tennis-paddle-ball text-5xl"></i>
            </div>
        </div>
    </section>

</main>

<?php require_once 'frontend/layouts/footer.php'; ?>
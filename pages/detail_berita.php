<?php
// Koneksi & Layouts
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';

// Ambil ID dari URL
$id_berita = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Query Ambil Data Berita
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id_berita = '$id_berita'");
$berita = mysqli_fetch_assoc($query);

// Kalo berita gak ketemu, balikin ke halaman berita utama
if (!$berita) {
    echo "<script>window.location='berita.php';</script>";
    exit;
}

// Ambil Berita Lain (Sidebar)
$sidebar_news = mysqli_query($conn, "SELECT * FROM berita WHERE id_berita != '$id_berita' ORDER BY created_at DESC LIMIT 5");
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    
    <div class="bg-white border-b border-gray-200 py-4">
        <div class="container mx-auto px-6 text-xs text-gray-500 uppercase tracking-wide font-bold">
            <a href="../index.php" class="hover:text-red-600">Home</a> 
            <span class="mx-2">/</span> 
            <a href="berita.php" class="hover:text-red-600">Berita</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 truncate"><?= substr($berita['judul'], 0, 30) ?>...</span>
        </div>
    </div>

    <div class="container mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2">
                <article class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    
                    <div class="flex items-center gap-4 mb-4">
                        <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                            <?= $berita['kategori'] ?>
                        </span>
                        <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                            <i class="fa-regular fa-calendar"></i> 
                            <?= date('d F Y', strtotime($berita['created_at'])) ?>
                        </span>
                        <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                            <i class="fa-regular fa-user"></i> 
                            <?= $berita['penulis'] ?>
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-4xl font-heading font-bold text-gray-900 mb-6 leading-tight">
                        <?= $berita['judul'] ?>
                    </h1>

                    <?php if($berita['gambar']): ?>
                        <div class="rounded-xl overflow-hidden mb-8 shadow-lg">
                            <img src="../uploads/berita/<?= $berita['gambar'] ?>" class="w-full h-auto object-cover" alt="<?= $berita['judul'] ?>">
                        </div>
                    <?php endif; ?>

                    <div class="prose max-w-none text-gray-700 leading-relaxed text-justify">
                        <?= html_entity_decode($berita['isi_berita']) ?>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <p class="text-sm font-bold text-gray-500 mb-3">Bagikan Berita Ini:</p>
                        <div class="flex gap-2">
                            <a href="#" class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:opacity-80"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:opacity-80"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center hover:opacity-80"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                </article>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-28">
                    <h3 class="font-heading font-bold text-xl text-gray-900 mb-6 border-l-4 border-red-600 pl-3">
                        Berita Terbaru Lainnya
                    </h3>
                    
                    <div class="space-y-6">
                        <?php while($side = mysqli_fetch_assoc($sidebar_news)): ?>
                        <a href="detail_berita.php?id=<?= $side['id_berita'] ?>" class="flex gap-4 group">
                            <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-gray-200">
                                <?php if($side['gambar']): ?>
                                    <img src="../uploads/berita/<?= $side['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-regular fa-image"></i></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-800 leading-snug group-hover:text-red-600 transition line-clamp-2">
                                    <?= $side['judul'] ?>
                                </h4>
                                <span class="text-[10px] text-gray-400 mt-2 block">
                                    <?= date('d M Y', strtotime($side['created_at'])) ?>
                                </span>
                            </div>
                        </a>
                        <?php endwhile; ?>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <a href="berita.php" class="text-xs font-bold text-red-600 hover:underline uppercase tracking-widest">Lihat Indeks Berita &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
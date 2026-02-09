<?php
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';

// Logic Search & Filter
$where = "";
if(isset($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $where = "WHERE judul LIKE '%$q%' OR isi_berita LIKE '%$q%'";
}

// Query Berita
$query = mysqli_query($conn, "SELECT * FROM berita $where ORDER BY created_at DESC");
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    <div class="bg-white border-b border-gray-200 py-10">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-heading font-bold text-gray-900 uppercase">Berita & Kegiatan</h1>
                <p class="text-gray-500 text-sm mt-1">Update informasi terbaru seputar olahraga Banyumas.</p>
            </div>
            <form action="" method="GET" class="w-full md:w-auto relative">
                <input type="text" name="q" placeholder="Cari berita..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-full w-full md:w-64 focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none transition text-sm">
                <i class="fa-solid fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-6 py-12">
        <?php if(mysqli_num_rows($query) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($row = mysqli_fetch_assoc($query)): ?>
                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition group h-full flex flex-col">
                    <div class="h-56 overflow-hidden relative">
                        <?php if($row['gambar']): ?>
                            <img src="../../uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.src='https://placehold.co/600x400?text=KONI+Banyumas'">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image text-4xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide shadow-md">
                                <?= $row['kategori'] ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                            <span><i class="fa-regular fa-calendar mr-1"></i> <?= date('d M Y', strtotime($row['created_at'])) ?></span>
                            <span>&bull;</span>
                            <span><i class="fa-regular fa-user mr-1"></i> <?= $row['penulis'] ?></span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800 mb-3 leading-snug group-hover:text-red-600 transition line-clamp-2">
                            <a href="#"><?= $row['judul'] ?></a>
                        </h2>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                            <?= strip_tags($row['isi_berita']) ?>
                        </p>
                        <a href="detail_berita.php?id=<?= $row['id_berita'] ?>" class="inline-flex items-center text-red-600 font-bold text-sm hover:underline mt-auto">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-newspaper text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada berita ditemukan</h3>
                <p class="text-gray-500 text-sm">Coba kata kunci lain atau kembali lagi nanti.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
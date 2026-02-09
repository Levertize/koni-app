<?php
// 1. Panggil Koneksi & Layout
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';

// 2. Query Ambil Data Pengumuman (Urut dari yang terbaru)
$query = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY created_at DESC");
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    <div class="container mx-auto px-6 py-10 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-heading font-bold text-gray-900 uppercase">Papan Pengumuman</h1>
            <p class="text-gray-500 mt-2">Informasi resmi, surat edaran, dan hasil seleksi.</p>
            <div class="w-16 h-1 bg-red-600 mx-auto mt-6"></div>
        </div>

        <div class="space-y-4">
            
            <?php if(mysqli_num_rows($query) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($query)): ?>
                    <?php 
                        // Logic buat nentuin ikon & warna berdasarkan jenis file
                        $ext = pathinfo($row['file_lampiran'], PATHINFO_EXTENSION);
                        $icon = 'fa-file-lines'; // Default
                        $badge_color = 'bg-gray-100 text-gray-600';
                        
                        if($ext == 'pdf') { 
                            $icon = 'fa-file-pdf'; 
                            $badge_color = 'bg-red-50 text-red-600 border-red-100'; 
                        } elseif(in_array($ext, ['doc','docx'])) { 
                            $icon = 'fa-file-word'; 
                            $badge_color = 'bg-blue-50 text-blue-600 border-blue-100'; 
                        } elseif(in_array($ext, ['jpg','jpeg','png'])) { 
                            $icon = 'fa-file-image'; 
                            $badge_color = 'bg-purple-50 text-purple-600 border-purple-100'; 
                        }
                    ?>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-l-4 hover:border-l-red-600 transition-all group">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="<?= $badge_color ?> border px-2 py-1 rounded text-[10px] font-bold uppercase flex items-center gap-1">
                                        <i class="fa-solid <?= $icon ?>"></i> <?= strtoupper($ext) ?: 'INFO' ?>
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        <i class="fa-regular fa-clock mr-1"></i> <?= date('d M Y', strtotime($row['created_at'])) ?>
                                    </span>
                                </div>
                                <h3 class="font-bold text-lg text-gray-800 group-hover:text-red-600 transition">
                                    <?= $row['judul'] ?>
                                </h3>
                                <div class="text-sm text-gray-500 mt-2 line-clamp-2 prose prose-sm">
                                    <?= strip_tags(html_entity_decode($row['isi_pengumuman'])) ?>
                                </div>
                            </div>
                            
                            <?php if($row['file_lampiran']): ?>
                                <a href="../uploads/pengumuman/<?= $row['file_lampiran'] ?>" target="_blank" class="px-5 py-2.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-bold hover:bg-red-600 hover:text-white transition flex items-center gap-2 whitespace-nowrap border border-gray-200 hover:border-red-600 shadow-sm">
                                    <i class="fa-solid fa-download"></i> Download File
                                </a>
                            <?php else: ?>
                                <button disabled class="px-5 py-2.5 bg-gray-50 text-gray-300 rounded-lg text-xs font-bold cursor-not-allowed flex items-center gap-2 whitespace-nowrap border border-gray-100">
                                    <i class="fa-solid fa-ban"></i> Tidak Ada File
                                </button>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endwhile; ?>

            <?php else: ?>
                <div class="text-center py-16 border-2 border-dashed border-gray-300 rounded-xl bg-white">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-solid fa-clipboard-list text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Belum Ada Pengumuman</h3>
                    <p class="text-gray-500 text-sm mt-1">Saat ini belum ada informasi terbaru yang diterbitkan.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
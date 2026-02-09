<?php
session_start();
require_once '../../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$title = "Kelola Pengumuman";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// Query Pengumuman
$query = "SELECT * FROM pengumuman ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengumuman & Edaran</h1>
        <p class="text-sm text-gray-500">Upload surat keputusan, jadwal seleksi, atau info penting lainnya.</p>
    </div>
    <a href="tambah.php" class="bg-red-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:bg-red-700 transition flex items-center gap-2 font-bold text-sm">
        <i class="fa-solid fa-bullhorn"></i> Buat Pengumuman
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition flex flex-col h-full">
        
        <div class="h-40 bg-gray-50 border-b border-gray-100 flex items-center justify-center relative overflow-hidden group-hover:bg-red-50 transition duration-500">
            <i class="fa-solid fa-file-lines text-6xl text-gray-200 absolute -bottom-4 -right-4 transform rotate-12 group-hover:text-red-200 transition"></i>
            
            <div class="text-center z-10">
                <?php 
                    // Cek ekstensi file buat nentuin icon
                    $ext = pathinfo($row['file_lampiran'], PATHINFO_EXTENSION);
                    if($ext == 'pdf') { $icon = 'fa-file-pdf text-red-500'; }
                    elseif(in_array($ext, ['doc','docx'])) { $icon = 'fa-file-word text-blue-500'; }
                    elseif(in_array($ext, ['jpg','jpeg','png'])) { $icon = 'fa-file-image text-purple-500'; }
                    else { $icon = 'fa-bullhorn text-gray-400'; } // Default kalo ga ada file
                ?>
                <i class="fa-regular <?= $icon ?> text-5xl mb-2 drop-shadow-sm"></i>
                
                <?php if($row['file_lampiran']): ?>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Lampiran Tersedia
                    </p>
                <?php else: ?>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Info Teks Saja
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-5 flex-1 flex flex-col">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                <i class="fa-regular fa-clock"></i> <?= date('d M Y', strtotime($row['created_at'])) ?>
            </div>
            
            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 h-12 leading-snug group-hover:text-red-600 transition">
                <?= $row['judul'] ?>
            </h3>
            
            <p class="text-xs text-gray-500 line-clamp-3 mb-4 h-12">
                <?= strip_tags($row['isi_pengumuman']) ?>
            </p>
            
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-center">
                <?php if($row['file_lampiran']): ?>
                    <a href="../../uploads/pengumuman/<?= $row['file_lampiran'] ?>" target="_blank" class="text-xs font-bold text-gray-600 hover:text-red-600 bg-gray-100 px-2 py-1 rounded hover:bg-gray-200 transition">
                        <i class="fa-solid fa-download mr-1"></i> File
                    </a>
                <?php else: ?>
                    <span class="text-xs text-gray-300 italic">No File</span>
                <?php endif; ?>

                <div class="flex gap-3">
                    <a href="edit.php?id=<?= $row['id_pengumuman'] ?>" class="text-xs font-bold text-blue-600 hover:underline">Edit</a>
                    <a href="hapus.php?id=<?= $row['id_pengumuman'] ?>" onclick="return confirm('Hapus pengumuman ini?')" class="text-xs font-bold text-red-600 hover:underline">Hapus</a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php if(mysqli_num_rows($result) == 0): ?>
<div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
    <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
    <p class="text-gray-500 font-medium">Belum ada pengumuman yang dibuat.</p>
</div>
<?php endif; ?>

<?php require_once '../../layouts/footer.php'; ?>
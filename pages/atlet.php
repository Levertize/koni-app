<?php
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';

// Ambil data atlet (Join Cabor)
// Pastikan hanya mengambil atlet yang sudah diverifikasi (opsional, hapus WHERE kalau mau tampil semua)
$query = mysqli_query($conn, "SELECT atlet.*, cabor.nama_cabor 
                              FROM atlet 
                              LEFT JOIN cabor ON atlet.id_cabor = cabor.id_cabor 
                              ORDER BY atlet.nama_lengkap ASC");
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-heading font-bold text-gray-900 uppercase">Atlet Kebanggaan</h1>
            <p class="text-gray-500 mt-2">Daftar atlet berprestasi Kabupaten Banyumas</p>
            <div class="w-16 h-1 bg-red-600 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <?php if(mysqli_num_rows($query) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($query)): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition group">
                    <div class="h-64 overflow-hidden relative bg-gray-200">
                        <?php if(!empty($row['foto_profil'])): ?>
                            <img src="../uploads/foto_profil/<?= $row['foto_profil'] ?>" 
                                 class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-500"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x500?text=No+Image';"> 
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                                <i class="fa-solid fa-user text-6xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute top-2 right-2">
                             <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow uppercase">
                                <?= $row['nama_cabor'] ?? 'Umum' ?>
                             </span>
                        </div>
                    </div>
                    
                    <div class="p-4 text-center">
                        <h4 class="font-bold text-gray-800 text-sm truncate"><?= $row['nama_lengkap'] ?></h4>
                        <p class="text-[10px] text-gray-500 uppercase mt-1">
                            <?= $row['jenis_kelamin'] == 'L' ? 'Putra' : 'Putri' ?>
                        </p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 text-gray-400 border-2 border-dashed border-gray-300 rounded-xl">
                    <i class="fa-solid fa-user-slash text-4xl mb-2"></i>
                    <p>Belum ada data atlet yang ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
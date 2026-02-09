<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$title = "Data Cabang Olahraga";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// Ambil data cabor + Hitung jumlah SDM
$query = "SELECT cabor.*, 
          (SELECT COUNT(*) FROM atlet WHERE atlet.id_cabor = cabor.id_cabor) as jum_atlet,
          (SELECT COUNT(*) FROM pelatih WHERE pelatih.id_cabor = cabor.id_cabor) as jum_pelatih,
          (SELECT COUNT(*) FROM wasit WHERE wasit.id_cabor = cabor.id_cabor) as jum_wasit
          FROM cabor ORDER BY nama_cabor ASC";
$result = mysqli_query($conn, $query);
?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Cabor</h1>
        <p class="text-sm text-gray-500">Total terdata: <span class="font-bold text-purple-600"><?= mysqli_num_rows($result) ?> cabang</span></p>
    </div>
    <a href="tambah.php" class="bg-purple-600 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-purple-200 hover:bg-purple-700 transition flex items-center gap-2 font-bold text-sm">
        <i class="fa-solid fa-plus"></i> Tambah Cabor
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-[10px] text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100 font-bold tracking-widest">
                <tr>
                    <th class="px-6 py-4">Identitas</th>
                    <th class="px-6 py-4">Sekretariat / SK</th>
                    <th class="px-6 py-4">Statistik Anggota</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-purple-50/20 transition">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 font-bold text-lg border border-purple-100">
                                    <?= substr($row['nama_cabor'], 0, 1) ?>
                                </div>
                                <div>
                                    <span class="block font-bold text-gray-800 text-base"><?= $row['nama_cabor'] ?></span>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Ketua: <?= $row['ketua_cabor'] ?? '-' ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5 text-gray-600">
                                    <i class="fa-solid fa-location-dot text-gray-400 text-xs"></i>
                                    <span class="text-xs font-medium"><?= $row['lokasi_latihan'] ?? '-' ?></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <i class="fa-solid fa-file-contract text-gray-400 text-xs"></i>
                                    <span class="text-[10px]"><?= $row['nomor_sk'] ?? 'No SK: -' ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="px-2.5 py-1 rounded-lg bg-red-50 border border-red-100 text-center" title="Jumlah Atlet">
                                    <span class="block text-xs font-bold text-red-600"><?= $row['jum_atlet'] ?></span>
                                    <span class="text-[8px] text-red-400 font-bold uppercase">Atlet</span>
                                </div>
                                <div class="px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-100 text-center" title="Jumlah Pelatih">
                                    <span class="block text-xs font-bold text-blue-600"><?= $row['jum_pelatih'] ?></span>
                                    <span class="text-[8px] text-blue-400 font-bold uppercase">Coach</span>
                                </div>
                                <div class="px-2.5 py-1 rounded-lg bg-yellow-50 border border-yellow-100 text-center" title="Jumlah Wasit">
                                    <span class="block text-xs font-bold text-yellow-600"><?= $row['jum_wasit'] ?></span>
                                    <span class="text-[8px] text-yellow-500 font-bold uppercase">Wasit</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="detail.php?id=<?= $row['id_cabor'] ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition border border-green-100" title="Lihat Anggota">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>

                                <a href="edit.php?id=<?= $row['id_cabor'] ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition border border-blue-100" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>

                                <a href="hapus.php?id=<?= $row['id_cabor'] ?>" onclick="return confirm('Hapus cabor ini? Data atlet, pelatih, & wasit terkait akan ikut terhapus!')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition border border-red-100" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="fa-solid fa-layer-group text-5xl mb-2 text-purple-300"></i>
                                <p class="font-bold uppercase tracking-widest text-xs text-gray-500">Belum Ada Data Cabor</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
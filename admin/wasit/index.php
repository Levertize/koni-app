<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// LOGIC RESET PASSWORD
if (isset($_GET['reset_id'])) {
    $id_reset = $_GET['reset_id'];
    $pass_default = password_hash("123456", PASSWORD_DEFAULT);
    
    $query_reset = "UPDATE wasit SET password = '$pass_default' WHERE id_wasit = '$id_reset'";
    if (mysqli_query($conn, $query_reset)) {
        echo "<script>alert('Password wasit berhasil di-reset jadi: 123456'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal reset password!');</script>";
    }
}

$title = "Manajemen Wasit"; 
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// Query Data Wasit
$query = "SELECT wasit.*, cabor.nama_cabor 
          FROM wasit 
          LEFT JOIN cabor ON wasit.id_cabor = cabor.id_cabor 
          ORDER BY wasit.id_wasit DESC";
$result = mysqli_query($conn, $query);
?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Wasit & Juri</h1>
        <p class="text-sm text-gray-500">Total data: <span class="font-bold text-yellow-600"><?= mysqli_num_rows($result) ?> personil</span></p>
    </div>
    <a href="tambah.php" class="bg-yellow-500 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-yellow-200 hover:bg-yellow-600 transition flex items-center gap-2 font-bold text-sm">
        <i class="fa-solid fa-plus"></i> Tambah Wasit
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-[10px] text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100 font-bold tracking-widest">
                <tr>
                    <th class="px-6 py-4">Profil</th>
                    <th class="px-6 py-4">Identitas & Kontak</th>
                    <th class="px-6 py-4">Cabor & Gender</th>
                    <th class="px-6 py-4">Kualifikasi (Lisensi)</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-gray-50/50 transition">
                        
                        <td class="px-6 py-4">
                            <?php if($row['foto_profil']): ?>
                                <img src="../../uploads/foto_profil/<?= $row['foto_profil'] ?>" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100" alt="Foto">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-500 border border-yellow-100">
                                    <i class="fa-solid fa-user-clock"></i>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900"><?= $row['nama_lengkap'] ?></div>
                            <div class="flex flex-col gap-0.5 mt-1">
                                <span class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">NIK: <?= $row['nik'] ?></span>
                                <?php if(!empty($row['no_hp'])): ?>
                                    <span class="text-[10px] font-bold text-green-600 flex items-center gap-1">
                                        <i class="fa-brands fa-whatsapp"></i> <?= $row['no_hp'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-gray-100 text-gray-600 text-[10px] font-bold uppercase w-fit">
                                    <i class="fa-solid fa-medal text-[8px]"></i>
                                    <?= $row['nama_cabor'] ?? 'Unassigned' ?>
                                </span>
                                <?php if($row['jenis_kelamin'] == 'L'): ?>
                                    <span class="text-[10px] font-bold text-blue-600 flex items-center gap-1">
                                        <i class="fa-solid fa-mars"></i> Laki-laki
                                    </span>
                                <?php else: ?>
                                    <span class="text-[10px] font-bold text-pink-500 flex items-center gap-1">
                                        <i class="fa-solid fa-venus"></i> Perempuan
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <?php if(!empty($row['lisensi_grade'])): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-yellow-50 text-yellow-700 text-[10px] font-bold uppercase border border-yellow-100 w-fit">
                                        <i class="fa-solid fa-certificate text-[8px]"></i>
                                        <?= $row['lisensi_grade'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-[10px] text-gray-400 italic">Lisensi -</span>
                                <?php endif; ?>

                                <span class="text-[10px] font-bold text-gray-500 flex items-center gap-1">
                                    <i class="fa-solid fa-clock"></i> <?= $row['jam_terbang'] ?? '0' ?> Laga
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="detail.php?id=<?= $row['id_wasit'] ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="index.php?reset_id=<?= $row['id_wasit'] ?>" onclick="return confirm('Reset password wasit ini jadi 123456?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition" title="Reset Password">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </a>
                                <a href="edit.php?id=<?= $row['id_wasit'] ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <a href="hapus.php?id=<?= $row['id_wasit'] ?>" onclick="return confirm('Yakin mau hapus data wasit ini?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i class="fa-solid fa-folder-open text-5xl mb-2"></i>
                                <p class="font-bold uppercase tracking-widest text-xs">Data Kosong</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
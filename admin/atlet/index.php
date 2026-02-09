<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// LOGIC RESET PASSWORD (Jalan kalo tombol ditekan)
if (isset($_GET['reset_id'])) {
    $id_reset = $_GET['reset_id'];
    // Password default: 123456
    $pass_default = password_hash("123456", PASSWORD_DEFAULT);
    
    $query_reset = "UPDATE atlet SET password = '$pass_default' WHERE id_atlet = '$id_reset'";
    if (mysqli_query($conn, $query_reset)) {
        echo "<script>alert('Password atlet berhasil di-reset jadi: 123456'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal reset password!');</script>";
    }
}

$title = "Manajemen Atlet"; 
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// Query: Kita pake LEFT JOIN biar kalo cabornya kehapus, atletnya tetep muncul
$query = "SELECT atlet.*, cabor.nama_cabor 
          FROM atlet 
          LEFT JOIN cabor ON atlet.id_cabor = cabor.id_cabor 
          ORDER BY atlet.id_atlet DESC";
$result = mysqli_query($conn, $query);
?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Atlet KONI</h1>
        <p class="text-sm text-gray-500">Total terdata: <span class="font-bold text-red-600"><?= mysqli_num_rows($result) ?> orang</span></p>
    </div>
    <a href="tambah.php" class="bg-red-600 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition flex items-center gap-2 font-bold text-sm">
        <i class="fa-solid fa-plus"></i> Tambah Atlet Baru
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-[10px] text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100 font-bold tracking-widest">
                <tr>
                    <th class="px-6 py-4">Profil</th>
                    <th class="px-6 py-4">Informasi Atlet</th>
                    <th class="px-6 py-4">Cabang Olahraga</th>
                    <th class="px-6 py-4">Status Verifikasi</th>
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
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-400 border border-red-100">
                                    <i class="fa-solid fa-user-ninja"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900"><?= $row['nama_lengkap'] ?></div>
                            <div class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">NIK: <?= $row['nik'] ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-blue-50 text-blue-700 text-[10px] font-bold uppercase">
                                <i class="fa-solid fa-medal text-[8px]"></i>
                                <?= $row['nama_cabor'] ?? 'Unassigned' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($row['status_verifikasi'] == 'verified'): ?>
                                <span class="flex items-center gap-1 text-green-600 font-bold text-[10px] uppercase">
                                    <i class="fa-solid fa-circle-check"></i> Terverifikasi
                                </span>
                            <?php elseif($row['status_verifikasi'] == 'rejected'): ?>
                                <span class="flex items-center gap-1 text-red-600 font-bold text-[10px] uppercase">
                                    <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                </span>
                            <?php else: ?>
                                <span class="flex items-center gap-1 text-orange-500 font-bold text-[10px] uppercase animate-pulse">
                                    <i class="fa-solid fa-clock"></i> Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="detail.php?id=<?= $row['id_atlet'] ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>

                                <a href="index.php?reset_id=<?= $row['id_atlet'] ?>" onclick="return confirm('Yakin mau reset password atlet ini jadi 123456?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition" title="Reset Password">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </a>
                                
                                <a href="edit.php?id=<?= $row['id_atlet'] ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <a href="hapus.php?id=<?= $row['id_atlet'] ?>" onclick="return confirm('Yakin mau hapus data atlet ini?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Hapus">
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
                                <p class="font-bold uppercase tracking-widest text-xs">Data Kosong Melompong</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

$title = "Verifikasi Data & Berkas";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// Ambil semua data yang statusnya 'pending'
$atlet_pending = mysqli_query($conn, "SELECT atlet.*, cabor.nama_cabor FROM atlet LEFT JOIN cabor ON atlet.id_cabor = cabor.id_cabor WHERE status_verifikasi = 'pending'");
$pelatih_pending = mysqli_query($conn, "SELECT pelatih.*, cabor.nama_cabor FROM pelatih LEFT JOIN cabor ON pelatih.id_cabor = cabor.id_cabor WHERE status_verifikasi = 'pending'");
$wasit_pending = mysqli_query($conn, "SELECT wasit.*, cabor.nama_cabor FROM wasit LEFT JOIN cabor ON wasit.id_cabor = cabor.id_cabor WHERE status_verifikasi = 'pending'");

// Hitung total
$total_pending = mysqli_num_rows($atlet_pending) + mysqli_num_rows($pelatih_pending) + mysqli_num_rows($wasit_pending);
?>

<div class="mb-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Verifikasi Data</h1>
            <p class="text-sm text-gray-500">Validasi pendaftaran akun baru & pembaruan sertifikat/prestasi.</p>
        </div>
        <?php if($total_pending > 0): ?>
            <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-xl text-sm font-bold animate-pulse border border-orange-200">
                <i class="fa-solid fa-bell mr-2"></i> <?= $total_pending ?> Permintaan Menunggu
            </span>
        <?php endif; ?>
    </div>
</div>

<div class="space-y-10">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-between items-center">
            <h3 class="font-bold text-red-800 flex items-center gap-2">
                <i class="fa-solid fa-running"></i> Verifikasi Atlet (Prestasi & Akun)
            </h3>
            <span class="bg-red-200 text-red-800 text-xs font-bold px-2 py-1 rounded-full"><?= mysqli_num_rows($atlet_pending) ?> Pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-bold">
                    <tr>
                        <th class="px-6 py-3">Nama / NIK</th>
                        <th class="px-6 py-3">Cabor</th>
                        <th class="px-6 py-3">Jenis Verifikasi</th>
                        <th class="px-6 py-3">Bukti Dokumen</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(mysqli_num_rows($atlet_pending) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($atlet_pending)): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?= $row['nama_lengkap'] ?></div>
                                <div class="text-xs text-gray-400"><?= $row['nik'] ?></div>
                            </td>
                            <td class="px-6 py-4"><?= $row['nama_cabor'] ?></td>
                            <td class="px-6 py-4">
                                <?php if(!empty($row['file_bukti'])): ?>
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold border border-purple-200">Update Prestasi</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold border border-gray-200">Akun Baru</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!empty($row['file_bukti'])): ?>
                                    <a href="../../uploads/bukti/<?= $row['file_bukti'] ?>" target="_blank" class="flex items-center gap-2 text-blue-600 font-bold hover:underline text-xs bg-blue-50 px-3 py-2 rounded-lg w-fit border border-blue-100">
                                        <i class="fa-solid fa-file-pdf"></i> Lihat File
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 flex gap-2 justify-center">
                                <a href="proses.php?type=atlet&action=verify&id=<?= $row['id_atlet'] ?>" class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-green-200 transition border border-green-200">TERIMA</a>
                                <a href="proses.php?type=atlet&action=reject&id=<?= $row['id_atlet'] ?>" onclick="return confirm('Tolak data ini?')" class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-red-200 transition border border-red-200">TOLAK</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada antrean atlet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
            <h3 class="font-bold text-blue-800 flex items-center gap-2">
                <i class="fa-solid fa-user-tie"></i> Verifikasi Pelatih (Lisensi & Akun)
            </h3>
            <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded-full"><?= mysqli_num_rows($pelatih_pending) ?> Pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-bold">
                    <tr>
                        <th class="px-6 py-3">Nama / Kategori</th>
                        <th class="px-6 py-3">Cabor & Grade</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Sertifikat</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(mysqli_num_rows($pelatih_pending) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($pelatih_pending)): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?= $row['nama_lengkap'] ?></div>
                                <div class="text-xs text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded w-fit mt-1"><?= $row['kategori'] ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700"><?= $row['nama_cabor'] ?></div>
                                <div class="text-[10px] text-gray-500">Grade: <?= $row['lisensi_grade'] ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!empty($row['file_sertifikat'])): ?>
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-lg text-xs font-bold border border-purple-200">
                                        <i class="fa-solid fa-file-contract mr-1"></i> Cek Lisensi
                                    </span>
                                <?php else: ?>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg text-xs font-bold border border-blue-200">
                                        <i class="fa-solid fa-user-plus mr-1"></i> Akun Baru
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!empty($row['file_sertifikat'])): ?>
                                    <a href="../../uploads/sertifikat/<?= $row['file_sertifikat'] ?>" target="_blank" class="flex items-center gap-2 text-emerald-600 font-bold hover:underline text-xs bg-emerald-50 px-3 py-2 rounded-lg w-fit border border-emerald-100">
                                        <i class="fa-solid fa-file-contract"></i> Cek Sertifikat
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 flex gap-2 justify-center">
                                <a href="proses.php?type=pelatih&action=verify&id=<?= $row['id_pelatih'] ?>" class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-green-200 transition border border-green-200">TERIMA</a>
                                <a href="proses.php?type=pelatih&action=reject&id=<?= $row['id_pelatih'] ?>" onclick="return confirm('Tolak data ini?')" class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-red-200 transition border border-red-200">TOLAK</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada antrean pelatih.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100 flex justify-between items-center">
            <h3 class="font-bold text-yellow-800 flex items-center gap-2">
                <i class="fa-solid fa-flag-checkered"></i> Verifikasi Wasit (Lisensi & Akun)
            </h3>
            <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full"><?= mysqli_num_rows($wasit_pending) ?> Pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-bold">
                    <tr>
                        <th class="px-6 py-3">Nama / NIK</th>
                        <th class="px-6 py-3">Cabor & Lisensi</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Dokumen Lisensi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(mysqli_num_rows($wasit_pending) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($wasit_pending)): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?= $row['nama_lengkap'] ?></div>
                                <div class="text-xs text-gray-400"><?= $row['nik'] ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700"><?= $row['nama_cabor'] ?></div>
                                <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-0.5 rounded font-bold border border-yellow-200 mt-1 inline-block">
                                    <?= $row['lisensi_grade'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!empty($row['file_lisensi'])): ?>
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-lg text-xs font-bold border border-purple-200">
                                        <i class="fa-solid fa-file-contract mr-1"></i> Cek Lisensi
                                    </span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-lg text-xs font-bold border border-yellow-200">
                                        <i class="fa-solid fa-user-plus mr-1"></i> Akun Baru
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(!empty($row['file_lisensi'])): ?>
                                    <a href="../../uploads/lisensi/<?= $row['file_lisensi'] ?>" target="_blank" class="flex items-center gap-2 text-yellow-600 font-bold hover:underline text-xs bg-yellow-50 px-3 py-2 rounded-lg w-fit border border-yellow-100">
                                        <i class="fa-solid fa-id-card"></i> Cek Lisensi
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 flex gap-2 justify-center">
                                <a href="proses.php?type=wasit&action=verify&id=<?= $row['id_wasit'] ?>" class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-green-200 transition border border-green-200">TERIMA</a>
                                <a href="proses.php?type=wasit&action=reject&id=<?= $row['id_wasit'] ?>" onclick="return confirm('Tolak data ini?')" class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-red-200 transition border border-red-200">TOLAK</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada antrean wasit.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once '../../layouts/footer.php'; ?>
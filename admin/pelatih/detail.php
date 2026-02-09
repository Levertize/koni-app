<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];

// Ambil Data Pelatih
$pelatih = mysqli_fetch_assoc(mysqli_query($conn, "SELECT pelatih.*, cabor.nama_cabor FROM pelatih LEFT JOIN cabor ON pelatih.id_cabor = cabor.id_cabor WHERE id_pelatih = '$id'"));

// Ambil Program Latihan (Jadwal)
$program = mysqli_query($conn, "SELECT * FROM program_latihan WHERE id_pelatih = '$id' ORDER BY tanggal_mulai DESC");

$title = "Detail Pelatih";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';

// --- DATA PREPARATION (SAFE MODE) ---
// TTL
$tgl = $pelatih['tgl_lahir'] ?? null;
if ($tgl && $tgl != '0000-00-00') {
    $ttl_show = date('d M Y', strtotime($tgl));
} else {
    $ttl_show = '-';
}

// Alamat
$alamat = $pelatih['alamat'] ?? '-';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profil & Jadwal Latihan</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Monitoring Kinerja Pelatih</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                <div class="px-6 relative">
                    <div class="w-20 h-20 rounded-full bg-white p-1 absolute -top-10 left-6 shadow-md">
                        <?php if(!empty($pelatih['foto_profil'])): ?>
                            <img src="../../uploads/foto_profil/<?= $pelatih['foto_profil'] ?>" class="w-full h-full rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-400">
                                <?= substr($pelatih['nama_lengkap'], 0, 1) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="pt-12 px-6 pb-6">
                    <h2 class="text-xl font-bold text-gray-800"><?= $pelatih['nama_lengkap'] ?></h2>
                    <p class="text-sm text-gray-500 mb-4 font-medium"><?= $pelatih['nama_cabor'] ?></p>

                    <div class="space-y-3 pt-2 border-t border-gray-50">
                        
                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">NIK</span>
                            <span class="text-xs font-bold text-gray-700 w-2/3 text-right"><?= $pelatih['nik'] ?></span>
                        </div>

                        <div class="flex justify-between py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase">Kategori</span>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded"><?= $pelatih['kategori'] ?></span>
                        </div>
                        
                        <div class="flex justify-between py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase">Grade</span>
                            <span class="text-sm font-bold text-gray-700"><?= $pelatih['lisensi_grade'] ?></span>
                        </div>
                        
                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Jenis Kelamin</span>
                            <span class="text-xs font-bold text-gray-700 w-2/3 text-right">
                                <?= ($pelatih['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Tgl Lahir</span>
                            <span class="text-xs font-bold text-gray-700 w-2/3 text-right"><?= $ttl_show ?></span>
                        </div>

                        <div class="flex justify-between items-start py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase w-1/3">Alamat</span>
                            <span class="text-xs font-medium text-gray-700 w-2/3 text-right leading-tight">
                                <?= $alamat ?>
                            </span>
                        </div>

                        <div class="flex justify-between py-1">
                            <span class="text-xs text-gray-400 font-bold uppercase">Status</span>
                            <span class="text-xs font-bold <?= $pelatih['status_aktif'] == 'Aktif' ? 'text-green-600' : 'text-gray-400' ?>">
                                <?= $pelatih['status_aktif'] ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-2">
                        <a href="edit.php?id=<?= $pelatih['id_pelatih'] ?>" class="flex-1 bg-gray-50 text-gray-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-gray-100 transition">Edit Profil</a>
                        <a href="https://wa.me/?text=Halo Coach <?= $pelatih['nama_lengkap'] ?>" target="_blank" class="flex-1 bg-green-50 text-green-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-green-100 transition"><i class="fa-brands fa-whatsapp"></i> Chat</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-calendar-days text-blue-600 text-xl"></i>
                <h3 class="font-bold text-gray-800 text-lg">Program Latihan</h3>
            </div>

            <?php if(mysqli_num_rows($program) > 0): ?>
                <div class="space-y-4">
                    <?php while($p = mysqli_fetch_assoc($program)): ?>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 group-hover:bg-blue-600 transition"></div>
                        <div class="pl-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-base"><?= $p['judul_program'] ?></h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <p class="text-xs text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded">
                                            <i class="fa-regular fa-calendar mr-1"></i> <?= date('d M Y', strtotime($p['tanggal_mulai'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-3 rounded-xl italic">
                                "<?= nl2br($p['deskripsi']) ?>"
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
                    <i class="fa-solid fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
                    <h4 class="font-bold text-gray-600">Belum Ada Program</h4>
                    <p class="text-xs text-gray-400 mt-1">Pelatih ini belum membuat jadwal latihan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
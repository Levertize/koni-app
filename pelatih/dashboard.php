<?php
session_start();
require_once '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pelatih') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// --- AMBIL DATA PELATIH ---
$q_profil = mysqli_query($conn, "SELECT pelatih.*, cabor.nama_cabor FROM pelatih LEFT JOIN cabor ON pelatih.id_cabor = cabor.id_cabor WHERE id_pelatih = '$id_user'");
$data = mysqli_fetch_assoc($q_profil);

// --- HITUNG STATISTIK ---
$q_prog = mysqli_query($conn, "SELECT * FROM program_latihan WHERE id_pelatih='$id_user'");
$total_program = ($q_prog) ? mysqli_num_rows($q_prog) : 0;

$id_cabor = $data['id_cabor'] ?? 0;
$q_atlet = mysqli_query($conn, "SELECT * FROM atlet WHERE id_cabor='$id_cabor'");
$total_atlet = ($q_atlet) ? mysqli_num_rows($q_atlet) : 0;

// --- CEK KELENGKAPAN DATA (LOGIC UTAMA) ---
$is_verified = ($data['status_verifikasi'] == 'verified');
$is_pending  = ($data['status_verifikasi'] == 'pending');
$is_rejected = ($data['status_verifikasi'] == 'rejected');
$has_file    = !empty($data['file_sertifikat']); 

$title = "Dashboard Pelatih";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="mb-8 relative group">
    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
    <div class="relative bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-8 text-white shadow-2xl shadow-emerald-500/40 overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold mb-2 tracking-tight">Coach <?= explode(' ', $data['nama_lengkap'])[0] ?>! 👋</h2>
            <p class="text-emerald-100 mb-6 text-lg font-medium">Siapkan strategi juara hari ini.</p>
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl font-bold shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-medal text-emerald-200"></i> <?= $data['nama_cabor'] ?? 'Umum' ?>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl font-bold shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-certificate text-emerald-200"></i> Grade: <?= $data['lisensi_grade'] ?: '-' ?>
                </div>
            </div>
        </div>
        <i class="fa-solid fa-chalkboard-user absolute -right-6 -bottom-8 text-[12rem] text-white/10 rotate-12 transition-transform duration-500 group-hover:scale-110"></i>
    </div>
</div>

<?php if(!$is_verified || !$has_file): ?>
    <div class="bg-white border-l-4 <?= $is_pending ? 'border-orange-500' : ($is_rejected ? 'border-red-500' : 'border-emerald-500') ?> p-6 rounded-r-xl shadow-sm mb-8 flex flex-col md:flex-row items-start gap-4 animate-fade-in-down">
        
        <div class="<?= $is_pending ? 'bg-orange-100 text-orange-600' : ($is_rejected ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600') ?> p-3 rounded-full shrink-0">
            <i class="fa-solid <?= $is_pending ? 'fa-hourglass-half' : ($is_rejected ? 'fa-circle-exclamation' : 'fa-triangle-exclamation') ?> text-xl"></i>
        </div>

        <div class="flex-1">
            <?php if($is_pending): ?>
                <h3 class="font-bold text-gray-800 text-lg">Verifikasi Berkas Sedang Berjalan</h3>
                <p class="text-gray-600 text-sm mt-1">Admin sedang meninjau sertifikat terbaru Anda. Anda tetap dapat mengelola program latihan.</p>
            <?php elseif($is_rejected): ?>
                <h3 class="font-bold text-red-800 text-lg">Dokumen Ditolak</h3>
                <p class="text-red-600 text-sm mt-1">Sertifikat tidak valid. Silakan upload ulang dokumen yang benar.</p>
                <a href="lisensi.php" class="inline-block mt-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700">Perbaiki Dokumen</a>
            <?php elseif($is_verified && !$has_file): ?>
                <h3 class="font-bold text-emerald-800 text-lg">Sertifikat Belum Diupload</h3>
                <p class="text-emerald-700 text-sm mt-1">Akun Anda aktif, tetapi Anda <b>belum mengupload sertifikat pelatih</b>. Menu Program Latihan terkunci.</p>
                <a href="lisensi.php" class="inline-block mt-2 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700">Upload Sekarang</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    
    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100 flex items-center gap-5 hover:-translate-y-1 transition duration-300">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Program Latihan</h3>
                    <p class="text-3xl font-black text-gray-800"><?= $total_program ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-blue-100 flex items-center gap-5 hover:-translate-y-1 transition duration-300">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Atlet Binaan</h3>
                    <p class="text-3xl font-black text-gray-800"><?= $total_atlet ?></p>
                </div>
            </div>
        </div>

        <?php if($has_file && !$is_rejected): ?>
            <a href="program.php" class="group relative bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-emerald-100 transition-all duration-300 overflow-hidden block">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-emerald-100"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-emerald-600 transition">Buat Program Latihan</h3>
                    <p class="text-gray-500 text-sm">Rancang jadwal dan menu latihan fisik/teknik untuk atlet.</p>
                </div>
            </a>
        <?php else: ?>
            <div class="group relative bg-gray-50 p-8 rounded-3xl border border-gray-200 cursor-not-allowed">
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-gray-200 text-gray-400 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-500 mb-2">Program Latihan Terkunci</h3>
                    <?php if(!$has_file): ?>
                        <p class="text-gray-400 text-sm">Wajib upload sertifikat pelatih terlebih dahulu.</p>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm">Dokumen ditolak. Perbaiki di menu lisensi.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden h-full">
            <div class="bg-gray-50 p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-certificate text-emerald-500"></i> Status Lisensi
                </h3>
            </div>
            
            <div class="p-6">
                <div class="mb-6 text-center">
                    <?php if($is_verified && $has_file): ?>
                        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl animate-bounce-slow">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h4 class="font-bold text-green-700 text-lg">Lisensi Valid</h4>
                        <p class="text-xs text-green-600 mt-1">Dokumen Terverifikasi</p>

                    <?php elseif($is_verified && !$has_file): ?>
                        <div class="w-16 h-16 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">
                            <i class="fa-solid fa-file-circle-question"></i>
                        </div>
                        <h4 class="font-bold text-gray-700 text-lg">Belum Ada Sertifikat</h4>
                        <p class="text-xs text-gray-500 mt-1">Silakan upload dokumen.</p>

                    <?php elseif($is_pending): ?>
                        <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl animate-pulse">
                            <i class="fa-solid fa-hourglass"></i>
                        </div>
                        <h4 class="font-bold text-orange-700 text-lg">Dalam Peninjauan</h4>
                        <p class="text-xs text-orange-600 mt-1">Menunggu ACC Admin</p>

                    <?php else: ?>
                        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <h4 class="font-bold text-red-700 text-lg">Ditolak</h4>
                        <p class="text-xs text-red-600 mt-1">Dokumen Tidak Valid</p>
                    <?php endif; ?>
                </div>

                <div class="space-y-4 border-t border-gray-100 pt-6">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                        <span class="text-xs font-bold text-gray-400 uppercase">Grade</span>
                        <span class="font-bold text-gray-800"><?= $data['lisensi_grade'] ?: '-' ?></span>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase block mb-2">File Sertifikat</span>
                        <?php if($has_file): ?>
                            <a href="../uploads/sertifikat/<?= $data['file_sertifikat'] ?>" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition group cursor-pointer text-decoration-none">
                                <i class="fa-solid fa-file-pdf text-red-500 text-xl group-hover:scale-110 transition"></i>
                                <div>
                                    <p class="text-xs font-bold text-blue-800">Lihat File</p>
                                    <p class="text-[10px] text-blue-600">Klik untuk membuka</p>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 text-gray-400 text-xs italic text-center">
                                Belum ada file
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="lisensi.php" class="block w-full text-center mt-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 transition">
                    <?= $has_file ? 'Perbarui Lisensi' : 'Upload Lisensi Sekarang' ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
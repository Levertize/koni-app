<?php
session_start();
require_once '../config/koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pelatih') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user']; 

// 2. CEK STATUS (GATEKEEPER)
$cek_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status_verifikasi, file_sertifikat FROM pelatih WHERE id_pelatih = '$id_user'"));

$punya_file = !empty($cek_data['file_sertifikat']);
$ditolak    = ($cek_data['status_verifikasi'] == 'rejected');

// Kalau Gak Punya File ATAU Ditolak -> TENDANG
if (!$punya_file || $ditolak) {
    echo "<script>
        alert('AKSES DITOLAK!\\n\\nAnda belum bisa mengakses menu Program Latihan.\\nPastikan Anda sudah upload sertifikat pelatih dan tidak berstatus ditolak.');
        window.location='dashboard.php';
    </script>";
    exit;
}

// 3. LOGIC CRUD PROGRAM
if (isset($_POST['simpan_program'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $tgl_mulai = $_POST['tgl_mulai'];
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $target = htmlspecialchars($_POST['target']);

    $query = "INSERT INTO program_latihan (id_pelatih, judul_program, deskripsi, target_latihan, tanggal_mulai) 
              VALUES ('$id_user', '$judul', '$deskripsi', '$target', '$tgl_mulai')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Program berhasil dibuat!'); window.location='program.php';</script>";
    } else {
        echo "<script>alert('Gagal simpan: " . mysqli_error($conn) . "');</script>";
    }
}

if (isset($_GET['hapus'])) {
    $id_prog = $_GET['hapus'];
    $del = mysqli_query($conn, "DELETE FROM program_latihan WHERE id_program='$id_prog' AND id_pelatih='$id_user'");
    if ($del) {
        echo "<script>alert('Program berhasil dihapus!'); window.location='program.php';</script>";
    }
}

$program_list = mysqli_query($conn, "SELECT * FROM program_latihan WHERE id_pelatih = '$id_user' ORDER BY tanggal_mulai DESC");

$title = "Manajemen Program Latihan";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="max-w-7xl mx-auto">
    
    <?php if($cek_data['status_verifikasi'] == 'pending'): ?>
        <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm animate-pulse">
            <i class="fa-solid fa-circle-info text-lg"></i>
            <div>
                <b>Info:</b> Sertifikat Anda sedang dalam proses verifikasi ulang. Anda tetap dapat mengelola program latihan.
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fa-solid fa-pen-to-square text-emerald-500"></i> Buat Program Baru
                </h3>
                
                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Judul Latihan</label>
                        <input type="text" name="judul" required class="w-full mt-1 border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="Misal: Latihan Fisik Mingguan">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" required class="w-full mt-1 border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Latihan</label>
                        <input type="text" name="target" class="w-full mt-1 border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="Misal: Meningkatkan Stamina">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Deskripsi / Materi</label>
                        <textarea name="deskripsi" rows="4" required class="w-full mt-1 border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="Tulis detail materi latihan di sini..."></textarea>
                    </div>

                    <button type="submit" name="simpan_program" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 mt-2">
                        <i class="fa-solid fa-paper-plane mr-1"></i> Terbitkan Program
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-800 text-xl">Daftar Program Latihan</h3>
                    <p class="text-sm text-gray-500">Program yang telah Anda buat.</p>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full border border-emerald-100">
                    Total: <?= mysqli_num_rows($program_list) ?>
                </span>
            </div>

            <?php if(mysqli_num_rows($program_list) > 0): ?>
                <div class="space-y-5">
                    <?php while($p = mysqli_fetch_assoc($program_list)): ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500 group-hover:bg-emerald-600 transition"></div>
                        
                        <div class="pl-2">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-3">
                                <h4 class="font-bold text-gray-800 text-lg group-hover:text-emerald-600 transition"><?= $p['judul_program'] ?></h4>
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg border border-blue-100 uppercase flex items-center gap-1">
                                        <i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($p['tanggal_mulai'])) ?>
                                    </span>
                                    
                                    <a href="?hapus=<?= $p['id_program'] ?>" onclick="return confirm('Yakin mau hapus program ini?')" class="text-[10px] font-bold bg-red-50 text-red-600 px-2.5 py-1 rounded-lg border border-red-100 uppercase hover:bg-red-600 hover:text-white transition flex items-center gap-1">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>

                            <?php if(!empty($p['target_latihan'])): ?>
                            <div class="mb-3">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                                    <i class="fa-solid fa-bullseye text-emerald-500"></i> Target: <?= $p['target_latihan'] ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="bg-gray-50/50 p-4 rounded-xl text-sm text-gray-600 leading-relaxed border border-gray-100">
                                <?= nl2br($p['deskripsi']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-300 text-3xl">
                        <i class="fa-solid fa-clipboard-question"></i>
                    </div>
                    <h4 class="font-bold text-gray-600 text-lg">Belum Ada Program</h4>
                    <p class="text-sm text-gray-400 mt-1 max-w-xs mx-auto">Ayo buat program latihan pertamamu untuk meningkatkan performa atlet!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
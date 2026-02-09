<?php
session_start();
require_once '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pelatih') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$msg = "";
$msg_type = "";

// --- LOGIC UPDATE LISENSI ---
if (isset($_POST['simpan_lisensi'])) {
    $grade = htmlspecialchars($_POST['grade']);
    
    // Cek File Baru
    if (!empty($_FILES['file_sertifikat']['name'])) {
        $filename = $_FILES['file_sertifikat']['name'];
        $tmp_name = $_FILES['file_sertifikat']['tmp_name'];
        $ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $new_name = time() . '_sertifikat_pelatih_' . $id_user . '.' . $ext;
        $folder   = '../uploads/sertifikat/';

        if (!is_dir($folder)) mkdir($folder, 0777, true);

        if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
            if (move_uploaded_file($tmp_name, $folder . $new_name)) {
                
                // Hapus file lama
                $q_old = mysqli_query($conn, "SELECT file_sertifikat FROM pelatih WHERE id_pelatih='$id_user'");
                $old_data = mysqli_fetch_assoc($q_old);
                if (!empty($old_data['file_sertifikat']) && file_exists($folder . $old_data['file_sertifikat'])) {
                    unlink($folder . $old_data['file_sertifikat']);
                }

                // Update DB
                $sql = "UPDATE pelatih SET 
                        lisensi_grade = '$grade', 
                        file_sertifikat = '$new_name', 
                        status_verifikasi = 'pending' 
                        WHERE id_pelatih = '$id_user'";
                
                if (mysqli_query($conn, $sql)) {
                    $msg = "<b>Berhasil Upload!</b> Data lisensi diperbarui. Menunggu verifikasi Admin.";
                    $msg_type = "success";
                } else {
                    $msg = "Database Error: " . mysqli_error($conn);
                    $msg_type = "error";
                }
            } else {
                $msg = "Gagal upload file.";
                $msg_type = "error";
            }
        } else {
            $msg = "Format salah! Wajib PDF/JPG/PNG.";
            $msg_type = "error";
        }
    } else {
        // Cuma Ganti Grade
        $sql = "UPDATE pelatih SET lisensi_grade = '$grade', status_verifikasi = 'pending' WHERE id_pelatih = '$id_user'";
        if (mysqli_query($conn, $sql)) {
            $msg = "Grade lisensi diperbarui.";
            $msg_type = "success";
        }
    }
}

// --- AMBIL DATA TERBARU ---
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelatih WHERE id_pelatih = '$id_user'"));

// Helper Vars
$file_ada = !empty($data['file_sertifikat']);
$status   = $data['status_verifikasi'];

$title = "Manajemen Lisensi Pelatih";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="max-w-6xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Lisensi & Sertifikasi</h1>
        <p class="text-sm text-gray-500">Kelola dokumen kualifikasi kepelatihan Anda.</p>
    </div>

    <?php if ($msg): ?>
        <div class="mb-6 p-4 rounded-xl text-sm font-bold flex items-center gap-3 shadow-sm border <?= $msg_type == 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200' ?>">
            <i class="fa-solid <?= $msg_type == 'success' ? 'fa-check' : 'fa-triangle-exclamation' ?> text-lg"></i>
            <?= $msg ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-emerald-600"></i> Data Lisensi Saat Ini
            </h3>
            
            <?php if($status == 'verified'): ?>
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">Terverifikasi</span>
            <?php elseif($status == 'pending'): ?>
                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold border border-orange-200 animate-pulse">Menunggu Verifikasi</span>
            <?php elseif($status == 'rejected'): ?>
                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold border border-red-200">Ditolak</span>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white text-gray-500 font-bold border-b border-gray-100 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Tingkat Grade</th>
                        <th class="px-6 py-4">Status Dokumen</th>
                        <th class="px-6 py-4">File Fisik</th>
                        <th class="px-6 py-4 text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if($file_ada): ?>
                    <tr class="hover:bg-emerald-50/30 transition">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800 text-base"><?= $data['lisensi_grade'] ?></span>
                            <div class="text-xs text-gray-400 mt-0.5">Sertifikat Pelatih</div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($status == 'verified'): ?>
                                <div class="flex items-center gap-2 text-green-600 font-bold text-xs">
                                    <i class="fa-solid fa-circle-check"></i> Valid
                                </div>
                            <?php elseif($status == 'pending'): ?>
                                <div class="flex items-center gap-2 text-orange-600 font-bold text-xs">
                                    <i class="fa-solid fa-clock"></i> Proses Review
                                </div>
                            <?php else: ?>
                                <div class="flex items-center gap-2 text-red-600 font-bold text-xs">
                                    <i class="fa-solid fa-circle-xmark"></i> Invalid
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <a href="../uploads/sertifikat/<?= $data['file_sertifikat'] ?>" target="_blank" class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-emerald-500 hover:text-emerald-600 px-4 py-2 rounded-lg text-xs font-bold text-gray-600 transition shadow-sm">
                                <i class="fa-solid fa-file-pdf text-red-500"></i> Lihat File
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($status == 'rejected'): ?>
                                <span class="text-red-500 text-xs font-bold">Perlu Perbaikan!</span>
                            <?php elseif($status == 'verified'): ?>
                                <span class="text-green-500 text-xs font-bold">Aktif</span>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 text-gray-300"></i>
                            <p class="text-sm">Belum ada lisensi yang diupload.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
            <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    <?= $file_ada ? 'Update Lisensi Baru' : 'Upload Lisensi Pertama' ?>
                </h2>
                <p class="text-gray-400 text-xs">Formulir ini untuk mengubah atau memperbarui data di atas.</p>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tingkat Lisensi (Grade)</label>
                    <div class="relative">
                        <select name="grade" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none bg-white transition">
                            <option value="Daerah" <?= $data['lisensi_grade'] == 'Daerah' ? 'selected' : '' ?>>Tingkat Daerah</option>
                            <option value="Nasional" <?= $data['lisensi_grade'] == 'Nasional' ? 'selected' : '' ?>>Tingkat Nasional</option>
                            <option value="Internasional" <?= $data['lisensi_grade'] == 'Internasional' ? 'selected' : '' ?>>Tingkat Internasional</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">File Dokumen (PDF/JPG)</label>
                    <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition border border-gray-200 rounded-xl cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-2 ml-1">
                        <i class="fa-solid fa-circle-info mr-1"></i> Maksimal 2MB. Jika tidak ingin mengubah file, biarkan kosong.
                    </p>
                </div>
            </div>

            <div class="flex flex-col justify-between">
                
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-4">
                    <div class="flex gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>
                        <div class="text-xs text-amber-800 leading-relaxed">
                            <p class="font-bold mb-1">Penting:</p>
                            Mengupload sertifikat baru akan mengubah status akun Anda menjadi <b>PENDING</b> sementara waktu hingga Admin selesai memverifikasi dokumen tersebut.
                        </div>
                    </div>
                </div>

                <button type="submit" name="simpan_lisensi" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition flex justify-center items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> 
                    <?= $file_ada ? 'Simpan & Ajukan Ulang' : 'Kirim Sertifikat' ?>
                </button>
            </div>

        </form>
    </div>

</div>

<?php require_once '../layouts/footer.php'; ?>
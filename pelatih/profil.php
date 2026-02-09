<?php
session_start();
require_once '../config/koneksi.php';

// Cek User
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pelatih') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$msg = "";
$msg_type = "";

// --- LOGIC UPDATE PROFIL ---
if (isset($_POST['update_profil'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat = htmlspecialchars($_POST['alamat']);
    
    // Logic Upload Foto
    $query_foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $file_name = time() . '_' . $_FILES['foto']['name'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        $target = '../uploads/foto_profil/';
        
        if (!is_dir($target)) mkdir($target, 0777, true);
        
        if (move_uploaded_file($file_tmp, $target . $file_name)) {
            // Hapus foto lama
            $old_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto_profil FROM pelatih WHERE id_pelatih='$id_user'"));
            if ($old_data['foto_profil'] && file_exists($target . $old_data['foto_profil'])) {
                unlink($target . $old_data['foto_profil']);
            }
            $query_foto = ", foto_profil='$file_name'";
        }
    }

    $sql = "UPDATE pelatih SET nama_lengkap='$nama', tgl_lahir='$tgl_lahir', alamat='$alamat' $query_foto WHERE id_pelatih='$id_user'";
    
    if (mysqli_query($conn, $sql)) {
        $msg = "Profil berhasil diperbarui!";
        $msg_type = "success";
        $_SESSION['nama'] = $nama; 
    } else {
        $msg = "Gagal update: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// --- LOGIC GANTI PASSWORD ---
if (isset($_POST['ganti_password'])) {
    $pass_baru = $_POST['pass_baru'];
    $pass_konfirm = $_POST['pass_konfirm'];
    
    if ($pass_baru == $pass_konfirm) {
        $pass_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
        $sql = "UPDATE pelatih SET password='$pass_hash' WHERE id_pelatih='$id_user'";
        if (mysqli_query($conn, $sql)) {
            $msg = "Password berhasil diganti!";
            $msg_type = "success";
        } else {
            $msg = "Gagal ganti password.";
            $msg_type = "error";
        }
    } else {
        $msg = "Password konfirmasi tidak cocok!";
        $msg_type = "error";
    }
}

// Ambil Data Terbaru
$query = "SELECT pelatih.*, cabor.nama_cabor 
          FROM pelatih 
          LEFT JOIN cabor ON pelatih.id_cabor = cabor.id_cabor 
          WHERE id_pelatih = '$id_user'";
$data = mysqli_fetch_assoc(mysqli_query($conn, $query));

$title = "Edit Profil Pelatih";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="max-w-5xl mx-auto">
    
    <!-- Notifikasi -->
    <?php if ($msg): ?>
        <div class="mb-6 p-4 rounded-xl text-sm font-bold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
            <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?> text-lg"></i>
            <?= $msg ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: INFO & FOTO -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kartu Foto -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                <div class="relative w-32 h-32 mx-auto mb-4 group">
                    <div class="w-full h-full rounded-full overflow-hidden border-4 border-emerald-50 shadow-inner">
                        <?php if(isset($data['foto_profil']) && $data['foto_profil']): ?>
                            <img src="../uploads/foto_profil/<?= $data['foto_profil'] ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300 text-4xl">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="font-bold text-gray-800"><?= $data['nama_lengkap'] ?></h3>
                <p class="text-xs text-gray-500 mb-3"><?= $data['nama_cabor'] ?></p>
                
                <div class="flex justify-center gap-2 mb-2">
                    <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded uppercase border border-emerald-100">
                        <?= $data['kategori'] ?>
                    </span>
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded uppercase border border-blue-100">
                        <?= $data['lisensi_grade'] ?>
                    </span>
                </div>
            </div>

            <!-- Form Ganti Password -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="font-bold text-gray-800 text-sm mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-key text-emerald-500"></i> Keamanan Akun
                </h4>
                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Password Baru</label>
                        <input type="password" name="pass_baru" required class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="******">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Ulangi Password</label>
                        <input type="password" name="pass_konfirm" required class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="******">
                    </div>
                    <button type="submit" name="ganti_password" class="w-full bg-emerald-50 text-emerald-600 font-bold text-xs py-3 rounded-lg hover:bg-emerald-600 hover:text-white transition">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM BIODATA -->
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-address-card text-emerald-600"></i> Biodata Pelatih
                </h4>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Upload Foto -->
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Update Foto Profil</label>
                            <div class="flex items-center gap-3 mt-1">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                            </div>
                        </div>

                        <!-- Data Diri -->
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= $data['nama_lengkap'] ?>" required class="w-full mt-1 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>
                        
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">NIK (Tidak dapat diubah)</label>
                            <input type="text" value="<?= $data['nik'] ?>" disabled class="w-full mt-1 border border-gray-200 bg-gray-50 text-gray-400 rounded-lg px-4 py-3 text-sm cursor-not-allowed">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="<?= $data['tgl_lahir'] ?>" required class="w-full mt-1 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Alamat Domisili</label>
                            <textarea name="alamat" rows="3" class="w-full mt-1 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition"><?= $data['alamat'] ?></textarea>
                        </div>
                        
                        <!-- Info Read Only -->
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Cabang Olahraga</label>
                            <input type="text" value="<?= $data['nama_cabor'] ?>" disabled class="w-full mt-1 border border-gray-200 bg-gray-50 text-gray-600 font-bold rounded-lg px-4 py-3 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Status Keaktifan</label>
                            <input type="text" value="<?= $data['status_aktif'] ?>" disabled class="w-full mt-1 border border-gray-200 bg-gray-50 text-emerald-600 font-bold rounded-lg px-4 py-3 text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-50">
                        <button type="submit" name="update_profil" class="px-8 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
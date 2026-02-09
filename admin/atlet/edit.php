<?php
session_start();
// Aktifin Error Reporting sementara buat ngecek masalah
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Cek ID di URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID Atlet tidak ditemukan di URL!'); window.location='index.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil Data Atlet
$query_atlet = mysqli_query($conn, "SELECT * FROM atlet WHERE id_atlet = '$id'");
$data = mysqli_fetch_assoc($query_atlet);

// Kalo data gak ketemu di database
if (!$data) {
    echo "<script>alert('Data atlet dengan ID $id tidak ditemukan di database!'); window.location='index.php';</script>";
    exit;
}

// --- LOGIC UPDATE ---
if (isset($_POST['update'])) {
    $nik = htmlspecialchars($_POST['nik']);
    $nama = htmlspecialchars($_POST['nama']);
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);
    $jk = htmlspecialchars($_POST['jk']);
    $alamat = htmlspecialchars($_POST['alamat']);
    
    // Logic Foto
    $query_foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = time() . '_' . rand(100, 999) . '.' . $ext;
        $target_dir = '../../uploads/foto_profil/';
        
        // Cek folder upload
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $foto_name)) {
            // Hapus foto lama
            if ($data['foto_profil'] && file_exists($target_dir . $data['foto_profil'])) {
                unlink($target_dir . $data['foto_profil']);
            }
            $query_foto = ", foto_profil='$foto_name'";
        }
    }

    $query = "UPDATE atlet SET 
              nik='$nik', 
              nama_lengkap='$nama', 
              id_cabor='$id_cabor', 
              tgl_lahir='$tgl_lahir', 
              jenis_kelamin='$jk', 
              alamat='$alamat' 
              $query_foto
              WHERE id_atlet='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil Data Cabor buat Dropdown
$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");

// Load Layouts (Pastikan path ../../ benar)
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Atlet</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Update Biodata & Status</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIK (Nomor Induk)</label>
                        <input type="number" name="nik" value="<?= $data['nik'] ?>" required class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= $data['nama_lengkap'] ?>" required class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cabang Olahraga</label>
                        <select name="id_cabor" required class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">-- Pilih Cabor --</option>
                            <?php while($c = mysqli_fetch_assoc($cabor_data)): ?>
                                <option value="<?= $c['id_cabor'] ?>" <?= ($c['id_cabor'] == $data['id_cabor']) ? 'selected' : '' ?>>
                                    <?= $c['nama_cabor'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                        <select name="jk" required class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="L" <?= ($data['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= ($data['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="<?= $data['tgl_lahir'] ?>" required class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Domisili</label>
                        <textarea name="alamat" rows="3" class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"><?= $data['alamat'] ?></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <?php if($data['foto_profil']): ?>
                                <img src="../../uploads/foto_profil/<?= $data['foto_profil'] ?>" class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                            <?php else: ?>
                                <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200"><i class="fa-solid fa-user"></i></div>
                            <?php endif; ?>
                            <input type="file" name="foto" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="index.php" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-500 font-bold text-sm hover:bg-gray-50 transition">Batal</a>
                <button type="submit" name="update" class="px-8 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../layouts/footer.php'; ?>
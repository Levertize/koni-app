<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelatih WHERE id_pelatih = '$id'"));

if (isset($_POST['update'])) {
    $nik = htmlspecialchars($_POST['nik']);
    $nama = htmlspecialchars($_POST['nama']);
    // 1. Tangkap Data Gender
    $jk = htmlspecialchars($_POST['jenis_kelamin']);
    
    // 2. Tangkap TTL & Alamat
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);
    $alamat = htmlspecialchars($_POST['alamat']);
    
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $kategori = htmlspecialchars($_POST['kategori']); 
    $grade = htmlspecialchars($_POST['grade']);       
    $status_aktif = htmlspecialchars($_POST['status_aktif']);

    // 3. Update Query (Masukin jenis_kelamin, tgl_lahir, alamat)
    $query = "UPDATE pelatih SET 
              id_cabor='$id_cabor', 
              nik='$nik', 
              nama_lengkap='$nama', 
              jenis_kelamin='$jk',
              tgl_lahir='$tgl_lahir',
              alamat='$alamat',
              kategori='$kategori', 
              lisensi_grade='$grade', 
              status_aktif='$status_aktif' 
              WHERE id_pelatih='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data pelatih berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}

$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");
$title = "Edit Pelatih";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Pelatih</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Perbarui Informasi & Status</p>
        </div>
    </div>

    <form action="" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3 mb-2">
                    <div class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center text-xs"><i class="fa-solid fa-user-pen"></i></div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Biodata Utama</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIK</label>
                    <input type="number" name="nik" value="<?= $data['nik'] ?>" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $data['nama_lengkap'] ?>" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="<?= $data['tgl_lahir'] ?? '' ?>" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Domisili</label>
                    <textarea name="alamat" rows="2" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"><?= $data['alamat'] ?? '' ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="L" <?= ($data['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= ($data['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cabor</label>
                    <select name="id_cabor" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <?php while($c = mysqli_fetch_assoc($cabor_data)): ?>
                            <option value="<?= $c['id_cabor'] ?>" <?= ($c['id_cabor'] == $data['id_cabor']) ? 'selected' : '' ?>>
                                <?= $c['nama_cabor'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3 mb-2">
                    <div class="w-6 h-6 rounded bg-yellow-50 text-yellow-600 flex items-center justify-center text-xs"><i class="fa-solid fa-award"></i></div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Kualifikasi & Status</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori</label>
                    <select name="kategori" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <?php 
                        $kategori = ['Koordinator', 'Fisik', 'Teknik', 'Taktik'];
                        foreach($kategori as $k): 
                        ?>
                            <option value="<?= $k ?>" <?= ($data['kategori'] == $k) ? 'selected' : '' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade Lisensi</label>
                    <select name="grade" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="Daerah" <?= ($data['lisensi_grade'] == 'Daerah') ? 'selected' : '' ?>>Daerah</option>
                        <option value="Nasional" <?= ($data['lisensi_grade'] == 'Nasional') ? 'selected' : '' ?>>Nasional</option>
                        <option value="Internasional" <?= ($data['lisensi_grade'] == 'Internasional') ? 'selected' : '' ?>>Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Aktif</label>
                    <select name="status_aktif" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="Aktif" <?= ($data['status_aktif'] == 'Aktif') ? 'selected' : '' ?>>Aktif Melatih</option>
                        <option value="Tidak Aktif" <?= ($data['status_aktif'] == 'Tidak Aktif') ? 'selected' : '' ?>>Tidak Aktif / Cuti</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="index.php" class="px-6 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition uppercase tracking-widest">Batal</a>
            <button type="submit" name="update" class="px-8 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 text-sm uppercase tracking-widest">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php require_once '../../layouts/footer.php'; ?>
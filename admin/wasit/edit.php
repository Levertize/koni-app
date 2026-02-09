<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM wasit WHERE id_wasit = '$id'"));

if (isset($_POST['update'])) {
    $nik = htmlspecialchars($_POST['nik']);
    $nama = htmlspecialchars($_POST['nama']);
    $jk = htmlspecialchars($_POST['jenis_kelamin']);
    
    // FIX DISINI: Samakan variabel jadi $tgl_lahir biar nyambung sama form & query
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']); 
    $alamat = htmlspecialchars($_POST['alamat']);
    
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $lisensi = htmlspecialchars($_POST['lisensi']);
    $jam_terbang = htmlspecialchars($_POST['jam_terbang']);

    $query_foto = "";
    if ($_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = time() . '_' . rand() . '.' . $ext;
        
        if ($data['foto_profil'] && file_exists('../../uploads/foto_profil/' . $data['foto_profil'])) {
            unlink('../../uploads/foto_profil/' . $data['foto_profil']);
        }

        move_uploaded_file($_FILES['foto']['tmp_name'], '../../uploads/foto_profil/' . $foto);
        $query_foto = ", foto_profil='$foto'";
    }

    // UPDATE QUERY (Pake $tgl_lahir yang udah bener)
    $query = "UPDATE wasit SET 
              nik='$nik', nama_lengkap='$nama', jenis_kelamin='$jk',
              tgl_lahir='$tgl_lahir', alamat='$alamat',
              id_cabor='$id_cabor', 
              lisensi_grade='$lisensi', jam_terbang='$jam_terbang' 
              $query_foto 
              WHERE id_wasit='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Wasit Berhasil Diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}

$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");
$title = "Edit Wasit";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-yellow-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Data Wasit</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Perbarui Informasi Personil</p>
        </div>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIK</label>
                    <input type="number" name="nik" value="<?= $data['nik'] ?>" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $data['nama_lengkap'] ?>" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                        <option value="L" <?= ($data['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= ($data['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="<?= $data['tgl_lahir'] ?? '' ?>" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cabor</label>
                    <select name="id_cabor" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                        <?php while($c = mysqli_fetch_assoc($cabor_data)): ?>
                            <option value="<?= $c['id_cabor'] ?>" <?= ($c['id_cabor'] == $data['id_cabor']) ? 'selected' : '' ?>>
                                <?= $c['nama_cabor'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Domisili</label>
                    <textarea name="alamat" rows="2" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition"><?= $data['alamat'] ?? '' ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tingkat Lisensi</label>
                    <select name="lisensi" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                        <option value="Daerah" <?= ($data['lisensi_grade'] == 'Daerah') ? 'selected' : '' ?>>Daerah (C3/C2)</option>
                        <option value="Provinsi" <?= ($data['lisensi_grade'] == 'Provinsi') ? 'selected' : '' ?>>Provinsi (C1)</option>
                        <option value="Nasional" <?= ($data['lisensi_grade'] == 'Nasional') ? 'selected' : '' ?>>Nasional</option>
                        <option value="Internasional" <?= ($data['lisensi_grade'] == 'Internasional') ? 'selected' : '' ?>>Internasional</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Terbang</label>
                    <input type="number" name="jam_terbang" value="<?= $data['jam_terbang'] ?>" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Update Foto (Opsional)</label>
                    <div class="flex items-center gap-4">
                        <?php if($data['foto_profil']): ?>
                            <img src="../../uploads/foto_profil/<?= $data['foto_profil'] ?>" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                        <?php endif; ?>
                        <input type="file" name="foto" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="index.php" class="px-6 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition uppercase tracking-widest">Batal</a>
                <button type="submit" name="update" class="px-8 py-2.5 rounded-xl bg-yellow-500 text-white font-bold hover:bg-yellow-600 transition shadow-lg shadow-yellow-200 text-sm uppercase tracking-widest">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>

<?php require_once '../../layouts/footer.php'; ?>
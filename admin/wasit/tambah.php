<?php
session_start();
// FIX PATH: Mundur 2 langkah karena posisi di admin/wasit/
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

if (isset($_POST['tambah'])) {
    $nik = htmlspecialchars(trim($_POST['nik']));
    $username = htmlspecialchars(trim($_POST['username']));
    $nama = htmlspecialchars($_POST['nama']);
    
    // PERBAIKAN:
    // 1. Tempat Lahir DIHAPUS.
    // 2. Tanggal Lahir diganti jadi 'tgl_lahir' (Sesuai DB).
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);
    $alamat = htmlspecialchars($_POST['alamat']);

    // Tangkap Data Gender
    $jk = htmlspecialchars($_POST['jenis_kelamin']);
    
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $lisensi = htmlspecialchars($_POST['lisensi']);
    $jam_terbang = htmlspecialchars($_POST['jam_terbang']);
    
    // Default Password = NIK
    $password = password_hash($nik, PASSWORD_DEFAULT);

    // Upload Foto (Opsional)
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = time() . '_' . rand() . '.' . $ext;
        $target = '../../uploads/foto_profil/';
        
        if (!is_dir($target)) mkdir($target, 0777, true);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target . $foto);
    }

    // Cek Duplikat
    $cek = mysqli_query($conn, "SELECT * FROM wasit WHERE nik='$nik' OR username='$username'");
    if(mysqli_num_rows($cek) > 0) {
        $error_msg = "NIK atau Username udah kepake, bre! Coba yang lain.";
    } else {
        // QUERY INSERT (Pake tgl_lahir, tanpa tempat_lahir)
        $query = "INSERT INTO wasit (id_cabor, nik, username, nama_lengkap, jenis_kelamin, tgl_lahir, alamat, lisensi_grade, jam_terbang, password, foto_profil, status_verifikasi) 
                  VALUES ('$id_cabor', '$nik', '$username', '$nama', '$jk', '$tgl_lahir', '$alamat', '$lisensi', '$jam_terbang', '$password', '$foto', 'verified')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Wasit sukses ditambah! Akun aktif (Login: Username/NIK & Pass: NIK)'); window.location='index.php';</script>";
        } else {
            $error_msg = "Database Error: " . mysqli_error($conn);
        }
    }
}

$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");
$title = "Tambah Wasit";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-yellow-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tambah Wasit Baru</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Auto Register Akun</p>
        </div>
    </div>

    <?php if(isset($error_msg)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl text-sm text-red-700 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3 mb-2">
                    <div class="w-6 h-6 rounded bg-yellow-50 text-yellow-600 flex items-center justify-center text-xs"><i class="fa-solid fa-user-shield"></i></div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Identitas Akun</h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">NIK (Pass Default)</label>
                        <input type="number" name="nik" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Username</label>
                        <input type="text" name="username" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition" placeholder="Username unik">
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        <b>Info:</b> Password default akun ini adalah <b>Nomor NIK</b> yang didaftarkan.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Alamat Lengkap</label>
                    <textarea name="alamat" required rows="2" class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Cabor</label>
                    <select name="id_cabor" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                        <option value="">-- Pilih Cabor --</option>
                        <?php while($c = mysqli_fetch_assoc($cabor_data)): ?>
                            <option value="<?= $c['id_cabor'] ?>"><?= $c['nama_cabor'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3 mb-2">
                    <div class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center text-xs"><i class="fa-solid fa-award"></i></div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Kualifikasi</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Tingkat Lisensi</label>
                    <select name="lisensi" required class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm bg-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                        <option value="Daerah">Daerah (C3/C2)</option>
                        <option value="Provinsi">Provinsi (C1)</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Jam Terbang</label>
                    <input type="number" name="jam_terbang" value="0" class="w-full mt-1 border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    <p class="text-[10px] text-gray-400 mt-1">Total pertandingan yang dipimpin</p>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Foto Profil</label>
                    <input type="file" name="foto" accept="image/*" class="w-full mt-1 text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition">
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="index.php" class="px-6 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition uppercase tracking-widest">Batal</a>
            <button type="submit" name="tambah" class="px-8 py-2.5 rounded-xl bg-yellow-500 text-white font-bold hover:bg-yellow-600 transition shadow-lg shadow-yellow-200 text-sm uppercase tracking-widest">Simpan & Buat Akun</button>
        </div>
    </form>
</div>

<?php require_once '../../layouts/footer.php'; ?>
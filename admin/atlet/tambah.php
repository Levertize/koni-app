<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Logic Tambah Data
if (isset($_POST['tambah'])) {
    $nik = htmlspecialchars(trim($_POST['nik']));
    $username = htmlspecialchars(trim($_POST['username'])); // Inputan Baru
    $nama = htmlspecialchars($_POST['nama']);
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);
    $jk = htmlspecialchars($_POST['jk']);
    $alamat = htmlspecialchars($_POST['alamat']);
    
    // Default password pake NIK
    $password = password_hash($nik, PASSWORD_DEFAULT); 

    // Handle Foto
    $foto = null;
    if ($_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = time() . '_' . rand(100, 999) . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../../uploads/foto_profil/' . $foto);
    }

    // Cek Username/NIK kembar
    $cek = mysqli_query($conn, "SELECT * FROM atlet WHERE nik='$nik' OR username='$username'");
    if(mysqli_num_rows($cek) > 0) {
        $error_msg = "NIK atau Username udah kepake, bre!";
    } else {
        $query = "INSERT INTO atlet (id_cabor, nik, username, nama_lengkap, tgl_lahir, jenis_kelamin, alamat, password, foto_profil, status_verifikasi) 
                  VALUES ('$id_cabor', '$nik', '$username', '$nama', '$tgl_lahir', '$jk', '$alamat', '$password', '$foto', 'verified')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Atlet berhasil ditambahkan! Password default: NIK'); window.location='index.php';</script>";
        } else {
            $error_msg = mysqli_error($conn);
        }
    }
}

$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");

$title = "Tambah Atlet Baru";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-red-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tambah Atlet Baru</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Registrasi Manual & Buat Akun</p>
        </div>
    </div>

    <?php if(isset($error_msg)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
            <p class="text-red-700 text-sm font-bold">Gagal!</p>
            <p class="text-red-600 text-xs"><?= $error_msg ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card Kiri -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-bold text-gray-400 text-[10px] uppercase tracking-widest mb-4">Akun & Biodata</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">NIK (Untuk Login)</label>
                        <input type="number" name="nik" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="Nomor KTP/KIA">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Username</label>
                        <input type="text" name="username" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="opsional">
                    </div>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-700 border border-blue-100">
                    <i class="fa-solid fa-info-circle mr-1"></i> <b>Info:</b> Password default akun ini adalah <b>Nomor NIK</b>.
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="Nama Atlet">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Jenis Kelamin</label>
                        <select name="jk" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition cursor-pointer">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Tgl Lahir</label>
                        <input type="date" name="tgl_lahir" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Card Kanan -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-bold text-gray-400 text-[10px] uppercase tracking-widest mb-4">Keahlian & Domisili</h3>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Cabang Olahraga</label>
                    <select name="id_cabor" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition cursor-pointer">
                        <option value="">-- Pilih Cabor --</option>
                        <?php while($c = mysqli_fetch_assoc($cabor_data)): ?>
                            <option value="<?= $c['id_cabor'] ?>"><?= $c['nama_cabor'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Alamat Domisili</label>
                    <textarea name="alamat" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="Alamat lengkap..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Foto Profil</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="reset" class="px-6 py-3 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition uppercase tracking-widest">Reset</button>
            <button type="submit" name="tambah" class="px-8 py-3 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition shadow-lg shadow-red-200 uppercase tracking-widest">Simpan & Buat Akun</button>
        </div>
    </form>
</div>

<?php require_once '../../layouts/footer.php'; ?>
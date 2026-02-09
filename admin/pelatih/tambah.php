<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

if (isset($_POST['tambah'])) {
    $nik = htmlspecialchars(trim($_POST['nik']));
    $username = htmlspecialchars(trim($_POST['username']));
    $nama = htmlspecialchars($_POST['nama']);
    
    // TAMBAHAN: Tangkap Tgl Lahir & Alamat
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);
    $alamat = htmlspecialchars($_POST['alamat']);
    
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $kategori = htmlspecialchars($_POST['kategori']); 
    $grade = htmlspecialchars($_POST['grade']);       
    $status_aktif = htmlspecialchars($_POST['status_aktif']);
    
    $password = password_hash($nik, PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM pelatih WHERE nik='$nik' OR username='$username'");
    if(mysqli_num_rows($cek) > 0) {
        $error_msg = "NIK atau Username udah kepake, cari yang lain bre!";
    } else {
        // UPDATE QUERY: Masukin tgl_lahir & alamat
        $query = "INSERT INTO pelatih (id_cabor, nik, username, nama_lengkap, tgl_lahir, alamat, kategori, lisensi_grade, status_aktif, password, status_verifikasi) 
                  VALUES ('$id_cabor', '$nik', '$username', '$nama', '$tgl_lahir', '$alamat', '$kategori', '$grade', '$status_aktif', '$password', 'verified')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Pelatih sukses ditambah! Akun sudah aktif.'); window.location='index.php';</script>";
        } else {
            $error_msg = mysqli_error($conn);
        }
    }
}

$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");
$title = "Tambah Pelatih";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tambah Pelatih Baru</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Registrasi Manual & Buat Akun</p>
        </div>
    </div>

    <?php if(isset($error_msg)): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span class="text-sm font-bold"><?= $error_msg ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 h-fit">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Akun & Identitas</h3>
                
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">NIK (Login)</label>
                            <input type="number" name="nik" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition placeholder-gray-400" placeholder="Nomor KTP">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Username</label>
                            <input type="text" name="username" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition placeholder-gray-400" placeholder="Username unik">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <b>Info:</b> Password default akun ini adalah <b>Nomor NIK</b> yang didaftarkan.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition placeholder-gray-400" placeholder="Nama Pelatih">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Alamat Domisili</label>
                        <textarea name="alamat" required rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition placeholder-gray-400" placeholder="Alamat lengkap..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Cabang Olahraga</label>
                        <div class="relative">
                            <select name="id_cabor" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition appearance-none cursor-pointer">
                                <option value="">-- Pilih Cabor --</option>
                                <?php while($c = mysqli_fetch_assoc($cabor_data)): ?>
                                    <option value="<?= $c['id_cabor'] ?>"><?= $c['nama_cabor'] ?></option>
                                <?php endwhile; ?>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 h-fit">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Kualifikasi & Status</h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Kategori Tugas</label>
                        <div class="relative">
                            <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition appearance-none cursor-pointer">
                                <option value="Teknik">Pelatih Teknik</option>
                                <option value="Fisik">Pelatih Fisik</option>
                                <option value="Taktik">Pelatih Taktik</option>
                                <option value="Koordinator">Koordinator</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Lisensi / Grade</label>
                        <div class="relative">
                            <select name="grade" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition appearance-none cursor-pointer">
                                <option value="Daerah">Daerah (Kab/Prov)</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Status Aktif</label>
                        <div class="relative">
                            <select name="status_aktif" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition appearance-none cursor-pointer">
                                <option value="Aktif">✅ Aktif Melatih</option>
                                <option value="Tidak Aktif">❌ Tidak Aktif / Cuti</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="index.php" class="px-6 py-3 rounded-xl border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition uppercase tracking-widest">Batal</a>
            <button type="submit" name="tambah" class="px-8 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                Simpan & Buat Akun
            </button>
        </div>
    </form>
</div>

<?php require_once '../../layouts/footer.php'; ?>
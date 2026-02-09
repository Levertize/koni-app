<?php
session_start();
require_once '../config/koneksi.php';

// Cek User
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'atlet') {
    header("Location: ../login.php"); exit;
}

$id_user = $_SESSION['id_user'];

// --- LOGIC TAMBAH PRESTASI ---
if (isset($_POST['tambah_prestasi'])) {
    $event = htmlspecialchars($_POST['nama_kejuaraan']);
    $tingkat = $_POST['tingkat'];   // Sesuai ENUM di DB
    $tahun = $_POST['tahun'];
    $peringkat = htmlspecialchars($_POST['peringkat']); // Kolom di DB 'peringkat'
    
    // Upload File
    $filename = $_FILES['file_sertifikat']['name'];
    $tmp_name = $_FILES['file_sertifikat']['tmp_name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $new_name = time() . '_prestasi_' . $id_user . '.' . $ext;
    $folder = '../uploads/bukti/'; 

    // Bikin folder kalo belum ada
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
        if (move_uploaded_file($tmp_name, $folder . $new_name)) {
            
            // 1. INSERT KE TABEL PRESTASI (Sesuai kolom di db_koni)
            $sql_insert = "INSERT INTO prestasi (id_atlet, nama_kejuaraan, tingkat, peringkat, tahun, file_sertifikat, status_validasi) 
                           VALUES ('$id_user', '$event', '$tingkat', '$peringkat', '$tahun', '$new_name', 'pending')";
            
            if (mysqli_query($conn, $sql_insert)) {
                
                // 2. TRIGGER: UBAH STATUS ATLET JADI PENDING (Biar Admin Cek)
                mysqli_query($conn, "UPDATE atlet SET status_verifikasi='pending' WHERE id_atlet='$id_user'");

                echo "<script>alert('Berhasil! Prestasi ditambahkan & Status akun Anda sekarang PENDING menunggu verifikasi Admin.'); window.location='prestasi.php';</script>";
            } else {
                $err = mysqli_error($conn);
                echo "<script>alert('Gagal simpan ke database: $err');</script>";
            }

        } else {
            echo "<script>alert('Gagal upload file ke folder server.');</script>";
        }
    } else {
        echo "<script>alert('Format file wajib PDF atau Gambar (JPG/PNG)!');</script>";
    }
}

// --- LOGIC HAPUS PRESTASI ---
if (isset($_GET['hapus'])) {
    $id_pres = $_GET['hapus'];
    
    // Ambil info file dulu
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT file_sertifikat FROM prestasi WHERE id_prestasi='$id_pres' AND id_atlet='$id_user'"));
    
    if ($cek) {
        if (!empty($cek['file_sertifikat']) && file_exists('../uploads/bukti/' . $cek['file_sertifikat'])) {
            unlink('../uploads/bukti/' . $cek['file_sertifikat']);
        }
        mysqli_query($conn, "DELETE FROM prestasi WHERE id_prestasi='$id_pres'");
        echo "<script>alert('Data prestasi berhasil dihapus.'); window.location='prestasi.php';</script>";
    }
}

$title = "Prestasi Saya";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Prestasi & Penghargaan</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data kejuaraan yang pernah Anda raih.</p>
        </div>
        <button onclick="openModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 transition flex items-center gap-2 transform hover:scale-105">
            <i class="fa-solid fa-trophy"></i> Tambah Prestasi Baru
        </button>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 flex items-start gap-3">
        <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
        <div class="text-sm text-blue-800">
            <p class="font-bold">Informasi Penting:</p>
            <p>Setiap Anda menambahkan prestasi baru, status akun akan berubah menjadi <b class="text-orange-600">PENDING</b> hingga Admin memvalidasi data tersebut.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Kejuaraan / Event</th>
                        <th class="px-6 py-4">Tingkat</th>
                        <th class="px-6 py-4">Peringkat</th>
                        <th class="px-6 py-4">Tahun</th>
                        <th class="px-6 py-4">Status Validasi</th>
                        <th class="px-6 py-4">Bukti</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    $query = mysqli_query($conn, "SELECT * FROM prestasi WHERE id_atlet='$id_user' ORDER BY tahun DESC");
                    if (mysqli_num_rows($query) > 0):
                        while ($row = mysqli_fetch_assoc($query)): 
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            <?= $row['nama_kejuaraan'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold border border-gray-200">
                                <?= $row['tingkat'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-indigo-600">
                            <i class="fa-solid fa-medal mr-1"></i> <?= $row['peringkat'] ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-500">
                            <?= $row['tahun'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($row['status_validasi'] == 'valid'): ?>
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">Valid</span>
                            <?php elseif($row['status_validasi'] == 'invalid'): ?>
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold">Ditolak</span>
                            <?php else: ?>
                                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-full text-xs font-bold animate-pulse">Menunggu</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($row['file_sertifikat']): ?>
                                <a href="../uploads/bukti/<?= $row['file_sertifikat'] ?>" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat
                                </a>
                            <?php else: ?>
                                <span class="text-gray-300">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="?hapus=<?= $row['id_prestasi'] ?>" onclick="return confirm('Yakin hapus prestasi ini?')" class="text-red-400 hover:text-red-600 transition p-2 rounded-lg hover:bg-red-50">
                                <i class="fa-solid fa-trash-can text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-award text-4xl mb-2 opacity-50"></i>
                                <p>Belum ada data prestasi.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalPrestasi" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300" id="modalContent">
        <div class="bg-indigo-600 p-5 flex justify-between items-center text-white">
            <h3 class="font-bold text-lg"><i class="fa-solid fa-trophy mr-2"></i> Tambah Prestasi</h3>
            <button onclick="closeModal()" class="hover:bg-indigo-700 p-1.5 rounded-lg transition"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="p-6">
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Kejuaraan / Event</label>
                    <input type="text" name="nama_kejuaraan" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cth: Pekan Olahraga Nasional">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tingkat</label>
                        <select name="tingkat" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <option value="Kabupaten">Kabupaten</option>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun</label>
                        <input type="number" name="tahun" min="2000" max="<?= date('Y') ?>" value="<?= date('Y') ?>" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Peringkat / Capaian</label>
                    <input type="text" name="peringkat" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cth: Juara 1, Medali Emas">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Upload Sertifikat/Piagam</label>
                    <input type="file" name="file_sertifikat" required accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 rounded-lg">
                    <p class="text-[10px] text-gray-400 mt-1">Format PDF atau JPG. Max 2MB.</p>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" name="tambah_prestasi" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-md">
                        Simpan & Ajukan Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() {
        const modal = document.getElementById('modalPrestasi');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('modalPrestasi');
        const content = document.getElementById('modalContent');
        content.classList.remove('scale-100'); content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 200);
    }
</script>

<?php require_once '../layouts/footer.php'; ?>
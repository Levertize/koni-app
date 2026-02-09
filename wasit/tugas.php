<?php
session_start();
require_once '../config/koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'wasit') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 2. CEK STATUS LISENSI (LOGIC DIPERBAIKI)
$cek_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status_verifikasi, file_lisensi FROM wasit WHERE id_wasit = '$id_user'"));

// Helper Check
$punya_file = !empty($cek_data['file_lisensi']);
$ditolak    = ($cek_data['status_verifikasi'] == 'rejected');

// LOGIC BARU:
// Akses DITOLAK hanya jika: GAK PUNYA FILE sama sekali -ATAU- Statusnya REJECTED.
// (Jadi kalau statusnya 'Pending' tapi punya file, DIA BOLEH MASUK).
if (!$punya_file || $ditolak) {
    echo "<script>
        alert('AKSES DITOLAK!\\n\\nAnda belum bisa mengakses menu Tugas Wasit.\\nPastikan Anda sudah upload lisensi dan tidak berstatus ditolak.');
        window.location='dashboard.php';
    </script>";
    exit;
}

// --- LOGIC SIMPAN TUGAS ---
if (isset($_POST['simpan_tugas'])) {
    $event = htmlspecialchars($_POST['nama_pertandingan']);
    $tgl = htmlspecialchars($_POST['tanggal']);
    $lokasi = htmlspecialchars($_POST['lokasi']);
    $peran = htmlspecialchars($_POST['peran']);

    $insert = "INSERT INTO riwayat_wasit (id_wasit, nama_pertandingan, tanggal_tugas, lokasi_tugas, peran) 
               VALUES ('$id_user', '$event', '$tgl', '$lokasi', '$peran')";
    
    if (mysqli_query($conn, $insert)) {
        // Auto Update Jam Terbang (+1)
        mysqli_query($conn, "UPDATE wasit SET jam_terbang = jam_terbang + 1 WHERE id_wasit = '$id_user'");
        
        echo "<script>alert('Tugas berhasil dicatat! Jam terbang bertambah +1.'); window.location='tugas.php';</script>";
    } else {
        echo "<script>alert('Gagal simpan data: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil Riwayat Tugas
$riwayat = mysqli_query($conn, "SELECT * FROM riwayat_wasit WHERE id_wasit = '$id_user' ORDER BY tanggal_tugas DESC");

$title = "Riwayat Tugas Wasit";
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';
?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="dashboard.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-yellow-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Penugasan</h1>
            <p class="text-sm text-gray-500">Catat dan kelola riwayat kepemimpinan pertandingan Anda.</p>
        </div>
    </div>

    <?php if($cek_data['status_verifikasi'] == 'pending'): ?>
        <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm animate-pulse">
            <i class="fa-solid fa-circle-info text-lg"></i>
            <div>
                <b>Info:</b> Lisensi Anda sedang dalam proses verifikasi ulang oleh Admin. Anda tetap dapat mencatat tugas.
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="p-5 border-b border-gray-50 bg-yellow-50/50">
                    <h3 class="font-bold text-yellow-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Lapor Tugas Baru
                    </h3>
                </div>
                <div class="p-6">
                    <form action="" method="POST" class="space-y-5">
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Event / Kejuaraan</label>
                            <input type="text" name="nama_pertandingan" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" placeholder="Contoh: Porkab Banyumas 2025">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Lokasi / Venue</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute top-3.5 left-3.5 text-gray-300 text-sm"></i>
                                <input type="text" name="lokasi" required class="w-full border border-gray-200 rounded-xl pl-10 pr-3 py-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" placeholder="Contoh: GOR Satria">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Peran Tugas</label>
                            <div class="relative">
                                <select name="peran" required class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm bg-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition appearance-none cursor-pointer">
                                    <option value="" disabled selected>- Pilih Posisi -</option>
                                    <option value="Wasit Utama">Wasit Utama (Referee)</option>
                                    <option value="Wasit 2">Wasit 2 (Umpire)</option>
                                    <option value="Hakim Garis">Hakim Garis (Line Judge)</option>
                                    <option value="Juri">Juri / Penilai</option>
                                    <option value="Inspektur">Inspektur Pertandingan</option>
                                    <option value="Petugas Meja">Petugas Meja</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute top-4 right-4 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <button type="submit" name="simpan_tugas" class="w-full bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-yellow-200 mt-2 transform active:scale-[0.98]">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Laporan
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Riwayat Penugasan</h3>
                        <p class="text-sm text-gray-400">Daftar pertandingan yang telah Anda pimpin.</p>
                    </div>
                    <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-3 py-1.5 rounded-full border border-yellow-100 self-start sm:self-center">
                        Total: <?= mysqli_num_rows($riwayat) ?> Laga
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4">Detail Event</th>
                                <th class="px-6 py-4">Posisi</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(mysqli_num_rows($riwayat) > 0): ?>
                                <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
                                <tr class="hover:bg-yellow-50/30 transition group">
                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-700 text-base"><?= date('d', strtotime($r['tanggal_tugas'])) ?></span>
                                            <span class="text-xs text-gray-400 uppercase"><?= date('M Y', strtotime($r['tanggal_tugas'])) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="font-bold text-gray-800 text-base mb-1 group-hover:text-yellow-600 transition"><?= $r['nama_pertandingan'] ?></div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1.5 bg-gray-50 w-fit px-2 py-1 rounded border border-gray-100">
                                            <i class="fa-solid fa-map-pin text-gray-400"></i> <?= $r['lokasi_tugas'] ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200">
                                            <?= $r['peran'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 align-top text-right">
                                        <span class="text-xs font-bold text-green-600 flex items-center justify-end gap-1">
                                            <i class="fa-solid fa-circle-check"></i> Selesai
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-50">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 text-2xl">
                                                <i class="fa-solid fa-clipboard-list"></i>
                                            </div>
                                            <p class="text-gray-600 font-bold text-lg">Belum ada riwayat</p>
                                            <p class="text-sm text-gray-400 mt-1 max-w-xs">Mulailah karir perwasitan Anda dengan mencatat tugas pertama.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
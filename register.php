<?php
session_start();
require_once 'config/koneksi.php'; 

// Kalau udah login, tendang ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: " . ($_SESSION['role'] == 'super_admin' ? 'admin' : $_SESSION['role']) . "/dashboard.php");
    exit;
}

$cabor_data = mysqli_query($conn, "SELECT * FROM cabor ORDER BY nama_cabor ASC");

// Logic Register
if (isset($_POST['register'])) {
    $role = $_POST['role'];
    $nik = htmlspecialchars(trim($_POST['nik']));
    $username = htmlspecialchars(trim($_POST['username'])); // Inputan Baru
    $nama = htmlspecialchars($_POST['nama']);
    $id_cabor = htmlspecialchars($_POST['id_cabor']);
    $password = $_POST['password'];
    
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Cek NIK atau Username udah ada belum
    $cek_query = "SELECT * FROM $role WHERE nik = '$nik' OR username = '$username'";
    $cek = mysqli_query($conn, $cek_query);
    
    if (mysqli_num_rows($cek) > 0) {
        $error = "NIK atau Username ini sudah terpakai, bre! Ganti yang lain.";
    } else {
        // Query Insert (Tambah kolom username)
        if ($role == 'atlet') {
            $query = "INSERT INTO atlet (id_cabor, nik, username, nama_lengkap, password, status_verifikasi) 
                      VALUES ('$id_cabor', '$nik', '$username', '$nama', '$pass_hash', 'pending')";
        } elseif ($role == 'pelatih') {
            $query = "INSERT INTO pelatih (id_cabor, nik, username, nama_lengkap, password, status_verifikasi, kategori, lisensi_grade, status_aktif) 
                      VALUES ('$id_cabor', '$nik', '$username', '$nama', '$pass_hash', 'pending', 'Teknik', 'Daerah', 'Aktif')";
        } elseif ($role == 'wasit') {
            $query = "INSERT INTO wasit (id_cabor, nik, username, nama_lengkap, password, status_verifikasi) 
                      VALUES ('$id_cabor', '$nik', '$username', '$nama', '$pass_hash', 'pending')";
        }

        if (mysqli_query($conn, $query)) {
            echo "<script>
                alert('Registrasi Berhasil! Tunggu verifikasi admin ya.');
                window.location='login.php';
            </script>";
        } else {
            $error = "Gagal daftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - KONI Banyumas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-red-600 p-6 text-center text-white">
            <h1 class="text-xl font-bold">Buat Akun Baru</h1>
            <p class="text-red-100 text-xs">Bergabung dengan KONI Kabupaten Banyumas</p>
        </div>

        <div class="p-8">
            <?php if (isset($error)) : ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-xs font-bold mb-6 border border-red-200">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-4">
                
                <!-- Pilih Role -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Daftar Sebagai</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="atlet" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition text-sm font-bold text-gray-500 hover:bg-gray-50">Atlet</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="pelatih" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-sm font-bold text-gray-500 hover:bg-gray-50">Pelatih</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="wasit" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 transition text-sm font-bold text-gray-500 hover:bg-gray-50">Wasit</div>
                        </label>
                    </div>
                </div>

                <!-- Input Biodata -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIK (KTP)</label>
                        <input type="number" name="nik" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm" placeholder="Nomor KTP">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Username</label>
                        <input type="text" name="username" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm" placeholder="Buat Login">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm" placeholder="Nama sesuai KTP">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cabang Olahraga</label>
                    <select name="id_cabor" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm bg-white">
                        <option value="">-- Pilih Cabor --</option>
                        <?php foreach ($cabor_data as $c) : ?>
                            <option value="<?= $c['id_cabor'] ?>"><?= $c['nama_cabor'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Buat Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm" placeholder="Minimal 6 karakter">
                </div>

                <button type="submit" name="register" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-black transition shadow-lg mt-4">
                    DAFTAR SEKARANG
                </button>
            </form>

            <div class="mt-6 text-center text-sm border-t border-gray-100 pt-4">
                <span class="text-gray-500">Sudah punya akun?</span>
                <a href="login.php" class="font-bold text-red-600 hover:underline ml-1">Login disini</a>
            </div>
        </div>
    </div>

</body>
</html>
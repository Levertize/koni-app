<?php
session_start();
require_once 'config/koneksi.php';

// 1. CEK SESSION (Kalau udah login, lempar ke dashboard)
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'super_admin' || $_SESSION['role'] == 'admin_cabor') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: " . $_SESSION['role'] . "/dashboard.php");
    }
    exit;
}

// Inisialisasi Pesan
$error_msg = "";
$warning_msg = "";
$input_user = "";
$input_role = "";

// 2. LOGIC LOGIN (Di sini biang keroknya kita benerin)
if (isset($_POST['login'])) {
    $input_role = $_POST['role'];
    $input_user = trim($_POST['username']); 
    $password   = $_POST['password'];

    $username_safe = mysqli_real_escape_string($conn, $input_user);

    // --- A. LOGIN ADMIN ---
    if ($input_role == 'admin') {
        $cek = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username_safe'");
        if (mysqli_num_rows($cek) === 1) {
            $data = mysqli_fetch_assoc($cek);
            if (password_verify($password, $data['password'])) {
                // Set Session
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $data['id_admin'];
                $_SESSION['nama'] = $data['nama_lengkap'];
                $_SESSION['role'] = $data['role'];
                header("Location: admin/dashboard.php");
                exit;
            } else {
                $error_msg = "Password salah, Bre!";
            }
        } else {
            $error_msg = "Username Admin tidak ditemukan!";
        }
    } 
    
    // --- B. LOGIN USER (ATLET, PELATIH, WASIT) ---
    else {
        $tabel = $input_role;
        $pk    = "id_" . $tabel;

        // Cek NIK atau Username
        $cek = mysqli_query($conn, "SELECT * FROM $tabel WHERE nik = '$username_safe' OR username = '$username_safe'");

        if (mysqli_num_rows($cek) === 1) {
            $data = mysqli_fetch_assoc($cek);

            if (password_verify($password, $data['password'])) {
                
                // === LOGIKA FILTER PENTING (SESUAI FLOWCHART) ===
                
                // 1. Cek Status REJECTED
                if ($data['status_verifikasi'] == 'rejected') {
                    $error_msg = "<b>Login Ditolak!</b><br>Pendaftaran Anda ditolak Admin. Silakan hubungi pengurus.";
                }
                
                // 2. Cek Status PENDING (Ini kuncinya!)
                else if ($data['status_verifikasi'] == 'pending') {
                    
                    // Kita cek apakah dia punya file atau enggak?
                    $file_ada = false;
                    
                    if ($input_role == 'pelatih' && !empty($data['file_sertifikat'])) $file_ada = true;
                    if ($input_role == 'wasit' && !empty($data['file_lisensi'])) $file_ada = true;
                    if ($input_role == 'atlet' && !empty($data['file_bukti'])) $file_ada = true;

                    // Kalo PENDING + GAK ADA FILE = AKUN BARU DAFTAR -> BLOKIR
                    if (!$file_ada) {
                        $warning_msg = "<b>Pendaftaran Berhasil!</b><br>Akun Anda sedang menunggu persetujuan (ACC) dari Admin. Mohon bersabar ya.";
                    } 
                    // Kalo PENDING + ADA FILE = USER LAMA UPDATE -> LANJUT LOGIN
                    else {
                        $_SESSION['login'] = true;
                        $_SESSION['id_user'] = $data[$pk];
                        $_SESSION['nama'] = $data['nama_lengkap'];
                        $_SESSION['role'] = $input_role;
                        header("Location: $input_role/dashboard.php");
                        exit;
                    }
                }
                
                // 3. Status VERIFIED (Lanjut Login)
                else {
                    $_SESSION['login'] = true;
                    $_SESSION['id_user'] = $data[$pk];
                    $_SESSION['nama'] = $data['nama_lengkap'];
                    $_SESSION['role'] = $input_role;
                    header("Location: $input_role/dashboard.php");
                    exit;
                }

            } else {
                $error_msg = "Password salah!";
            }
        } else {
            $error_msg = "Username/NIK tidak terdaftar sebagai <b>" . strtoupper($input_role) . "</b>.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KONI Banyumas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-[440px] rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        
        <div class="bg-red-600 p-8 text-center text-white relative">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-8xl"><i class="fa-solid fa-shield-halved"></i></div>
            <img src="/koni-app/assets/img/logo.png" alt="Logo KONI" class="h-16 mx-auto mb-4 bg-white p-2 rounded-full shadow-lg">
            <h1 class="text-2xl font-bold tracking-tight">SiMALT KONI</h1>
            <p class="text-red-100 text-xs font-medium uppercase tracking-widest mt-1">Sistem Manajemen Atlet & Pelatih</p>
        </div>

        <div class="p-8">
            
            <?php if (!empty($error_msg)) : ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-xs font-bold mb-6 border border-red-100 flex items-start gap-3 animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
                    <span><?= $error_msg ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($warning_msg)) : ?>
                <div class="bg-yellow-50 text-yellow-700 p-4 rounded-xl text-xs font-bold mb-6 border border-yellow-200 flex items-start gap-3">
                    <i class="fa-solid fa-clock text-lg mt-0.5 text-yellow-600"></i>
                    <div class="leading-relaxed"><?= $warning_msg ?></div>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-5">
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Masuk Sebagai</label>
                    <div class="relative">
                        <i class="fa-solid fa-users absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                        <select name="role" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 outline-none transition appearance-none cursor-pointer">
                            <option value="admin" <?= $input_role == 'admin' ? 'selected' : '' ?>>Administrator / Pengurus</option>
                            <option value="atlet" <?= $input_role == 'atlet' ? 'selected' : '' ?>>Atlet Terdaftar</option>
                            <option value="pelatih" <?= $input_role == 'pelatih' ? 'selected' : '' ?>>Pelatih Cabang Olahraga</option>
                            <option value="wasit" <?= $input_role == 'wasit' ? 'selected' : '' ?>>Wasit & Juri</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Username / NIK</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                        <input type="text" name="username" value="<?= htmlspecialchars($input_user) ?>" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="Masukkan Username atau NIK">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                        <input type="password" name="password" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" name="login" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-200 transition transform active:scale-[0.98]">
                    MASUK KE SISTEM
                </button>
            </form>

            <div class="mt-6 text-center pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 mb-2">Belum punya akun?</p>
                <a href="register.php" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wide bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100">
                    <i class="fa-solid fa-user-plus"></i> Daftar Disini
                </a>
            </div>

            <div class="mt-4 text-center">
                <a href="index.php" class="text-[10px] font-bold text-slate-400 hover:text-red-600 transition uppercase tracking-widest">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Website
                </a>
            </div>
        </div>
    </div>

</body>
</html>
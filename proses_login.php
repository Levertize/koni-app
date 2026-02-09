<?php
session_start();
require_once 'config/koneksi.php';

if (isset($_POST['login'])) {
    $role_pilihan = $_POST['role'];
    $username     = mysqli_real_escape_string($conn, trim($_POST['username'])); 
    $password     = $_POST['password'];

    // 1. LOGIC ADMIN (Tetap sama)
    if ($role_pilihan == 'admin') {
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
        if (mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);
            if (password_verify($password, $row['password'])) {
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $row['id_admin'];
                $_SESSION['nama'] = $row['nama_lengkap'];
                $_SESSION['role'] = $row['role']; 
                header("Location: admin/dashboard.php");
                exit;
            } else {
                $_SESSION['error'] = "Password salah!";
                $_SESSION['username_input'] = $username;
                $_SESSION['role_input'] = $role_pilihan;
                header("Location: login.php"); exit;
            }
        } else {
            $_SESSION['error'] = "Username Admin tidak ditemukan!";
            header("Location: login.php"); exit;
        }
    } 
    
    // 2. LOGIC USER (PELATIH & WASIT & ATLET)
    else {
        $tabel = $role_pilihan; 
        $pk    = "id_" . $tabel; 

        $query = mysqli_query($conn, "SELECT * FROM $tabel WHERE nik = '$username' OR username = '$username'");

        if (mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            if (password_verify($password, $row['password'])) {
                
                // === LOGIKA FILTER SESUAI FLOWCHART === //

                // 1. Kalo REJECTED -> PASTI DITOLAK
                if ($row['status_verifikasi'] == 'rejected') {
                    $_SESSION['error'] = "<b>Login Ditolak!</b><br>Pendaftaran Anda ditolak Admin. Silakan hubungi pengurus.";
                    header("Location: login.php"); exit;
                }

                // 2. Kalo PENDING -> CEK DIA BARU DAFTAR ATAU LAGI UPDATE?
                if ($row['status_verifikasi'] == 'pending') {
                    
                    // Cek nama kolom file berdasarkan role
                    $file_cek = null;
                    if ($role_pilihan == 'pelatih') $file_cek = $row['file_sertifikat'];
                    elseif ($role_pilihan == 'wasit') $file_cek = $row['file_lisensi'];
                    elseif ($role_pilihan == 'atlet') $file_cek = $row['file_bukti']; // Atlet biasanya prestasi

                    // LOGIKA UTAMA:
                    // Kalau File KOSONG = Berarti baru daftar (Biodata doang) -> BLOKIR
                    // Kalau File ADA = Berarti user lama lagi update file -> BOLEH MASUK
                    if (empty($file_cek)) {
                        $_SESSION['warning'] = "<b>Akun Belum Aktif!</b><br>Pendaftaran Anda sedang menunggu persetujuan (ACC) dari Admin.";
                        header("Location: login.php"); exit;
                    }
                }

                // 3. LOLOS FILTER (Verified user / Pending user with file)
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $row[$pk];
                $_SESSION['nama'] = $row['nama_lengkap'];
                $_SESSION['role'] = $role_pilihan;
                
                header("Location: $role_pilihan/dashboard.php");
                exit;

            } else {
                $_SESSION['error'] = "Password salah!";
                $_SESSION['username_input'] = $username;
                $_SESSION['role_input'] = $role_pilihan;
                header("Location: login.php"); exit;
            }
        } else {
            $_SESSION['error'] = "Username/NIK tidak terdaftar.";
            header("Location: login.php"); exit;
        }
    }
} else {
    header("Location: login.php"); exit;
}
?>
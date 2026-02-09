<?php
// 1. START SESSION & HANCURKAN
session_start();
$_SESSION = [];
session_unset();
session_destroy();

// Hapus cookies session (Deep Clean)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout...</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Slate-50 */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
    </style>
</head>
<body>

    <script>
        // Logika "Super Premium" SweetAlert
        Swal.fire({
            title: 'Berhasil Keluar!',
            text: 'Terima kasih telah menggunakan sistem ini. Sampai jumpa lagi!',
            icon: 'success',
            
            // UI Tweaks biar mahal
            width: '400px',
            padding: '2em',
            color: '#1e293b', // Slate-800
            background: '#ffffff',
            backdrop: `
                rgba(15, 23, 42, 0.4)
                left top
                no-repeat
            `,
            
            // Tombol OK
            confirmButtonText: 'Login Kembali',
            confirmButtonColor: '#ef4444', // Red-500 (Sesuai tema Koni)
            
            // Auto Redirect (Timer)
            timer: 2000, // 2 Detik
            timerProgressBar: true,
            
            // Animasi Custom
            showClass: {
                popup: 'swal2-show',
                backdrop: 'swal2-backdrop-show',
                icon: 'swal2-icon-show'
            },
            hideClass: {
                popup: 'swal2-hide',
                backdrop: 'swal2-backdrop-hide',
                icon: 'swal2-icon-hide'
            }
        }).then((result) => {
            // Pas timer abis atau tombol dipencet, lempar ke login
            window.location.href = 'index.php'; 
        });
    </script>

</body>
</html>
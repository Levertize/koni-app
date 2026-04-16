🏅 Sistem Informasi Manajemen KONI Banyumas (SIM KONI)

Aplikasi berbasis web terintegrasi untuk Komite Olahraga Nasional Indonesia (KONI) Kabupaten Banyumas. Sistem ini dirancang khusus untuk mendigitalisasi dan mempermudah manajemen pendataan insan olahraga, termasuk pengelolaan atlet, pelatih, wasit, pengurus cabang olahraga (cabor), serta memfasilitasi publikasi informasi ke masyarakat luas.

🚀 Fitur Utama & Hak Akses (Multi-Role)

Sistem ini memiliki portal publik dan 4 hak akses (roles) untuk dashboard manajemen:

🌐 1. Portal Publik (Frontend)

Beranda & Profil: Informasi tentang struktur organisasi, statistik olahraga, dan kontak KONI Banyumas.

Informasi: Akses publik ke daftar berita olahraga terbaru dan pengumuman resmi.

Direktori: Menampilkan daftar cabor, atlet, dan pengurus yang terdaftar.

👨‍💻 2. Admin Panel

Pusat kendali aplikasi untuk staf atau pengurus inti KONI.

Dashboard Statistik: Ringkasan data (jumlah cabor, atlet, pelatih, wasit).

Manajemen Master Data: Pengelolaan Cabang Olahraga (Cabor), Berita, dan Pengumuman.

Verifikasi Pengguna: Memverifikasi pendaftaran akun baru untuk Atlet, Pelatih, dan Wasit.

Manajemen Insan Olahraga: Mengelola (Tambah, Edit, Hapus, Detail) data Atlet, Pelatih, dan Wasit secara menyeluruh.

🏃‍♂️ 3. Panel Atlet

Manajemen Profil: Pembaruan biodata dan foto profil atlet.

Portofolio Prestasi: Fitur untuk mengunggah dan mengelola riwayat prestasi beserta bukti sertifikat.

🎯 4. Panel Pelatih

Manajemen Profil: Pembaruan biodata dan foto profil pelatih.

Lisensi Kepelatihan: Mengunggah dan mengelola riwayat lisensi kepelatihan (dengan file bukti).

Program Latihan: (Fitur pengembangan) Penyusunan dan pencatatan program kepelatihan.

⚖️ 5. Panel Wasit

Manajemen Profil: Pembaruan biodata dan foto profil wasit.

Lisensi Perwasitan: Mengunggah dan mengelola sertifikat/lisensi wasit.

Riwayat Penugasan: Pencatatan jadwal dan riwayat tugas perwasitan.

🛠️ Teknologi yang Digunakan

Bahasa Pemrograman: PHP Native (Minimal direkomendasikan PHP 7.4 / 8.x)

Database: MySQL / MariaDB

Frontend: HTML5, CSS3, JavaScript

Arsitektur: Procedural / Modular PHP

📂 Struktur Direktori

koni-app/
├── admin/          # Modul CRUD & Dashboard khusus hak akses Admin
├── atlet/          # Modul Dashboard & pendataan khusus Atlet
├── pelatih/        # Modul Dashboard & pendataan khusus Pelatih
├── wasit/          # Modul Dashboard & pendataan khusus Wasit
├── assets/         # Aset statis (Gambar bawaan, custom CSS, JS)
├── config/         # Konfigurasi sistem (koneksi.php)
├── frontend/       # Komponen layout untuk portal publik
├── layouts/        # Komponen layout reusable dashboard (header, sidebar, footer, navbar)
├── pages/          # Halaman portal publik (Berita, Tentang, Kontak, Struktur, Statistik)
├── uploads/        # Direktori penyimpanan file dinamis:
│   ├── berita/     # Cover/Thumbnail berita
│   ├── bukti/      # Bukti prestasi atlet
│   ├── foto_profil/# Foto profil user
│   ├── lisensi/    # File lisensi pelatih & wasit
│   ├── pengumuman/ # Lampiran pengumuman (PDF dll)
│   └── sertifikat/ # Sertifikat pendukung
├── index.php       # Entry point / Halaman Utama portal publik
├── login.php       # Halaman Login
├── register.php    # Halaman Pendaftaran akun baru
└── proses_login.php# Logika autentikasi multi-role


💻 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal Anda menggunakan XAMPP, Laragon, atau web server lokal sejenis:

1. Persiapan Repository

Clone repository ini ke dalam direktori server lokal Anda (htdocs untuk XAMPP, atau www untuk Laragon).

git clone [https://github.com/username-kamu/koni-app.git](https://github.com/username-kamu/koni-app.git)


Pastikan nama folder adalah koni-app.

2. Persiapan Database

Buka MySQL client favorit Anda (misal: phpMyAdmin di http://localhost/phpmyadmin).

Buat database baru, contoh: koni_banyumas.

Import file database berformat .sql (biasanya db_koni.sql atau sejenisnya jika dilampirkan) ke dalam database yang baru dibuat.

3. Konfigurasi Koneksi

Buka file config/koneksi.php menggunakan Code Editor (VS Code/Sublime Text), dan sesuaikan dengan kredensial database lokal Anda:

<?php
$host = "localhost";
$user = "root";       // Default XAMPP/Laragon
$pass = "";           // Kosongkan jika tidak ada password
$db   = "koni_banyumas"; // Sesuaikan dengan nama database Anda
// ...
?>


4. Menjalankan Aplikasi

Buka browser dan akses URL berikut:

http://localhost/koni-app


🔐 Akun Default (Untuk Keperluan Testing)

Gunakan kredensial berikut untuk mencoba masuk ke dalam sistem (Pastikan untuk mengganti password di environment produksi):

Role

Username / Email

Password

Admin

admin

password

Atlet

atlet

password

Pelatih

pelatih

password

Wasit

wasit

password

🤝 Kontribusi

Sistem ini dikembangkan untuk keperluan instansi. Jika Anda menemukan bug atau memiliki saran fitur tambahan:

Fork repository ini.

Buat branch fitur Anda (git checkout -b feature/FiturBaru).

Commit perubahan Anda (git commit -m 'Menambahkan FiturBaru').

Push ke branch (git push origin feature/FiturBaru).

Buat Pull Request.

📄 Lisensi & Hak Cipta

Hak Cipta © 2026 KONI Kabupaten Banyumas. All rights reserved.
Dikembangkan oleh: Tim Kelompok PKM

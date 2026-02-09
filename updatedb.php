<?php
require_once 'config/koneksi.php';

echo "<div style='font-family: sans-serif; padding: 20px; line-height: 1.6;'>";
echo "<h2 style='color: #db2777;'>📰 Upgrade Database: Modul Berita</h2>";

// Bikin tabel berita
$sql = "CREATE TABLE IF NOT EXISTS berita (
    id_berita INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    kategori VARCHAR(50) DEFAULT 'Umum',
    isi_berita TEXT NOT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    penulis VARCHAR(100) DEFAULT 'Admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "<p>✅ Tabel <b>berita</b> berhasil dibuat.</p>";
    
    // Seed Data Dummy biar gak kosong
    $cek_isi = mysqli_query($conn, "SELECT * FROM berita");
    if (mysqli_num_rows($cek_isi) == 0) {
        $judul = "Seleksi Atlet POPDA 2026 Dimulai";
        $slug = "seleksi-atlet-popda-2026-dimulai";
        $isi = "KONI Banyumas resmi membuka seleksi untuk Pekan Olahraga Pelajar Daerah tahun 2026. Segera daftarkan diri anda melalui pengcab masing-masing.";
        
        $sql_seed = "INSERT INTO berita (judul, slug, kategori, isi_berita) VALUES ('$judul', '$slug', 'Kompetisi', '$isi')";
        mysqli_query($conn, $sql_seed);
        echo "<p>🌱 Data dummy berita berhasil ditambahkan.</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Gagal bikin tabel: " . mysqli_error($conn) . "</p>";
}

echo "<hr>";
echo "<a href='admin/berita/index.php' style='background: #db2777; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Kelola Berita >></a>";
echo "</div>";
?>
<?php
require_once '../config/koneksi.php';
header('Content-Type: application/json');

$events = [];

// ==========================================
// 1. DATA LATIHAN (PELATIH)
// ==========================================
// Ganti LEFT JOIN jadi JOIN (INNER JOIN)
// Artinya: Hanya ambil program yg pelatihnya MASIH ADA di database
$query_latihan = "SELECT program_latihan.*, pelatih.nama_lengkap, cabor.nama_cabor 
                  FROM program_latihan 
                  JOIN pelatih ON program_latihan.id_pelatih = pelatih.id_pelatih
                  LEFT JOIN cabor ON pelatih.id_cabor = cabor.id_cabor";

$result_latihan = mysqli_query($conn, $query_latihan);

if ($result_latihan) {
    while ($row = mysqli_fetch_assoc($result_latihan)) {
        // Tentukan Jam (Default jika null)
        $jam_mulai = isset($row['created_at']) ? date('H:i', strtotime($row['created_at'])) : '08:00';
        
        $events[] = [
            'title' => "Latihan: " . $row['judul_program'],
            'start' => $row['tanggal_mulai'],
            
            // WARNA HIJAU (Teks Putih)
            'backgroundColor' => '#10b981',
            'borderColor'     => '#10b981',
            'textColor'       => '#ffffff', 

            'extendedProps' => [
                'type'   => 'latihan',
                'pic'    => $row['nama_lengkap'], 
                'cabor'  => $row['nama_cabor'] ?? '-',
                'waktu'  => $jam_mulai . ' WIB',
                'target' => $row['target_latihan'],
                'desc'   => $row['deskripsi'] ?? 'Latihan rutin.',
                'lokasi' => 'Lapangan Cabor'
            ]
        ];
    }
}

// ==========================================
// 2. DATA TUGAS (WASIT)
// ==========================================
// Ganti LEFT JOIN jadi JOIN (INNER JOIN)
// Artinya: Hanya ambil tugas yg wasitnya MASIH ADA di database
$query_wasit = "SELECT riwayat_wasit.*, wasit.nama_lengkap, cabor.nama_cabor 
                FROM riwayat_wasit 
                JOIN wasit ON riwayat_wasit.id_wasit = wasit.id_wasit
                LEFT JOIN cabor ON wasit.id_cabor = cabor.id_cabor";

$result_wasit = mysqli_query($conn, $query_wasit);

if ($result_wasit) {
    while ($row = mysqli_fetch_assoc($result_wasit)) {
        // Gabung Tanggal + Jam
        $jam_tugas = isset($row['jam_tugas']) ? $row['jam_tugas'] : '08:00:00';
        $start_full = $row['tanggal_tugas'] . 'T' . $jam_tugas;

        $events[] = [
            'title' => "Wasit: " . $row['nama_pertandingan'],
            'start' => $start_full,
            
            // WARNA KUNING (Teks Hitam)
            'backgroundColor' => '#f59e0b',
            'borderColor'     => '#f59e0b',
            'textColor'       => '#000000',

            'extendedProps' => [
                'type'   => 'wasit',
                'pic'    => $row['nama_lengkap'], 
                'cabor'  => $row['nama_cabor'] ?? 'Umum',
                'waktu'  => date('H:i', strtotime($jam_tugas)) . ' WIB',
                'peran'  => $row['peran'],
                'lokasi' => $row['lokasi_tugas'],
                'desc'   => "Bertugas di event " . $row['nama_pertandingan']
            ]
        ];
    }
}

echo json_encode($events);
?>
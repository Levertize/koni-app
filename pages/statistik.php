<?php
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';

// 1. Hitung Atlet per Cabor (Top 5)
$query_cabor = mysqli_query($conn, "SELECT c.nama_cabor, COUNT(a.id_atlet) as total 
                                   FROM cabor c 
                                   LEFT JOIN atlet a ON c.id_cabor = a.id_cabor 
                                   GROUP BY c.id_cabor 
                                   ORDER BY total DESC LIMIT 5");

$labels_cabor = [];
$data_cabor = [];
while($row = mysqli_fetch_assoc($query_cabor)){
    $labels_cabor[] = $row['nama_cabor'];
    $data_cabor[] = $row['total'];
}

// 2. Hitung Gender Atlet
$cowok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM atlet WHERE jenis_kelamin='L'"))['t'];
$cewek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM atlet WHERE jenis_kelamin='P'"))['t'];

// 3. Total Data
$total_atlet = $cowok + $cewek;
$total_pelatih = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pelatih"))['t'];
$total_wasit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM wasit"))['t'];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    <div class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-heading font-bold uppercase mb-2">Data & Statistik</h1>
            <p class="text-gray-400 text-sm">Visualisasi data olahraga Kabupaten Banyumas secara Real-time.</p>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-red-600 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Total Atlet</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $total_atlet ?></h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-running"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-blue-600 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Total Pelatih</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $total_pelatih ?></h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-yellow-500 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Total Wasit</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $total_wasit ?></h3>
                </div>
                <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-flag-checkered"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple text-red-600"></i> Cabang Olahraga Terpopuler
                </h3>
                <canvas id="chartCabor"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-blue-600"></i> Rasio Atlet Laki-laki vs Perempuan
                </h3>
                <div class="w-full max-w-xs mx-auto">
                    <canvas id="chartGender"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Rekapitulasi Data Per Cabang Olahraga</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-gray-500 font-bold uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Nama Cabor</th>
                            <th class="px-6 py-4 text-center">Jml Atlet</th>
                            <th class="px-6 py-4 text-center">Jml Pelatih</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php 
                        $all_cabor = mysqli_query($conn, "SELECT c.*, 
                            (SELECT COUNT(*) FROM atlet WHERE id_cabor=c.id_cabor) as a,
                            (SELECT COUNT(*) FROM pelatih WHERE id_cabor=c.id_cabor) as p
                            FROM cabor c ORDER BY nama_cabor ASC");
                        while($c = mysqli_fetch_assoc($all_cabor)): 
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-800"><?= $c['nama_cabor'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $c['a'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $c['p'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-bold uppercase">Aktif</span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
    // 1. Chart Cabor (Bar)
    const ctx1 = document.getElementById('chartCabor').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels_cabor) ?>,
            datasets: [{
                label: 'Jumlah Atlet',
                data: <?= json_encode($data_cabor) ?>,
                backgroundColor: '#dc2626',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // 2. Chart Gender (Doughnut)
    const ctx2 = document.getElementById('chartGender').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [<?= $cowok ?>, <?= $cewek ?>],
                backgroundColor: ['#2563eb', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

<?php require_once '../frontend/layouts/footer.php'; ?>
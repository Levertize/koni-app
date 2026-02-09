<?php
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    <div class="bg-red-800 py-12 text-center text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        <div class="container mx-auto relative z-10 px-6">
            <h1 class="text-3xl md:text-4xl font-heading font-bold uppercase mb-2">Struktur Organisasi</h1>
            <p class="text-red-200 text-xs md:text-sm font-bold tracking-widest uppercase">KONI Kabupaten Banyumas Periode 2025-2029</p>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 -mt-8 relative z-20">
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-6 md:p-10 text-center">
            
            <div class="mb-8 max-w-2xl mx-auto">
                <p class="text-gray-600 leading-relaxed">
                    Struktur organisasi KONI Kabupaten Banyumas disusun untuk memastikan kinerja pembinaan prestasi olahraga berjalan efektif, efisien, dan transparan.
                </p>
            </div>

            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 bg-gray-50 inline-block w-full">
                <img src="https://via.placeholder.com/1200x800/ffffff/d1d5db?text=BAGAN+STRUKTUR+ORGANISASI+KONI+BANYUMAS" 
                     class="w-full h-auto rounded shadow-sm mx-auto" 
                     alt="Bagan Struktur Organisasi">
            </div>

            <div class="mt-8 flex justify-center gap-4">
                <a href="#" class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">
                    <i class="fa-solid fa-download"></i> Download SK Pengurus
                </a>
            </div>

        </div>
    </div>
</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
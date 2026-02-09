<?php
// 1. Panggil Koneksi & Layout
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';

// Logic Kirim Pesan (Dummy/Simulasi)
$msg = "";
if(isset($_POST['kirim_pesan'])){
    // Disini nanti logika masukin ke database tabel 'pesan_masuk'
    $msg = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-sm flex items-center gap-2'>
                <i class='fa-solid fa-check-circle'></i> Pesan Anda berhasil dikirim! Tim kami akan segera membalas via Email.
            </div>";
}
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">

    <div class="bg-gray-900 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-heading font-bold uppercase mb-4 tracking-wide">Hubungi Kami</h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                Punya pertanyaan seputar pendaftaran atlet, jadwal kejuaraan, atau kemitraan? <br>
                Tim KONI Kabupaten Banyumas siap membantu Anda.
            </p>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-10 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                
                <div class="p-8 md:p-12 bg-red-600 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl"></div>

                    <h3 class="text-2xl font-bold font-heading uppercase mb-8 relative z-10">Informasi Kantor</h3>
                    
                    <div class="space-y-8 relative z-10">
                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-2xl group-hover:bg-white group-hover:text-red-600 transition duration-300">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Alamat Sekretariat</h4>
                                <p class="text-red-100 text-sm leading-relaxed opacity-90">
                                    Komplek GOR Satria,<br>
                                    Jl. Prof. Dr. Suharso No. 1, Purwokerto,<br>
                                    Jawa Tengah 53114
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-xl group-hover:bg-white group-hover:text-red-600 transition duration-300">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Email Resmi</h4>
                                <p class="text-red-100 text-sm opacity-90 mb-1">admin@konibanyumas.or.id</p>
                                <p class="text-red-100 text-sm opacity-90">humas@konibanyumas.or.id</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-xl group-hover:bg-white group-hover:text-red-600 transition duration-300">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">WhatsApp / Telepon</h4>
                                <p class="text-red-100 text-sm opacity-90 mb-1">+62 812-3456-7890 (Admin)</p>
                                <p class="text-red-100 text-sm opacity-90">(0281) 636xxx (Kantor)</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 relative z-10">
                        <p class="text-sm font-bold uppercase tracking-widest opacity-70 mb-4">Ikuti Kami</p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center hover:bg-white hover:text-red-600 transition"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center hover:bg-white hover:text-red-600 transition"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center hover:bg-white hover:text-red-600 transition"><i class="fa-brands fa-youtube"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center hover:bg-white hover:text-red-600 transition"><i class="fa-brands fa-tiktok"></i></a>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-12 bg-white">
                    <h3 class="text-2xl font-bold font-heading text-gray-800 uppercase mb-2">Kirim Pesan</h3>
                    <p class="text-gray-500 text-sm mb-8">Silakan isi formulir di bawah ini, kami akan merespons secepatnya.</p>
                    
                    <?= $msg ?>

                    <form action="" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 outline-none transition" placeholder="Nama Anda">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nomor HP</label>
                                <input type="number" name="hp" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 outline-none transition" placeholder="08xxxx">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Alamat Email</label>
                            <input type="email" name="email" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 outline-none transition" placeholder="contoh@email.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Subjek Pesan</label>
                            <select name="subjek" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 outline-none transition">
                                <option>Pertanyaan Umum</option>
                                <option>Pendaftaran Atlet</option>
                                <option>Kerjasama / Sponsorship</option>
                                <option>Pengaduan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Isi Pesan</label>
                            <textarea name="pesan" rows="4" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 outline-none transition" placeholder="Tulis pesan Anda disini..."></textarea>
                        </div>

                        <button type="submit" name="kirim_pesan" class="w-full bg-gray-900 hover:bg-red-600 text-white font-bold py-3 rounded-lg transition duration-300 shadow-lg transform hover:-translate-y-1">
                            <i class="fa-regular fa-paper-plane mr-2"></i> KIRIM PESAN SEKARANG
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 mt-12">
        <div class="bg-white p-2 rounded-xl shadow-lg border border-gray-200">
            <div class="rounded-lg overflow-hidden h-[400px] bg-gray-200 relative group">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.2709605372336!2d109.2464733740445!3d-7.412975673248665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e8f49896793%3A0xc3163459972323b!2sGOR%20Satria%20Purwokerto!5e0!3m2!1sid!2sid!4v1707234567890!5m2!1sid!2sid" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="grayscale group-hover:grayscale-0 transition duration-700"></iframe>
                
                <div class="absolute bottom-4 left-4 bg-white px-4 py-2 rounded shadow-lg text-xs font-bold text-gray-800 pointer-events-none">
                    <i class="fa-solid fa-map-pin text-red-600 mr-1"></i> Lokasi GOR Satria
                </div>
            </div>
        </div>
    </div>

    <section class="mt-16 container mx-auto px-6 max-w-4xl">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold font-heading text-gray-800 uppercase">Pertanyaan Sering Diajukan (FAQ)</h2>
            <div class="w-16 h-1 bg-red-600 mx-auto mt-4"></div>
        </div>

        <div class="space-y-4">
            <details class="group bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <summary class="flex justify-between items-center font-bold cursor-pointer list-none p-4 bg-gray-50 group-open:bg-red-50 group-open:text-red-700 transition">
                    <span>Bagaimana cara mendaftar menjadi atlet binaan KONI?</span>
                    <span class="transition group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                </summary>
                <div class="text-gray-600 text-sm p-4 leading-relaxed bg-white border-t border-gray-100">
                    Pendaftaran atlet biasanya dilakukan melalui Pengurus Cabang (Pengcabl) olahraga masing-masing. Silakan hubungi pengurus cabor terkait atau pantau pengumuman seleksi di website ini pada menu <strong>Pengumuman</strong>.
                </div>
            </details>

            <details class="group bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <summary class="flex justify-between items-center font-bold cursor-pointer list-none p-4 bg-gray-50 group-open:bg-red-50 group-open:text-red-700 transition">
                    <span>Dimana saya bisa melihat jadwal pertandingan?</span>
                    <span class="transition group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                </summary>
                <div class="text-gray-600 text-sm p-4 leading-relaxed bg-white border-t border-gray-100">
                    Jadwal pertandingan resmi (PORPROV, POPDA, Dulongmas) akan selalu kami update di halaman <strong>Berita & Kegiatan</strong> serta melalui sosial media resmi kami.
                </div>
            </details>

            <details class="group bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <summary class="flex justify-between items-center font-bold cursor-pointer list-none p-4 bg-gray-50 group-open:bg-red-50 group-open:text-red-700 transition">
                    <span>Apakah KONI Banyumas menyediakan fasilitas latihan untuk umum?</span>
                    <span class="transition group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                </summary>
                <div class="text-gray-600 text-sm p-4 leading-relaxed bg-white border-t border-gray-100">
                    Fasilitas di GOR Satria dikelola oleh Dinporabudpar. Beberapa venue bisa disewa untuk umum, namun prioritas utama tetap untuk pemusatan latihan atlet daerah (Puslatkab).
                </div>
            </details>
        </div>
    </section>

</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
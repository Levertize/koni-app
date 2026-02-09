<?php
// Pastikan path ini bener (naik satu folder ke root)
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';
?>

<main class="pt-[156px] min-h-screen bg-white">
    
    <div class="relative bg-[#0f172a] text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-red-900 to-transparent opacity-40"></div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="text-red-500 font-bold tracking-widest text-sm uppercase mb-2 block animate-fade-in-up">Profil Organisasi</span>
            <h1 class="text-4xl md:text-5xl font-heading font-bold uppercase mb-4 leading-tight">Mengenal Lebih Dekat <br><span class="text-red-600">KONI Banyumas</span></h1>
            <div class="w-24 h-1.5 bg-red-600 mx-auto rounded-full"></div>
        </div>
    </div>

    <section class="py-16 md:py-24">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute -inset-4 bg-red-100 rounded-2xl transform rotate-3 transition group-hover:rotate-6"></div>
                    <img src="..\assets\img\logo.png" class="relative rounded-xl shadow-xl w-full h-[400px] object-cover grayscale group-hover:grayscale-0 transition duration-500" alt="Gedung KONI">
                    
                    <div class="absolute bottom-6 -right-6 bg-white p-4 rounded-lg shadow-lg border-l-4 border-red-600 hidden md:block">
                        <p class="text-3xl font-bold text-gray-800">45+</p>
                        <p class="text-xs text-gray-500 font-bold uppercase">Cabang Olahraga</p>
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-heading font-bold text-gray-900 mb-6">Induk Organisasi Olahraga Prestasi di Kota Satria</h2>
                    <div class="space-y-4 text-gray-600 leading-relaxed text-justify">
                        <p>
                            <strong class="text-red-700">Komite Olahraga Nasional Indonesia (KONI)</strong> Kabupaten Banyumas adalah satu-satunya organisasi yang berwenang dan bertanggung jawab mengelola, membina, mengembangkan, dan mengkoordinasikan seluruh pelaksanaan kegiatan olahraga prestasi bagi setiap anggotanya di wilayah Kabupaten Banyumas.
                        </p>
                        <p>
                            Berdiri dengan semangat untuk memajukan potensi atlet daerah, KONI Banyumas berkomitmen mencetak juara-juara yang tidak hanya mengharumkan nama kabupaten di tingkat provinsi (PORPROV), tetapi juga di kancah nasional maupun internasional.
                        </p>
                        <p>
                            Kami percaya bahwa prestasi olahraga adalah hasil dari pembinaan yang sistematis, penerapan <em>Sport Science</em>, dan sinergi yang kuat antara atlet, pelatih, pengurus, serta pemerintah daerah.
                        </p>
                    </div>
                    
                    <div class="mt-8 flex gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm">Profesional</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm">Transparan</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="font-bold text-gray-700 text-sm">Berprestasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50 relative">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-heading font-bold text-gray-900 uppercase">Arah & Tujuan</h2>
                <div class="w-16 h-1 bg-red-600 mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border-t-4 border-red-600 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110">
                        <i class="fa-solid fa-eye text-9xl"></i>
                    </div>
                    <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Visi</h3>
                    <p class="text-gray-600 text-lg leading-relaxed italic">
                        "Terwujudnya Banyumas sebagai Barometer Pembinaan Olahraga Prestasi di Jawa Tengah yang Berkarakter dan Berdaya Saing Global pada Tahun 2029."
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border-t-4 border-blue-600 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110">
                        <i class="fa-solid fa-list-check text-9xl"></i>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Misi</h3>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-1"></i>
                            <span>Meningkatkan tata kelola organisasi keolahragaan yang profesional dan akuntabel.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-1"></i>
                            <span>Membangun sistem pembinaan atlet yang terencana, berjenjang, dan berkelanjutan berbasis IPTEK.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-1"></i>
                            <span>Meningkatkan kualitas Sumber Daya Manusia (Pelatih, Wasit, dan Tenaga Keolahragaan).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-1"></i>
                            <span>Menggalang kerjasama dengan pemerintah, swasta, dan masyarakat dalam pendanaan olahraga.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="bg-[#1e293b] rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-red-600 rounded-full opacity-20 blur-2xl"></div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600 rounded-full opacity-20 blur-2xl"></div>

                <div class="flex flex-col md:flex-row gap-12 relative z-10">
                    <div class="md:w-1/3">
                        <h3 class="text-2xl font-heading font-bold mb-4 border-l-4 border-red-500 pl-4">Tugas & Fungsi</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">
                            Sesuai dengan UU No. 3 Tahun 2005 tentang Sistem Keolahragaan Nasional, KONI memiliki peran strategis dalam membantu pemerintah daerah.
                        </p>
                        <a href="struktur.php" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full font-bold text-sm transition">
                            Lihat Struktur Organisasi
                        </a>
                    </div>
                    
                    <div class="md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 hover:bg-white/10 transition">
                            <h4 class="font-bold text-red-400 mb-2">01. Perencanaan</h4>
                            <p class="text-xs text-gray-300">Menyusun rencana program pembinaan prestasi olahraga jangka panjang dan pendek.</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 hover:bg-white/10 transition">
                            <h4 class="font-bold text-blue-400 mb-2">02. Koordinasi</h4>
                            <p class="text-xs text-gray-300">Mengoordinasikan kegiatan pengurus cabang olahraga (Pengcabor) di tingkat kabupaten.</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 hover:bg-white/10 transition">
                            <h4 class="font-bold text-yellow-400 mb-2">03. Seleksi</h4>
                            <p class="text-xs text-gray-300">Melaksanakan seleksi atlet dan pelatih untuk kontingen daerah dalam *event* olahraga.</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 hover:bg-white/10 transition">
                            <h4 class="font-bold text-green-400 mb-2">04. Audit & Evaluasi</h4>
                            <p class="text-xs text-gray-300">Melakukan monitoring dan evaluasi terhadap pelaksanaan program latihan dan penggunaan anggaran.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white mb-8">
        <div class="container mx-auto px-6">
            <h3 class="font-heading font-bold text-2xl text-gray-800 mb-6">Fasilitas & Kegiatan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=600" class="rounded-lg h-40 w-full object-cover hover:opacity-80 transition cursor-pointer" alt="Gym">
                <img src="https://images.unsplash.com/photo-1526676037777-05a232554f77?q=80&w=600" class="rounded-lg h-40 w-full object-cover hover:opacity-80 transition cursor-pointer" alt="Stadium">
                <img src="https://images.unsplash.com/photo-1565992441121-4367c2967103?q=80&w=600" class="rounded-lg h-40 w-full object-cover hover:opacity-80 transition cursor-pointer" alt="Running">
                <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=600" class="rounded-lg h-40 w-full object-cover hover:opacity-80 transition cursor-pointer" alt="Training">
            </div>
        </div>
    </section>

</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
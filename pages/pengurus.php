<?php
// Koneksi & Layouts (Path-nya naik satu folder '..')
require_once '../config/koneksi.php';
require_once '../frontend/layouts/header.php';
require_once '../frontend/layouts/navbar.php';
?>

<main class="pt-[156px] min-h-screen bg-gray-50 pb-20">
    
    <div class="bg-red-800 py-12 text-center text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        <div class="container mx-auto relative z-10 px-6">
            <h1 class="text-4xl font-heading font-bold uppercase mb-2">Daftar Pengurus</h1>
            <p class="text-red-200 text-sm font-bold tracking-widest uppercase">KONI Kabupaten Banyumas • Masa Bakti 2025 - 2029</p>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 -mt-8 relative z-20">
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                
                <div class="mb-6 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-users-rectangle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg uppercase">Susunan Personalia</h3>
                        <p class="text-xs text-gray-500">Berdasarkan SK KONI Provinsi Jawa Tengah</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-[10px] font-extrabold tracking-widest">
                                <th class="p-4 border-b-2 border-red-600 w-16 text-center">No</th>
                                <th class="p-4 border-b-2 border-red-600">Nama Lengkap</th>
                                <th class="p-4 border-b-2 border-red-600">Jabatan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                            
                            <tr class="hover:bg-red-50 transition group">
                                <td class="p-4 text-center font-bold text-red-600 group-hover:text-red-800">1</td>
                                <td class="p-4 font-bold text-gray-900 text-base">Arie Suprapto</td>
                                <td class="p-4 font-bold text-red-700 uppercase">Ketua Umum</td>
                            </tr>
                            
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">2</td>
                                <td class="p-4 font-bold">dr. H Tangguh Budi Prasetyo, S.H.</td>
                                <td class="p-4 font-semibold text-gray-600">Ketua Harian</td>
                            </tr>

                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">3</td>
                                <td class="p-4 font-bold">Happy Sunaryanto, S.H., M.H.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Umum I</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">4</td>
                                <td class="p-4 font-bold">Wijayanto Sulistiyanto, S.Pd.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Umum II</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">5</td>
                                <td class="p-4 font-bold">Dr. Aman Suyadi, MP</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Umum III</td>
                            </tr>

                            <tr class="bg-gray-50/50">
                                <td class="p-4 text-center font-bold text-red-600">6</td>
                                <td class="p-4 font-bold">Wikan Agung Winasis, S.Ip.</td>
                                <td class="p-4 font-semibold text-gray-600">Sekretaris Umum</td>
                            </tr>
                            <tr class="bg-gray-50/50">
                                <td class="p-4 text-center font-bold text-red-600">7</td>
                                <td class="p-4 font-bold">Triani Budi Lestari, S.E.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Sekretaris Umum</td>
                            </tr>

                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">8</td>
                                <td class="p-4 font-bold">Fajar Adi Nugroho, M.Pd.</td>
                                <td class="p-4 font-semibold text-gray-600">Bendahara Umum</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">9</td>
                                <td class="p-4 font-bold">Diana Kusumawati, S.E.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Bendahara Umum</td>
                            </tr>

                            <tr class="bg-gray-800 text-white">
                                <td colspan="3" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-center">
                                    Bidang Organisasi & Hukum Keolahragaan
                                </td>
                            </tr>

                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">10</td>
                                <td class="p-4 font-bold">J. Rony Endaryanto, S.T.</td>
                                <td class="p-4 font-semibold text-gray-600">Ketua Bidang</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">11</td>
                                <td class="p-4 font-bold">Susetyo, S.H., M.Hum.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Bidang I</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">12</td>
                                <td class="p-4 font-bold">Suradi A. Karim, S.H.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Bidang II</td>
                            </tr>

                            <tr class="bg-gray-800 text-white">
                                <td colspan="3" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-center">
                                    Bidang Binpres, Sport Science & Pengelolaan Data
                                </td>
                            </tr>

                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">13</td>
                                <td class="p-4 font-bold">Taufik Widjatmoko, S.Sos., M.M</td>
                                <td class="p-4 font-semibold text-gray-600">Ketua Bidang</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">14</td>
                                <td class="p-4 font-bold">AKP (Purn) R. Sunardjo</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Bidang I</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">15</td>
                                <td class="p-4 font-bold">Abdul Latif Rahman Hakim, M.Or</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Bidang II</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">16</td>
                                <td class="p-4 font-bold">Dewi Anggraeni, M.Pd.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Bidang III</td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4 text-center font-bold text-red-600">17</td>
                                <td class="p-4 font-bold">Prima Ghozali, M.Pd.</td>
                                <td class="p-4 font-semibold text-gray-600">Wakil Ketua Bidang IV</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="text-[10px] text-gray-400 italic">Data diperbarui per Februari 2026</p>
                </div>

            </div>
        </div>
    </div>
</main>

<?php require_once '../frontend/layouts/footer.php'; ?>
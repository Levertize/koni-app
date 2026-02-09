<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

if (isset($_POST['simpan'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $isi = $_POST['isi']; // Summernote output HTML
    
    // Upload Lampiran (PDF/Doc/Gambar)
    $filename = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        
        if(in_array(strtolower($ext), $allowed)) {
            $filename = time() . '_' . rand() . '.' . $ext;
            $target = '../../uploads/pengumuman/';
            
            // Buat folder kalo belum ada
            if (!is_dir($target)) mkdir($target, 0777, true);
            
            move_uploaded_file($_FILES['file']['tmp_name'], $target . $filename);
        } else {
            echo "<script>alert('Format file tidak didukung! Gunakan PDF, Word, atau Gambar.');</script>";
        }
    }

    $query = "INSERT INTO pengumuman (judul, isi_pengumuman, file_lampiran) 
              VALUES ('$judul', '$isi', '$filename')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengumuman berhasil dipublish!'); window.location='index.php';</script>";
    } else {
        $error_msg = mysqli_error($conn);
    }
}

$title = "Buat Pengumuman Baru";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-red-600 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Buat Pengumuman</h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Informasi & Edaran</p>
            </div>
        </div>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Pengumuman</label>
                        <input type="text" name="judul" required class="w-full border-0 border-b-2 border-gray-200 px-0 py-3 text-2xl font-bold focus:ring-0 focus:border-red-500 outline-none transition placeholder-gray-300" placeholder="Contoh: Hasil Seleksi...">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Pengumuman</label>
                        <textarea id="summernote" name="isi"></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Lampiran File</h3>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Dokumen</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer relative" onclick="document.getElementById('fileInput').click()">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-400">Klik untuk upload PDF/Doc/Gambar</p>
                            <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.jpg,.png" class="hidden" onchange="previewFile(event)">
                            
                            <div id="fileInfo" class="hidden mt-4 bg-red-50 text-red-600 px-3 py-2 rounded text-xs font-bold truncate"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">*Opsional. Maks 5MB.</p>
                    </div>
                    
                    <button type="submit" name="simpan" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bullhorn"></i> Terbitkan Sekarang
                    </button>
                </div>

                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 text-blue-800">
                    <h4 class="font-bold text-sm mb-2 flex items-center gap-2"><i class="fa-solid fa-circle-info"></i> Info</h4>
                    <p class="text-xs opacity-80">Gunakan file PDF agar dokumen mudah dibaca di semua perangkat tanpa mengubah format.</p>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    $('#summernote').summernote({
        placeholder: 'Tulis detail pengumuman disini...',
        tabsize: 2,
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']], // Ga perlu gambar/video di pengumuman biasanya
          ['view', ['fullscreen', 'codeview']]
        ]
    });

    function previewFile(event) {
        const input = event.target;
        const info = document.getElementById('fileInfo');
        if(input.files && input.files[0]) {
            info.textContent = input.files[0].name;
            info.classList.remove('hidden');
        }
    }
</script>

<?php require_once '../../layouts/footer.php'; ?>
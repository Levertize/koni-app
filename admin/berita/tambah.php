<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

if (isset($_POST['publish'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $kategori = htmlspecialchars($_POST['kategori']);
    $isi = $_POST['isi']; // Summernote output HTML, jangan di-escape
    
    // Upload Gambar Utama
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . rand() . '.' . $ext;
        $target = '../../uploads/berita/';
        if (!is_dir($target)) mkdir($target, 0777, true);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target . $gambar);
    }

    $query = "INSERT INTO berita (judul, slug, kategori, isi_berita, gambar, penulis) 
              VALUES ('$judul', '$slug', '$kategori', '$isi', '$gambar', '{$_SESSION['nama']}')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Artikel berhasil dipublish!'); window.location='index.php';</script>";
    } else {
        $error_msg = mysqli_error($conn);
    }
}

$title = "Tulis Berita Baru";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<!-- Load Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-400 hover:text-pink-600 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tulis Artikel Baru</h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Konten Website</p>
            </div>
        </div>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM UTAMA: EDITOR -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Artikel</label>
                        <input type="text" name="judul" required class="w-full border-0 border-b-2 border-gray-200 px-0 py-3 text-2xl font-bold focus:ring-0 focus:border-pink-500 outline-none transition placeholder-gray-300" placeholder="Ketik Judul Berita Disini...">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Konten Berita</label>
                        <textarea id="summernote" name="isi"></textarea>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: PUBLISH & META -->
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Publishing</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <select name="kategori" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-500 outline-none transition">
                            <option value="Umum">Umum</option>
                            <option value="Kompetisi">Kompetisi</option>
                            <option value="Prestasi">Prestasi</option>
                            <option value="Pengumuman">Pengumuman</option>
                            <option value="Agenda">Agenda</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Gambar Utama</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:bg-gray-50 transition cursor-pointer relative" onclick="document.getElementById('fileInput').click()">
                            <i class="fa-regular fa-image text-3xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-400">Klik untuk upload cover</p>
                            <input type="file" id="fileInput" name="gambar" accept="image/*" class="hidden" onchange="previewCover(event)">
                            <img id="coverPreview" class="hidden w-full h-32 object-cover rounded mt-2">
                        </div>
                    </div>
                    
                    <button type="submit" name="publish" class="w-full bg-pink-600 text-white font-bold py-3 rounded-xl hover:bg-pink-700 transition shadow-lg shadow-pink-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Publish Sekarang
                    </button>
                </div>

                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 text-blue-800">
                    <h4 class="font-bold text-sm mb-2 flex items-center gap-2"><i class="fa-solid fa-circle-info"></i> Tips Editor</h4>
                    <p class="text-xs opacity-80">Gunakan toolbar di atas untuk memformat teks (tebal, miring, list). Anda juga bisa menyisipkan gambar langsung ke dalam artikel.</p>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    $('#summernote').summernote({
        placeholder: 'Tulis isi berita yang menarik...',
        tabsize: 2,
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    function previewCover(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('coverPreview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php require_once '../../layouts/footer.php'; ?>
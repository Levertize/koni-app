<?php
session_start();
require_once '../../config/koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }

// Cek ID
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM berita WHERE id_berita='$id'"));

if (!$data) {
    echo "<script>alert('Berita tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Logic Update
if (isset($_POST['update'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $isi = $_POST['isi']; // HTML dari Summernote
    
    $query_img = "";
    // Cek upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . rand() . '.' . $ext;
        $target = '../../uploads/berita/';
        
        // Buat folder kalo belum ada
        if (!is_dir($target)) mkdir($target, 0777, true);
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target . $gambar)) {
            // Hapus gambar lama
            if ($data['gambar'] && file_exists($target . $data['gambar'])) {
                unlink($target . $data['gambar']);
            }
            $query_img = ", gambar='$gambar'";
        }
    }

    $sql = "UPDATE berita SET judul='$judul', kategori='$kategori', isi_berita='$isi' $query_img WHERE id_berita='$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: ".mysqli_error($conn)."');</script>";
    }
}

$title = "Edit Artikel";
require_once '../../layouts/header.php';
require_once '../../layouts/sidebar.php';
require_once '../../layouts/navbar.php';
?>

<!-- Load Summernote (Editor Teks Canggih) -->
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
                <h1 class="text-2xl font-bold text-gray-800">Edit Artikel</h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Update Konten Website</p>
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
                        <input type="text" name="judul" value="<?= $data['judul'] ?>" required class="w-full border-0 border-b-2 border-gray-200 px-0 py-3 text-2xl font-bold focus:ring-0 focus:border-pink-500 outline-none transition placeholder-gray-300">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Konten Berita</label>
                        <!-- Textarea ini bakal diubah jadi editor -->
                        <textarea id="summernote" name="isi"><?= $data['isi_berita'] ?></textarea>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: PUBLISH & META -->
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Pengaturan</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                        <select name="kategori" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-500 outline-none transition">
                            <?php 
                            $kats = ['Umum', 'Kompetisi', 'Prestasi', 'Pengumuman', 'Agenda'];
                            foreach($kats as $k) {
                                $sel = ($data['kategori'] == $k) ? 'selected' : '';
                                echo "<option value='$k' $sel>$k</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Cover Image</label>
                        
                        <!-- Preview Gambar Lama -->
                        <?php if($data['gambar']): ?>
                            <div class="relative group mb-3">
                                <img src="../../uploads/berita/<?= $data['gambar'] ?>" class="w-full h-32 object-cover rounded border">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition rounded pointer-events-none">Gambar Saat Ini</div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:bg-gray-50 transition cursor-pointer relative" onclick="document.getElementById('fileInput').click()">
                            <span class="text-xs text-gray-400">Klik untuk ganti gambar</span>
                            <input type="file" id="fileInput" name="gambar" accept="image/*" class="hidden" onchange="previewCover(event)">
                            <!-- Preview Gambar Baru -->
                            <img id="coverPreview" class="hidden w-full h-32 object-cover rounded mt-2">
                        </div>
                    </div>
                    
                    <button type="submit" name="update" class="w-full bg-pink-600 text-white font-bold py-3 rounded-xl hover:bg-pink-700 transition shadow-lg shadow-pink-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Init Summernote
    $('#summernote').summernote({
        placeholder: 'Tulis isi berita...',
        tabsize: 2,
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'picture']],
          ['view', ['fullscreen', 'codeview']]
        ]
    });

    // Preview Foto Baru
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
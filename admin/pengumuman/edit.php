<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: ../../login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengumuman WHERE id_pengumuman='$id'"));

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $isi = $_POST['isi'];
    
    $query_file = "";
    // Cek upload file baru
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        
        if(in_array(strtolower($ext), $allowed)) {
            $filename = time() . '_' . rand() . '.' . $ext;
            $target = '../../uploads/pengumuman/';
            
            if (!is_dir($target)) mkdir($target, 0777, true);
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target . $filename)) {
                // Hapus file lama
                if ($data['file_lampiran'] && file_exists($target . $data['file_lampiran'])) {
                    unlink($target . $data['file_lampiran']);
                }
                $query_file = ", file_lampiran='$filename'";
            }
        }
    }

    $sql = "UPDATE pengumuman SET judul='$judul', isi_pengumuman='$isi' $query_file WHERE id_pengumuman='$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Update berhasil!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: ".mysqli_error($conn)."');</script>";
    }
}

$title = "Edit Pengumuman";
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
                <h1 class="text-2xl font-bold text-gray-800">Edit Pengumuman</h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Perbarui Informasi</p>
            </div>
        </div>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul</label>
                        <input type="text" name="judul" value="<?= $data['judul'] ?>" required class="w-full border-0 border-b-2 border-gray-200 px-0 py-3 text-2xl font-bold focus:ring-0 focus:border-red-500 outline-none transition placeholder-gray-300">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi</label>
                        <textarea id="summernote" name="isi"><?= $data['isi_pengumuman'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Lampiran</h3>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">File Saat Ini</label>
                        
                        <?php if($data['file_lampiran']): ?>
                            <div class="bg-gray-50 border border-gray-200 p-3 rounded-lg flex items-center gap-3 mb-4">
                                <i class="fa-solid fa-file-lines text-red-500 text-xl"></i>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-gray-700 truncate"><?= $data['file_lampiran'] ?></p>
                                    <a href="../../uploads/pengumuman/<?= $data['file_lampiran'] ?>" target="_blank" class="text-[10px] text-blue-600 hover:underline">Download / Lihat</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-xs text-gray-400 italic mb-4">Tidak ada lampiran.</p>
                        <?php endif; ?>
                        
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
                            <span class="text-xs text-gray-400">Klik untuk ganti file</span>
                            <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.jpg,.png" class="hidden" onchange="previewFile(event)">
                            <div id="fileInfo" class="hidden mt-2 bg-red-50 text-red-600 px-2 py-1 rounded text-[10px] font-bold truncate"></div>
                        </div>
                    </div>
                    
                    <button type="submit" name="update" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#summernote').summernote({
        placeholder: 'Isi pengumuman...',
        tabsize: 2,
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['view', ['fullscreen', 'codeview']]
        ]
    });

    function previewFile(event) {
        const input = event.target;
        const info = document.getElementById('fileInfo');
        if(input.files && input.files[0]) {
            info.textContent = "Dipilih: " + input.files[0].name;
            info.classList.remove('hidden');
        }
    }
</script>

<?php require_once '../../layouts/footer.php'; ?>
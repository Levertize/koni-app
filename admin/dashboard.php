<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../layouts/navbar.php';

// Statistik Data
$atlet   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM atlet WHERE status_verifikasi='verified'"))['total'];
$pelatih = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelatih WHERE status_verifikasi='verified'"))['total'];
$wasit   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM wasit WHERE status_verifikasi='verified'"))['total'];

// Hitung Pending
$p_atlet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM atlet WHERE status_verifikasi='pending'"))['total'];
$p_pelatih = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelatih WHERE status_verifikasi='pending'"))['total'];
$p_wasit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM wasit WHERE status_verifikasi='pending'"))['total'];
$total_pending = $p_atlet + $p_pelatih + $p_wasit;
?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<style>
    /* Header Toolbar */
    .fc-header-toolbar { margin-bottom: 1.5rem !important; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6; }
    .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 800; color: #1f2937; letter-spacing: -0.025em; }

    /* Tombol Navigasi */
    .fc-button {
        background-color: white !important;
        border: 1px solid #e5e7eb !important;
        color: #4b5563 !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s !important;
        text-transform: capitalize !important;
    }
    .fc-button:hover { background-color: #f9fafb !important; border-color: #d1d5db !important; color: #111827 !important; }
    .fc-button-active { background-color: #eff6ff !important; border-color: #3b82f6 !important; color: #2563eb !important; box-shadow: none !important; }

    /* Grid & Event Styling */
    .fc-theme-standard td, .fc-theme-standard th { border-color: #f3f4f6 !important; }
    .fc-col-header-cell-cushion { padding: 12px 0; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #9ca3af; text-decoration: none !important; }
    .fc-daygrid-day-number { font-size: 0.875rem; font-weight: 600; color: #6b7280; padding: 8px 12px; text-decoration: none !important; }
    .fc-day-today { background-color: #f8fafc !important; }

    /* EVENT KOTAK (Block) */
    .fc-event {
        border: none !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        margin-bottom: 4px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        cursor: pointer;
    }
    .fc-daygrid-event-dot { display: none; }
</style>

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Halo, <?= $_SESSION['nama'] ?>! 👋</h2>
        <p class="text-gray-500">Pantau aktivitas KONI Banyumas hari ini.</p>
    </div>
    
    <?php if($total_pending > 0): ?>
    <a href="verifikasi/index.php" class="bg-orange-100 border border-orange-200 p-4 rounded-xl flex items-center gap-4 hover:bg-orange-200 transition group shadow-sm">
        <div class="bg-orange-500 text-white w-10 h-10 rounded-full flex items-center justify-center group-hover:scale-110 transition shadow-md">
            <i class="fa-solid fa-bell animate-pulse"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-orange-800 uppercase">Verifikasi Tertunda</p>
            <p class="text-sm text-orange-700"><b><?= $total_pending ?> data baru</b> menunggu persetujuan.</p>
        </div>
    </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Atlet</p><h3 class="text-3xl font-bold text-gray-800"><?= $atlet ?></h3></div>
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl shadow-sm"><i class="fa-solid fa-running"></i></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pelatih</p><h3 class="text-3xl font-bold text-gray-800"><?= $pelatih ?></h3></div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm"><i class="fa-solid fa-user-tie"></i></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Wasit</p><h3 class="text-3xl font-bold text-gray-800"><?= $wasit ?></h3></div>
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center text-xl shadow-sm"><i class="fa-solid fa-flag-checkered"></i></div>
        </div>
        
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-widest">Legenda Kalender</h4>
            <div class="space-y-3">
                <div class="flex items-center gap-3"><div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div><span class="text-xs font-bold text-gray-600">Latihan (Pelatih)</span></div>
                <div class="flex items-center gap-3"><div class="w-3 h-3 rounded-full bg-amber-500 shadow-sm shadow-amber-200"></div><span class="text-xs font-bold text-gray-600">Tugas Wasit</span></div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 h-full relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50 -mr-10 -mt-10"></div>
            
            <div class="flex items-center justify-between mb-6 relative z-10">
                <h3 class="font-extrabold text-gray-800 text-xl flex items-center gap-2">
                    <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-lg text-sm shadow-md shadow-blue-200"><i class="fa-regular fa-calendar-days"></i></span>
                    Jadwal Kegiatan
                </h3>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Live Sync</span>
                </div>
            </div>
            
            <div id='calendar' class="relative z-10 font-sans"></div>
        </div>
    </div>
</div>

<div id="eventModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300" id="modalContent">
        <div class="p-6 flex justify-between items-start text-white relative overflow-hidden" id="modalHeader">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold uppercase opacity-90 mb-1 tracking-widest" id="modalSubtitle">Kegiatan</p>
                <h3 class="font-extrabold text-xl leading-snug pr-4" id="modalTitle">Judul</h3>
            </div>
            <button onclick="closeModal()" class="relative z-10 bg-white/20 hover:bg-white/30 p-1.5 rounded-lg transition backdrop-blur-sm"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-6 space-y-5" id="modalBody"></div>
        <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-center">
            <button onclick="closeModal()" class="w-full px-4 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-100 transition shadow-sm hover:shadow">Tutup Jendela</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            eventDisplay: 'block', 
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listWeek' },
            locale: 'id',
            buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'List' },
            navLinks: true,
            editable: false,
            dayMaxEvents: true,
            events: 'get_events.php',
            
            // --- INI SCRIPT BARUNYA (Force Text Color) ---
            eventDidMount: function(info) {
                // Jika background kuning (Wasit), paksa text hitam
                if(info.event.backgroundColor === '#f59e0b') {
                    info.el.style.setProperty('color', '#000000', 'important');
                    
                    // Pastikan elemen child (judul & jam) juga hitam
                    let content = info.el.querySelectorAll('.fc-event-title, .fc-event-time');
                    content.forEach(el => el.style.setProperty('color', '#000000', 'important'));
                }
            },
            // ---------------------------------------------

            eventClick: function(info) {
                info.jsEvent.preventDefault();
                openModal(info.event);
            },
            
            eventTimeFormat: { 
                hour: '2-digit', minute: '2-digit', meridiem: false, omitZeroMinute: false 
            }
        });
        calendar.render();
    });

    function openModal(event) {
        const modal = document.getElementById('eventModal');
        const content = document.getElementById('modalContent');
        const header = document.getElementById('modalHeader');
        const body = document.getElementById('modalBody');
        
        document.getElementById('modalTitle').innerText = event.title;
        header.style.backgroundColor = event.backgroundColor;

        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const tgl = event.start.toLocaleDateString('id-ID', dateOptions);
        const props = event.extendedProps;

        document.getElementById('modalSubtitle').innerText = props.cabor || 'Umum';

        let htmlContent = '';

        if (props.type === 'latihan') {
            htmlContent = `
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Tanggal</label>
                        <p class="text-sm font-bold text-gray-800"><i class="fa-regular fa-calendar mr-1 text-emerald-500"></i> ${tgl}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Jam</label>
                        <p class="text-sm font-bold text-emerald-600">${props.waktu}</p>
                    </div>
                </div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pelatih</label><div class="flex items-center gap-3 mt-1.5 p-2 rounded-lg hover:bg-gray-50 transition"><div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs shadow-sm"><i class="fa-solid fa-user-tie"></i></div><p class="text-sm font-bold text-gray-800">${props.pic}</p></div></div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Latihan</label><p class="text-sm font-medium text-emerald-800 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-100 mt-1 shadow-sm">${props.target}</p></div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Deskripsi</label><div class="text-sm text-gray-600 bg-white border border-gray-100 p-4 rounded-xl mt-1 leading-relaxed shadow-inner h-24 overflow-y-auto">${props.desc}</div></div>
            `;
        } else {
            htmlContent = `
                <div class="bg-amber-50 p-3 rounded-xl border border-amber-100 flex justify-between">
                    <div><label class="text-[10px] font-bold text-amber-400 uppercase">Tanggal</label><p class="text-sm font-bold text-gray-800">${tgl}</p></div>
                    <div class="text-right"><label class="text-[10px] font-bold text-amber-400 uppercase">Jam Main</label><p class="text-sm font-bold text-amber-600">${props.waktu}</p></div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Wasit</label><div class="flex items-center gap-2 mt-1"><i class="fa-solid fa-flag-checkered text-amber-500"></i><p class="text-sm font-bold text-gray-800">${props.pic}</p></div></div>
                    <div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Lokasi</label><p class="text-sm font-bold text-gray-800 mt-1"><i class="fa-solid fa-location-dot mr-1 text-amber-500"></i> ${props.lokasi}</p></div>
                </div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase">Peran</label><p class="text-sm font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded w-fit mt-1 border border-amber-100">${props.peran}</p></div>
                <div><label class="text-[10px] font-bold text-gray-400 uppercase">Detail</label><div class="text-sm text-gray-600 bg-white border border-gray-100 p-3 rounded-lg mt-1">${props.desc}</div></div>
            `;
        }

        body.innerHTML = htmlContent;
        modal.classList.remove('hidden');
        setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('eventModal');
        const content = document.getElementById('modalContent');
        content.classList.remove('scale-100'); content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 200);
    }
    window.onclick = function(event) { if (event.target == document.getElementById('eventModal')) closeModal(); }
</script>

<?php require_once '../layouts/footer.php'; ?>
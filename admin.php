<?php
// Load Booth Settings & Branding
$settingsFile = __DIR__ . '/uploads/booth_settings.json';
$settings = [
    'title' => 'Berbuka Bersama',
    'subtitle' => 'Mahaghora Group',
    'titleColor' => '#D48C12',
    'subtitleColor' => '#D48C12',
    'bgColor' => '#2D5A27',
    'bgImage' => '',
    'primaryColor' => '#D48C12',
    'secondaryColor' => '#63392E',
    'goldColor' => '#D4AF37',
];

if (file_exists($settingsFile)) {
    $saved = json_decode(file_get_contents($settingsFile), true);
    if (is_array($saved)) {
        $settings = array_merge($settings, $saved);
    }
}

$boothTitle = !empty($settings['title']) ? $settings['title'] : 'Photo Booth';
$boothSubtitle = !empty($settings['subtitle']) ? $settings['subtitle'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= htmlspecialchars($boothTitle) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              ramadan: {
                primary: "#D4AF37",   
                secondary: "#63392E", 
                cream: "#FFFDF5",     
                gold: "#C9A227",      
                green: "#2D5A27",     
                lightGreen: "#2D6A4F",
                dark: "#0F172A",
              },
            },
          },
        },
      }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --booth-bg: <?= htmlspecialchars($settings['bgColor']) ?>;
            --booth-primary: <?= htmlspecialchars($settings['primaryColor']) ?>;
            --booth-secondary: <?= htmlspecialchars($settings['secondaryColor']) ?>;
            --booth-gold: <?= htmlspecialchars($settings['goldColor']) ?>;
            --booth-cream: #FFFDF5;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--booth-bg);
            <?php if (!empty($settings['bgImage'])): ?>
            background-image: url('<?= htmlspecialchars($settings['bgImage']) ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            <?php else: ?>
            background-image:
                radial-gradient(circle at 10% 15%, rgba(212, 140, 18, 0.18) 0%, transparent 35%),
                radial-gradient(circle at 90% 85%, rgba(212, 140, 18, 0.18) 0%, transparent 35%);
            <?php endif; ?>
            color: #1E293B;
        }

        .font-playfair { font-family: 'Playfair Display', serif; }
        
        .glass-card {
            background: rgba(255, 253, 245, 0.94);
            backdrop-filter: blur(20px);
            border: 1.5px solid rgba(212, 140, 18, 0.25);
            box-shadow: 0 20px 40px -12px rgba(45, 90, 39, 0.25);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, var(--booth-primary) 0%, #B4730A 100%);
            color: #FFFDF5;
            box-shadow: 0 10px 25px -5px rgba(212, 140, 18, 0.4);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
        }

        .tab-btn:not(.active) {
            background-color: rgba(255, 253, 245, 0.9);
            color: var(--booth-secondary);
            border: 1.5px solid rgba(212, 140, 18, 0.25);
        }

        .tab-btn:not(.active):hover {
            background-color: #FFFFFF;
            color: #1E293B;
            transform: translateY(-1px);
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.6; }
        }
        .animate-pulse-dot {
            animation: pulse-dot 1.5s infinite ease-in-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--booth-bg); }
        ::-webkit-scrollbar-thumb { background: var(--booth-gold); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--booth-primary); }
    </style>
</head>
<body class="p-4 md:p-8 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Top Navbar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 glass-card p-6 rounded-3xl shadow-xl">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-3 py-1 bg-amber-400/20 text-amber-900 border border-amber-400/40 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                        ✨ <?= htmlspecialchars($boothTitle) ?> Master
                    </span>
                    <span class="text-xs text-amber-800/80 font-semibold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sistem Aktif
                    </span>
                </div>
                <h1 class="text-3xl font-playfair font-bold text-amber-950">Dashboard Operator</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="history.php" class="flex items-center gap-2 text-sm bg-stone-800 hover:bg-stone-700 text-stone-100 px-5 py-2.5 rounded-full font-bold transition-all shadow border border-stone-600 hover:scale-105 active:scale-95">
                    <span>🎞️</span> Riwayat Sesi Foto
                </a>
                <a href="index.html" class="flex items-center gap-2 text-sm bg-amber-400 hover:bg-amber-300 text-slate-900 px-5 py-2.5 rounded-full font-bold transition-all shadow hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Ke Kamera Booth</span>
                </a>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <!-- Tab: Print Queue -->
                <button onclick="switchTab('queue')" id="tab-btn-queue" class="tab-btn active px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Antrean Print Peserta</span>
                    <span id="queue-badge-count" class="px-2.5 py-0.5 bg-amber-400 text-slate-900 text-xs font-black rounded-full shadow-sm">0</span>
                </button>

                <!-- Tab: Template Manager -->
                <button onclick="switchTab('templates')" id="tab-btn-templates" class="tab-btn px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Pengaturan Template</span>
                </button>

                <!-- Tab: Branding & Appearance -->
                <button onclick="switchTab('branding')" id="tab-btn-branding" class="tab-btn px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4 4 0 014-4c.734 0 1.41.203 2 .556V5a2 2 0 012-2h6a2 2 0 012 2v6.556A4.001 4.001 0 0121 17a4 4 0 01-4 4 4 4 0 01-4-4c0-.734.203-1.41.556-2H10.444A3.996 3.996 0 017 21z" />
                    </svg>
                    <span>🎨 Tampilan &amp; Branding Booth</span>
                </button>
            </div>

            <!-- Queue Quick Filter & Sound Toggle (visible on queue tab) -->
            <div id="queue-controls" class="flex flex-wrap items-center gap-3">
                <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 bg-white/90 border border-amber-600/30 px-3.5 py-2 rounded-xl cursor-pointer hover:bg-white shadow-sm">
                    <input type="checkbox" id="sound-toggle" checked class="w-4 h-4 accent-amber-600 rounded">
                    <span>🔔 Notifikasi Suara</span>
                </label>

                <select id="status-filter" onchange="fetchQueue()" class="text-xs font-bold bg-white/90 border border-amber-600/30 px-3.5 py-2 rounded-xl text-stone-800 outline-none focus:border-amber-500 shadow-sm cursor-pointer">
                    <option value="all" selected>📋 Semua Status (Item Tetap Tampil)</option>
                    <option value="pending">⏳ Menunggu Cetak (Pending)</option>
                    <option value="printing">🖨️ Sedang Dicetak</option>
                    <option value="completed">✅ Selesai Dicetak</option>
                </select>

                <button onclick="clearCompletedQueue()" class="text-xs font-bold bg-stone-100/90 hover:bg-red-50 hover:text-red-700 text-stone-700 border border-stone-300 px-3.5 py-2 rounded-xl transition-all shadow-sm">
                    Bersihkan Riwayat Selesai
                </button>
            </div>
        </div>

        <!-- ================= TAB 1: PRINT QUEUE ================= -->
        <section id="tab-content-queue" class="space-y-6">
            <!-- Live Status Banner -->
            <div class="bg-emerald-950 text-emerald-100 p-5 rounded-3xl shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-emerald-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-800/80 flex items-center justify-center text-xl shrink-0">
                        🖨️
                    </div>
                    <div>
                        <h2 class="font-bold text-base text-white">Live Monitor Antrean Cetak</h2>
                        <p class="text-xs text-emerald-300">Setiap kali peserta menekan tombol "Cetak" di HP mereka setelah scan QR, foto akan langsung muncul di sini.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <button onclick="fetchQueue()" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin-hover" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Refresh Antrean</span>
                    </button>
                </div>
            </div>

            <!-- Print Cards Grid -->
            <div id="queue-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Dynamically populated by JS -->
                <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
                    <p class="text-sm font-semibold">Memuat data antrean...</p>
                </div>
            </div>
        </section>

        <!-- ================= TAB 2: TEMPLATES MANAGER ================= -->
        <section id="tab-content-templates" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Form & A5 Guide -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- A5 Template Design Guide & Download Center -->
                    <section class="bg-gradient-to-br from-[#1B4332] to-[#2D6A4F] text-white p-6 md:p-8 rounded-3xl shadow-xl border-2 border-amber-400/40 relative overflow-hidden space-y-6">
                        <!-- Background glow effect -->
                        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-4 border-b border-white/20">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-400 text-slate-900 rounded-full text-xs font-black uppercase tracking-wider mb-2 shadow">
                                    📐 Pusat Download Template Desain A5 (300 DPI)
                                </div>
                                <h2 class="text-2xl md:text-3xl font-playfair font-bold text-amber-300">Download Blueprint &amp; Template Transparan</h2>
                                <p class="text-sm text-emerald-100 mt-1">Gunakan panduan ini untuk membuat bingkai / frame di Photoshop, Canva, Figma, atau CorelDraw.</p>
                            </div>
                        </div>

                        <!-- SECTION 1: 6 FOTO GRID A5 -->
                        <div class="bg-black/30 backdrop-blur-sm p-5 rounded-2xl border border-amber-400/30 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-amber-300 flex items-center gap-2">
                                    <span>📸</span> Format 6 Foto Grid (2 Kolom x 3 Baris) — Kertas A5
                                </h3>
                                <span class="text-xs bg-amber-400/20 text-amber-300 px-2.5 py-0.5 rounded-full font-bold border border-amber-400/30">Layout Baru</span>
                            </div>
                            
                            <!-- 6 Foto Specs Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <span class="text-[11px] text-amber-300 font-bold block">📄 Ukuran Canvas A5</span>
                                    <p class="text-base font-bold text-white">1748 x 2480 px</p>
                                    <p class="text-[10px] text-slate-300">300 DPI Standar Cetak</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <span class="text-[11px] text-amber-300 font-bold block">🖼️ Ukuran 6 Slot Foto</span>
                                    <p class="text-base font-bold text-white">760 x 372 px</p>
                                    <p class="text-[10px] text-slate-300">Rasio Foto 920:450 (2:1)</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <span class="text-[11px] text-amber-300 font-bold block">📐 Margin &amp; Jarak (Gap)</span>
                                    <p class="text-base font-bold text-white">Margin: 94px | Gap: 40px</p>
                                    <p class="text-[10px] text-slate-300">Header &amp; Footer: ~637 px</p>
                                </div>
                            </div>

                            <!-- Download Buttons 6 Photos -->
                            <div class="flex flex-wrap gap-3 pt-1">
                                <a href="download_guide.php?type=blueprint_6photo" download="Panduan_Desain_Template_A5_6Foto_1748x2480px.png" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold py-3 px-4 rounded-xl transition-all shadow-md hover:scale-[1.02] active:scale-95 text-xs md:text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>📥 Blueprint Panduan 6 Foto (PNG)</span>
                                </a>
                                <a href="download_guide.php?type=transparent_6photo" download="Template_A5_Transparan_6Foto_1748x2480px.png" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 text-white font-bold py-3 px-4 rounded-xl border border-white/40 transition-all shadow-md hover:scale-[1.02] active:scale-95 text-xs md:text-sm backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>📥 Template Transparan 6 Foto (PNG)</span>
                                </a>
                            </div>
                        </div>

                        <!-- SECTION 2: 3 FOTO STRIP A5 -->
                        <div class="bg-black/30 backdrop-blur-sm p-5 rounded-2xl border border-white/10 space-y-4">
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <span>🎞️</span> Format 3 Foto Strip Vertikal — Kertas A5
                            </h3>

                            <!-- 3 Foto Specs Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <span class="text-[11px] text-amber-300 font-bold block">📄 Ukuran Canvas A5</span>
                                    <p class="text-base font-bold text-white">1748 x 2480 px</p>
                                    <p class="text-[10px] text-slate-300">300 DPI Standar</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <span class="text-[11px] text-amber-300 font-bold block">🖼️ Ukuran 3 Slot Foto</span>
                                    <p class="text-base font-bold text-white">1540 x 650 px</p>
                                    <p class="text-[10px] text-slate-300">Tiap Slot (1, 2, &amp; 3)</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <span class="text-[11px] text-amber-300 font-bold block">📐 Margin &amp; Jarak (Gap)</span>
                                    <p class="text-base font-bold text-white">Kiri/Kanan: 104 px</p>
                                    <p class="text-[10px] text-slate-300">Header: 180px | Gap: 60px</p>
                                </div>
                            </div>

                            <!-- Action Download Buttons 3 Photos -->
                            <div class="flex flex-wrap gap-3 pt-1">
                                <a href="download_guide.php?type=blueprint" download="Panduan_Desain_Template_A5_3Foto_1748x2480px.png" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold py-3 px-4 rounded-xl transition-all shadow-md hover:scale-[1.02] active:scale-95 text-xs md:text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>📥 Blueprint Panduan 3 Foto (PNG)</span>
                                </a>
                                <a href="download_guide.php?type=transparent" download="Template_A5_Transparan_3Foto_1748x2480px.png" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 text-white font-bold py-3 px-4 rounded-xl border border-white/40 transition-all shadow-md hover:scale-[1.02] active:scale-95 text-xs md:text-sm backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>📥 Template Transparan 3 Foto (PNG)</span>
                                </a>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold mb-4 text-[#2D6A4F]">Tambah Template Baru</h2>
                        <form id="upload-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b pb-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Nama Template</label>
                                    <input type="text" name="name" required placeholder="Contoh: Ramadan Kareem A5" class="w-full px-4 py-2 rounded-xl border border-gray-200 outline-none focus:border-emerald-600 font-semibold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Ukuran / Format Template</label>
                                    <select name="sizeType" id="form-size-type" onchange="updateLivePreview()" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-emerald-600 font-bold text-slate-800 bg-white cursor-pointer">
                                        <option value="a5_6grid">📸 Kertas A5 (1748 x 2480 px - 6 Foto Grid 2x3)</option>
                                        <option value="a5" selected>📄 Kertas A5 (1748 x 2480 px - 3 Foto Strip)</option>
                                        <option value="strip">📱 Photostrip 9:16 (1080 x 1920 px - 3 Foto)</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 pt-6">
                                    <input type="checkbox" name="overlayMode" id="form-overlay" onchange="updateLivePreview()" class="w-5 h-5 accent-[#2D6A4F] cursor-pointer">
                                    <label for="form-overlay" class="text-sm font-medium cursor-pointer">Tema Luar sebagai Overlay</label>
                                </div>
                            </div>

                            <!-- DYNAMIC PHOTO SLOTS CONTAINER (TEMPAT FOTO PERSEGI PANJANG) -->
                            <div class="space-y-4 bg-amber-50/40 p-5 rounded-3xl border border-amber-200/80 shadow-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-amber-200/60">
                                    <div>
                                        <h3 class="font-bold text-sm text-[#1B4332] flex items-center gap-2">
                                            <span>📸</span> Daftar Tempat / Slot Foto (Wajib Persegi Panjang)
                                        </h3>
                                        <p class="text-[11px] text-amber-900/70">Bisa ditambah 1, 2, 3, 4, 6, atau lebih banyak slot foto. Tiap tempat foto <strong>wajib berbentuk persegi panjang</strong> (panjang &ne; lebar) dan bisa digeser/diatur langsung di monitor!</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button type="button" onclick="addNewPhotoSlot()" class="py-2 px-3.5 bg-amber-500 hover:bg-amber-400 text-stone-950 font-black rounded-xl text-xs flex items-center gap-1.5 transition-all shadow-sm active:scale-95 cursor-pointer">
                                            <span class="text-base leading-none">+</span> Tambah Tempat Foto
                                        </button>
                                    </div>
                                </div>

                                <!-- Quick Presets Toolbar for Photo Slots -->
                                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                                    <span class="text-[10px] font-bold text-amber-900/80 uppercase tracking-wider shrink-0 mr-1">📐 Preset Cepat:</span>
                                    <button type="button" onclick="applySlotPreset('a5_6grid')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-300 transition-all shrink-0 active:scale-95 text-[11px]">
                                        📸 6 Foto Grid (2x3)
                                    </button>
                                    <button type="button" onclick="applySlotPreset('a5_3strip')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-300 transition-all shrink-0 active:scale-95 text-[11px]">
                                        📄 3 Foto Strip
                                    </button>
                                    <button type="button" onclick="applySlotPreset('a5_4grid')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-300 transition-all shrink-0 active:scale-95 text-[11px]">
                                        🖼️ 4 Foto Grid (2x2)
                                    </button>
                                    <button type="button" onclick="applySlotPreset('a5_2landscape')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-300 transition-all shrink-0 active:scale-95 text-[11px]">
                                        🎞️ 2 Foto Landscape
                                    </button>
                                    <button type="button" onclick="applySlotPreset('a5_1single')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-300 transition-all shrink-0 active:scale-95 text-[11px]">
                                        🌟 1 Foto Besar
                                    </button>
                                </div>

                                <div id="dynamic-slots-container" class="space-y-4">
                                    <!-- Dynamic Photo Slots rendered dynamically by JS -->
                                </div>
                            </div>

                            <!-- DYNAMIC MULTI-ITEM ORNAMENTS CONTAINER -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                    <div>
                                        <h3 class="font-bold text-sm text-[#2D6A4F] flex items-center gap-2">
                                            <span>✨</span> Daftar Item / Stiker / Hiasan Template
                                        </h3>
                                        <p class="text-[11px] text-gray-400">Tambah stiker atau hiasan sebanyak yang Anda inginkan (bisa upload lebih dari 1). Semua item bisa digeser langsung di monitor!</p>
                                    </div>
                                    <button type="button" onclick="addNewTemplateItem()" class="py-2 px-3.5 bg-emerald-100 text-emerald-900 hover:bg-emerald-200 border border-emerald-300 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm active:scale-95 cursor-pointer">
                                        <span class="text-base font-black">+</span> Tambah Item Baru
                                    </button>
                                </div>

                                <div id="dynamic-items-container" class="space-y-4">
                                    <!-- Dynamic items rendered dynamically by JS -->
                                </div>
                            </div>

                            <div class="pt-4 border-t">
                                <label id="outer-label" class="block text-sm font-semibold mb-2">Tema Luar Utama (A5: 1748x2480 px) <span class="text-red-500">*</span></label>
                                <input type="file" name="outerImage" id="outer-input" accept="image/*" class="w-full text-sm">
                            </div>

                            <button type="submit" class="w-full px-8 py-4 bg-[#2D6A4F] text-white rounded-2xl font-bold hover:scale-[1.01] transition-all shadow-lg active:scale-95 cursor-pointer">
                                Upload Template & Save Layout
                            </button>
                        </form>
                    </section>

                    <!-- Template List -->
                    <section>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                            <h2 class="text-xl font-bold text-[#2D6A4F] flex items-center gap-2">
                                <span>📁</span> Daftar Template
                            </h2>
                            <a href="auto_compress.php" target="_blank" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-xl text-xs font-black shadow-md flex items-center gap-1.5 transition-all active:scale-95 cursor-pointer">
                                <span>⚡</span> Kompres & Ringankan Semua Template di Server
                            </a>
                        </div>
                        <div id="template-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <p class="text-gray-500 italic">Memuat daftar template...</p>
                        </div>
                    </section>
                </div>

                <!-- Right: Preview (Sticky) -->
                <div class="lg:sticky lg:top-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-[#2D6A4F] flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Live Preview Monitor
                        </h2>
                        <span class="text-[11px] font-bold px-2.5 py-1 bg-amber-500/20 text-amber-900 border border-amber-400/50 rounded-full flex items-center gap-1 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span> Drag & Drop Aktif
                        </span>
                    </div>

                    <!-- Interactive Item Selector Badges -->
                    <div id="preview-item-buttons" class="flex items-center gap-2 overflow-x-auto pb-1">
                        <!-- Rendered by JS -->
                    </div>

                    <!-- Multi-Selection & Productivity Quick Toolbar (Undo/Redo/Copy/Paste/Nudge) -->
                    <div class="space-y-1.5 p-2.5 bg-amber-500/10 rounded-2xl border border-amber-300/40 text-xs shadow-sm">
                        <!-- Row 1: Undo, Redo, Copy, Paste, Duplicate, Delete -->
                        <div class="flex flex-wrap items-center justify-between gap-1.5 pb-1.5 border-b border-amber-300/30">
                            <div class="flex items-center gap-1">
                                <button type="button" id="btn-undo" onclick="undo()" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] transition-all active:scale-95 flex items-center gap-1" title="Undo / Kembalikan (Ctrl+Z)">
                                    <span>↩️</span> Undo
                                </button>
                                <button type="button" id="btn-redo" onclick="redo()" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] transition-all active:scale-95 flex items-center gap-1" title="Redo / Ulangi (Ctrl+Y)">
                                    <span>↪️</span> Redo
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="copySelected()" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] transition-all active:scale-95" title="Copy Objek Terpilih (Ctrl+C)">
                                    📋 Copy
                                </button>
                                <button type="button" onclick="pasteCopied()" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] transition-all active:scale-95" title="Paste / Tempel Objek (Ctrl+V)">
                                    📥 Paste
                                </button>
                                <button type="button" onclick="duplicateSelected()" class="px-2 py-1 bg-amber-400 hover:bg-amber-300 text-stone-950 font-black rounded-lg border border-amber-500 shadow-sm text-[11px] transition-all active:scale-95" title="Duplikat Cepat (Ctrl+D)">
                                    📑 Duplikat
                                </button>
                                <button type="button" onclick="deleteSelected()" class="px-2 py-1 bg-rose-100 hover:bg-rose-200 text-rose-800 font-bold rounded-lg border border-rose-300 shadow-sm text-[11px] transition-all active:scale-95" title="Hapus Objek Terpilih (Delete)">
                                    🗑️
                                </button>
                            </div>
                        </div>

                        <!-- Row 2: Select All, Clear, & Nudge Arrows -->
                        <div class="flex flex-wrap items-center justify-between gap-1.5 pt-0.5">
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="selectAllTargets()" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-900 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] transition-all active:scale-95 flex items-center gap-1" title="Pilih Semua (Ctrl+A)">
                                    <span>🎯</span> Semua
                                </button>
                                <button type="button" onclick="clearSelection()" class="px-2 py-1 bg-stone-200 hover:bg-stone-300 text-stone-700 font-bold rounded-lg text-[11px] transition-all" title="Batal Seleksi (Esc)">
                                    🧹 Batal
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] font-bold text-amber-900/80 mr-0.5">Geser:</span>
                                <button type="button" onclick="nudgeSelectedTargets(0, -30)" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] active:scale-95" title="Geser ke Atas (↑)">⬆️</button>
                                <button type="button" onclick="nudgeSelectedTargets(0, 30)" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] active:scale-95" title="Geser ke Bawah (↓)">⬇️</button>
                                <button type="button" onclick="nudgeSelectedTargets(-30, 0)" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] active:scale-95" title="Geser ke Kiri (←)">⬅️</button>
                                <button type="button" onclick="nudgeSelectedTargets(30, 0)" class="px-2 py-1 bg-white hover:bg-amber-100 text-stone-800 font-bold rounded-lg border border-amber-300 shadow-sm text-[11px] active:scale-95" title="Geser ke Kanan (→)">➡️</button>
                            </div>
                        </div>
                    </div>

                    <div id="canvas-dropzone-container" class="relative bg-white rounded-[3rem] p-4 shadow-2xl border-8 border-gray-100 overflow-hidden aspect-[9/16] w-full max-w-[350px] mx-auto select-none touch-none transition-all">
                        <canvas id="preview-canvas" width="1080" height="1920" class="w-full h-full object-contain rounded-[2rem] bg-[#FFFDF5] cursor-grab"></canvas>
                        <div class="absolute inset-0 pointer-events-none border-[12px] border-white/50 rounded-[2.5rem]"></div>
                        
                        <!-- Drag and Drop Overlay Indicator -->
                        <div id="canvas-drop-overlay" class="absolute inset-3 bg-emerald-950/90 backdrop-blur-md rounded-[2.2rem] border-4 border-dashed border-emerald-400 flex flex-col items-center justify-center text-center p-6 transition-all duration-200 opacity-0 pointer-events-none z-30 scale-95 shadow-2xl">
                            <div class="w-16 h-16 rounded-3xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/50 flex items-center justify-center text-3xl mb-3 animate-bounce shadow-lg">
                                📥
                            </div>
                            <h4 class="text-white font-extrabold text-sm md:text-base tracking-wide">Lepaskan Gambar di Sini</h4>
                            <p class="text-emerald-200 text-xs mt-1 max-w-[220px]">Item / Stiker baru akan otomatis diletakkan tepat di posisi kursor drop!</p>
                            <span class="mt-3 px-3 py-1 bg-emerald-400 text-slate-950 font-black text-[10px] rounded-full uppercase tracking-wider shadow">
                                ✨ Rasio Asli Terjaga
                            </span>
                        </div>
                    </div>

                    <div class="bg-[#1B4332] text-[#FFFDF5] p-4 rounded-2xl text-xs space-y-1.5 shadow-md">
                        <div class="flex items-center justify-between border-b border-white/20 pb-1.5 mb-1.5 font-bold">
                            <span>⌨️ SHORTCUT DESAIN LENGKAP</span>
                            <span id="drag-coord-status" class="font-mono text-[11px] text-amber-300">Siap</span>
                        </div>
                        <p>• <strong>📥 Drag &amp; Drop File</strong> : Tarik file gambar (PNG/JPG/WEBP) dari laptop/PC langsung ke monitor untuk tambah item otomatis!</p>
                        <p>• <strong>Ctrl + Z</strong> / <strong>Ctrl + Y</strong> : Undo &amp; Redo riwayat perubahan.</p>
                        <p>• <strong>Ctrl + C</strong> / <strong>Ctrl + V</strong> : Salin (Copy) &amp; Tempel (Paste) objek terpilih.</p>
                        <p>• <strong>Ctrl + D</strong> : Duplikat cepat objek terpilih di tempat.</p>
                        <p>• <strong>Delete / Backspace</strong> : Hapus objek yang sedang dipilih.</p>
                        <p>• <strong>Kotak Seleksi (Marquee)</strong> : Klik area kosong canvas &amp; seret kursor untuk seleksi banyak.</p>
                        <p>• <strong>Tombol Panah (↑ ↓ ← →)</strong> : Geser presisi 10px (atau 50px jika tahan Shift).</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= TAB 3: BRANDING & APPEARANCE ================= -->
        <section id="tab-content-branding" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Form Controls (2 Columns on large screen) -->
                <div class="lg:col-span-2 space-y-6">
                    <form id="branding-form" class="space-y-6">
                        
                        <!-- 1. Header Titles & Text Colors -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg">
                                    ✍️
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-slate-800">Judul &amp; Teks di Atas Kamera</h3>
                                    <p class="text-xs text-slate-400">Atur tulisan nama acara/perusahaan dan warnanya di atas live view kamera.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Judul Utama -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Judul Utama (Baris 1)</label>
                                    <input type="text" id="brand-title" name="title" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm outline-none focus:border-emerald-600 font-semibold text-slate-800" placeholder="Berbuka Bersama">
                                    <div class="flex items-center gap-2 mt-2">
                                        <input type="color" id="brand-title-color" name="titleColor" value="#D48C12" class="w-8 h-8 rounded-lg cursor-pointer border-0 bg-transparent">
                                        <span class="text-xs text-slate-500 font-mono" id="brand-title-color-val">#D48C12</span>
                                        <span class="text-[11px] text-slate-400">Warna Judul</span>
                                    </div>
                                </div>

                                <!-- Sub Judul -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Sub Judul (Baris 2)</label>
                                    <input type="text" id="brand-subtitle" name="subtitle" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm outline-none focus:border-emerald-600 font-semibold text-slate-800" placeholder="Mahaghora Group">
                                    <div class="flex items-center gap-2 mt-2">
                                        <input type="color" id="brand-subtitle-color" name="subtitleColor" value="#D48C12" class="w-8 h-8 rounded-lg cursor-pointer border-0 bg-transparent">
                                        <span class="text-xs text-slate-500 font-mono" id="brand-subtitle-color-val">#D48C12</span>
                                        <span class="text-[11px] text-slate-400">Warna Sub Judul</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Background Layar Photobooth -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">
                                    🖼️
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-slate-800">Background Belakang Layar Booth</h3>
                                    <p class="text-xs text-slate-400">Pilih warna dasar atau upload gambar background full screen sendiri.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                                <!-- Warna Dasar Background -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Warna Dasar Latar Belakang</label>
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                        <input type="color" id="brand-bg-color" name="bgColor" value="#2D5A27" class="w-10 h-10 rounded-xl cursor-pointer border-0 bg-transparent shadow-sm">
                                        <div>
                                            <span class="text-xs font-bold font-mono text-slate-700 block" id="brand-bg-color-val">#2D5A27</span>
                                            <span class="text-[11px] text-slate-400">Warna Latar Utama</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Background Gambar Sendiri -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Gambar Background (Opsional)</label>
                                    <input type="file" id="brand-bg-image" name="bgImage" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 mb-2">
                                    <div id="bg-image-preview-box" class="hidden flex items-center justify-between p-2.5 bg-emerald-50/50 rounded-xl border border-emerald-200/60 text-xs">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <img id="bg-thumb" src="" class="w-10 h-10 object-cover rounded-lg border border-emerald-200">
                                            <span class="text-emerald-900 font-semibold truncate text-[11px]" id="bg-filename">Background Aktif</span>
                                        </div>
                                        <button type="button" onclick="removeBackgroundImage()" class="px-2.5 py-1 text-red-600 hover:bg-red-100 rounded-lg text-xs font-bold transition-all">
                                            Hapus Gambar
                                        </button>
                                    </div>
                                    <input type="hidden" name="removeBgImage" id="remove-bg-image-input" value="false">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Warna Tema & Tombol -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                                <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-lg">
                                    🎨
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-slate-800">Warna Tombol &amp; Elemen Tema</h3>
                                    <p class="text-xs text-slate-400">Atur skema warna tombol aksi dan aksen.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-2">Tombol Utama (Ambil Foto)</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" id="brand-primary-color" name="primaryColor" value="#D48C12" class="w-8 h-8 rounded-lg cursor-pointer border-0 bg-transparent">
                                        <span class="text-xs font-mono text-slate-600 font-bold" id="brand-primary-color-val">#D48C12</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-2">Warna Emas / Border</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" id="brand-gold-color" name="goldColor" value="#D4AF37" class="w-8 h-8 rounded-lg cursor-pointer border-0 bg-transparent">
                                        <span class="text-xs font-mono text-slate-600 font-bold" id="brand-gold-color-val">#D4AF37</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-2">Warna Teks &amp; Kartu</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" id="brand-secondary-color" name="secondaryColor" value="#63392E" class="w-8 h-8 rounded-lg cursor-pointer border-0 bg-transparent">
                                        <span class="text-xs font-mono text-slate-600 font-bold" id="brand-secondary-color-val">#63392E</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Gambar Hiasan & Ornamen Samping Layar -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-yellow-100 text-yellow-800 flex items-center justify-center font-bold text-lg">
                                        ✨
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-base text-slate-800">Ornamen &amp; Gambar Hiasan Samping Layar</h3>
                                        <p class="text-xs text-slate-400">Ganti gambar lentera, ketupat, atau stiker samping di 4 sudut layar.</p>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200 cursor-pointer">
                                    <input type="checkbox" id="brand-show-deco" name="showDeco" checked class="w-4 h-4 accent-emerald-600 rounded">
                                    <span>Aktifkan Hiasan</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Kiri Atas -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700">🏮 Hiasan Kiri Atas</span>
                                        <button type="button" onclick="resetDecoSlot('decoTopLeft')" class="text-[10px] text-slate-400 hover:text-red-600 font-bold">Reset</button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <img id="preview-deco-tl" src="./gambar/lampu.webp" class="w-12 h-12 object-contain bg-white p-1 rounded-xl border border-gray-200 shrink-0">
                                        <div class="flex-1">
                                            <input type="file" id="input-deco-tl" name="decoTopLeft" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-200 file:text-slate-700">
                                        </div>
                                    </div>
                                    <input type="hidden" name="reset_decoTopLeft" id="reset-decoTopLeft" value="false">
                                </div>

                                <!-- Kanan Atas -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700">🏮 Hiasan Kanan Atas</span>
                                        <button type="button" onclick="resetDecoSlot('decoTopRight')" class="text-[10px] text-slate-400 hover:text-red-600 font-bold">Reset</button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <img id="preview-deco-tr" src="./gambar/lampu.webp" class="w-12 h-12 object-contain bg-white p-1 rounded-xl border border-gray-200 shrink-0">
                                        <div class="flex-1">
                                            <input type="file" id="input-deco-tr" name="decoTopRight" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-200 file:text-slate-700">
                                        </div>
                                    </div>
                                    <input type="hidden" name="reset_decoTopRight" id="reset-decoTopRight" value="false">
                                </div>

                                <!-- Kiri Bawah -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700">✨ Hiasan Kiri Bawah</span>
                                        <button type="button" onclick="resetDecoSlot('decoBottomLeft')" class="text-[10px] text-slate-400 hover:text-red-600 font-bold">Reset</button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <img id="preview-deco-bl" src="./gambar/ketupat.webp" class="w-12 h-12 object-contain bg-white p-1 rounded-xl border border-gray-200 shrink-0">
                                        <div class="flex-1">
                                            <input type="file" id="input-deco-bl" name="decoBottomLeft" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-200 file:text-slate-700">
                                        </div>
                                    </div>
                                    <input type="hidden" name="reset_decoBottomLeft" id="reset-decoBottomLeft" value="false">
                                </div>

                                <!-- Kanan Bawah -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700">✨ Hiasan Kanan Bawah / Tengah</span>
                                        <button type="button" onclick="resetDecoSlot('decoBottomRight')" class="text-[10px] text-slate-400 hover:text-red-600 font-bold">Reset</button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <img id="preview-deco-br" src="./gambar/ketupat.webp" class="w-12 h-12 object-contain bg-white p-1 rounded-xl border border-gray-200 shrink-0">
                                        <div class="flex-1">
                                            <input type="file" id="input-deco-br" name="decoBottomRight" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-200 file:text-slate-700">
                                        </div>
                                    </div>
                                    <input type="hidden" name="reset_decoBottomRight" id="reset-decoBottomRight" value="false">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-2">
                            <button type="submit" id="btn-save-branding" class="w-full sm:flex-1 py-4 px-8 bg-[#1B4332] hover:bg-[#2D6A4F] text-white rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Pengaturan Tampilan</span>
                            </button>
                            <button type="button" onclick="resetAllBranding()" class="w-full sm:w-auto py-4 px-6 bg-red-50 text-red-600 hover:bg-red-100 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Reset ke Standar</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Live Mini Preview Mockup (Sticky) -->
                <div class="lg:sticky lg:top-8 space-y-4">
                    <h2 class="text-lg font-bold text-[#1B4332] flex items-center gap-2">
                        <span>👁️</span> Pratinjau Layar Booth
                    </h2>
                    
                    <!-- Screen Mockup Container -->
                    <div id="booth-screen-mockup" class="relative rounded-[2.5rem] p-4 shadow-2xl border-8 border-slate-800 overflow-hidden aspect-[16/10] w-full max-w-[420px] mx-auto flex flex-col justify-between transition-all" style="background-color: #2D5A27; background-size: cover; background-position: center;">
                        
                        <!-- Mockup Side Decors -->
                        <div id="mock-deco-container" class="absolute inset-0 pointer-events-none z-10">
                            <img id="mock-deco-tl" src="./gambar/lampu.webp" class="absolute -top-1 left-2 w-10 object-contain drop-shadow">
                            <img id="mock-deco-tr" src="./gambar/lampu.webp" class="absolute -top-2 right-4 w-9 object-contain drop-shadow">
                            <img id="mock-deco-bl" src="./gambar/ketupat.webp" class="absolute -bottom-2 -left-2 w-12 object-contain drop-shadow opacity-70">
                            <img id="mock-deco-br" src="./gambar/ketupat.webp" class="absolute top-1/2 -right-2 w-10 object-contain drop-shadow opacity-60">
                        </div>

                        <!-- Mockup Header -->
                        <div class="text-center z-20 relative pt-2">
                            <h3 id="mock-title" class="font-playfair text-lg font-bold drop-shadow leading-tight" style="color: #D48C12;">Berbuka Bersama</h3>
                            <p id="mock-subtitle" class="font-playfair text-xs font-semibold italic drop-shadow" style="color: #D48C12;">Mahaghora Group</p>
                        </div>

                        <!-- Mockup Camera Frame -->
                        <div class="mx-auto w-3/4 flex-1 my-2 bg-slate-900/90 rounded-xl border border-white/20 relative flex items-center justify-center overflow-hidden shadow-inner">
                            <div class="text-center text-white/50 text-[10px] flex flex-col items-center gap-1">
                                <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Kamera (Live Preview)</span>
                            </div>
                        </div>

                        <!-- Mockup Capture Button -->
                        <div class="text-center z-20 relative pb-1">
                            <span id="mock-btn" class="inline-flex items-center gap-1 text-[11px] font-bold text-white px-4 py-1.5 rounded-full shadow-lg" style="background-color: #D48C12;">
                                📷 Ambil Foto
                            </span>
                        </div>
                    </div>

                    <div class="bg-emerald-900/10 text-emerald-950 p-4 rounded-2xl text-xs space-y-1.5 border border-emerald-200/50">
                        <p class="font-bold text-emerald-900">💡 TIPS PENGATURAN</p>
                        <p>• Perubahan warna dan teks langsung terlihat di pratinjau di atas secara real-time.</p>
                        <p>• Setelah klik <strong>Simpan Pengaturan</strong>, layar photobooth pengunjung akan otomatis menggunakan tema baru.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Hidden Print Iframe -->
    <iframe id="print-frame" style="display: none; position: fixed; right: 0; bottom: 0; width: 0; height: 0; border: 0;"></iframe>

    <script>
        // ================= TAB MANAGEMENT =================
        function switchTab(tab) {
            const btnQueue = document.getElementById('tab-btn-queue');
            const btnTemplates = document.getElementById('tab-btn-templates');
            const btnBranding = document.getElementById('tab-btn-branding');
            const contentQueue = document.getElementById('tab-content-queue');
            const contentTemplates = document.getElementById('tab-content-templates');
            const contentBranding = document.getElementById('tab-content-branding');
            const queueControls = document.getElementById('queue-controls');

            // Reset all active classes & hide all sections
            if (btnQueue) btnQueue.classList.remove('active');
            if (btnTemplates) btnTemplates.classList.remove('active');
            if (btnBranding) btnBranding.classList.remove('active');
            if (contentQueue) contentQueue.classList.add('hidden');
            if (contentTemplates) contentTemplates.classList.add('hidden');
            if (contentBranding) contentBranding.classList.add('hidden');
            if (queueControls) queueControls.classList.add('hidden');

            if (tab === 'queue') {
                if (btnQueue) btnQueue.classList.add('active');
                if (contentQueue) contentQueue.classList.remove('hidden');
                if (queueControls) queueControls.classList.remove('hidden');
                fetchQueue();
            } else if (tab === 'templates') {
                if (btnTemplates) btnTemplates.classList.add('active');
                if (contentTemplates) contentTemplates.classList.remove('hidden');
                fetchTemplates();
                if (typeof renderTemplateItems === 'function') renderTemplateItems();
                if (typeof updateLivePreview === 'function') setTimeout(updateLivePreview, 100);
            } else if (tab === 'branding') {
                if (btnBranding) btnBranding.classList.add('active');
                if (contentBranding) contentBranding.classList.remove('hidden');
                fetchBrandingSettings();
            }
        }
        window.switchTab = switchTab;

        // ================= AUDIO NOTIFICATION =================
        function playChime() {
            if (!document.getElementById('sound-toggle').checked) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.setValueAtTime(880, ctx.currentTime + 0.15); // A5
                
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                osc.start();
                osc.stop(ctx.currentTime + 0.8);
            } catch (e) {
                // Audio context may require user interaction first
            }
        }

        // ================= PRINT QUEUE LOGIC =================
        let lastQueueCount = 0;
        let lastQueueIds = new Set();

        async function fetchQueue() {
            const status = document.getElementById('status-filter').value;
            try {
                const res = await fetch(`print_action.php?action=get_queue&status=${status}`);
                const data = await res.json();
                
                if (data.success) {
                    renderQueue(data.queue || []);
                    const pendingCount = data.pending_count || 0;
                    document.getElementById('queue-badge-count').innerText = pendingCount;
                    
                    // Check for new items to ring chime
                    const currentPendingIds = new Set((data.queue || []).filter(q => q.status === 'pending').map(q => q.id));
                    let hasNew = false;
                    for (let id of currentPendingIds) {
                        if (!lastQueueIds.has(id)) {
                            hasNew = true;
                            break;
                        }
                    }
                    if (hasNew && lastQueueIds.size > 0) {
                        playChime();
                    }
                    lastQueueIds = currentPendingIds;
                }
            } catch (err) {
                console.error("Queue fetch error:", err);
            }
        }

        function renderQueue(items) {
            const list = document.getElementById('queue-list');
            
            if (items.length === 0) {
                list.innerHTML = `
                    <div class="col-span-full py-20 text-center glass-card rounded-3xl p-6 shadow-md">
                        <div class="w-16 h-16 rounded-3xl bg-amber-500/20 text-amber-800 flex items-center justify-center text-2xl mx-auto mb-3 border border-amber-500/30">
                            ☕
                        </div>
                        <h3 class="text-base font-bold text-amber-950">Belum Ada Antrean Cetak</h3>
                        <p class="text-xs text-amber-800/80 mt-1 max-w-sm mx-auto">Saat peserta menekan tombol cetak di galeri HP mereka, daftar foto yang diminta cetak akan langsung muncul di sini.</p>
                    </div>
                `;
                return;
            }

            list.innerHTML = items.map(item => {
                const isPending = item.status === 'pending';
                const isPrinting = item.status === 'printing';
                const isCompleted = item.status === 'completed';

                let statusBadge = '';
                if (isPending) {
                    statusBadge = `<span class="px-2.5 py-1 bg-amber-400/20 text-amber-900 border border-amber-400/50 rounded-full text-[10px] font-black uppercase flex items-center gap-1 shadow-sm"><span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse-dot"></span> Menunggu Cetak</span>`;
                } else if (isPrinting) {
                    statusBadge = `<span class="px-2.5 py-1 bg-blue-100 text-blue-900 border border-blue-300 rounded-full text-[10px] font-black uppercase flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Sedang Dicetak</span>`;
                } else if (isCompleted) {
                    statusBadge = `<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-full text-[10px] font-black uppercase flex items-center gap-1">✓ Selesai Dicetak</span>`;
                } else {
                    statusBadge = `<span class="px-2.5 py-1 bg-stone-200 text-stone-700 rounded-full text-[10px] font-black uppercase">Dibatalkan</span>`;
                }

                return `
                    <div class="glass-card rounded-3xl border-2 ${isPending ? 'border-amber-400 ring-2 ring-amber-400/30 shadow-xl' : 'border-amber-500/25 shadow-md'} p-5 flex flex-col justify-between transition-all hover:shadow-2xl">
                        <div>
                            <!-- Header Info -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-amber-800/60 uppercase tracking-wider block">ID: ${item.session_id.substring(0, 15)}...</span>
                                    <h4 class="text-sm font-bold text-amber-950">${item.label || 'Photo Strip'}</h4>
                                </div>
                                ${statusBadge}
                            </div>

                            <!-- Photo Preview -->
                            <div class="aspect-[9/16] bg-black/10 rounded-2xl overflow-hidden mb-4 border-2 border-amber-500/30 relative group flex items-center justify-center p-1.5 shadow-inner">
                                <img src="${item.photo_url}" class="w-full h-full object-contain rounded-xl cursor-pointer transition-transform group-hover:scale-105" onclick="window.open('${item.photo_url}', '_blank')">
                                <a href="${item.photo_url}" target="_blank" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-[2px] rounded-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat HD
                                </a>
                            </div>

                            <!-- Meta Info -->
                            <div class="text-[11px] text-amber-800/80 space-y-1 mb-4 font-medium">
                                <p>⏱️ Request: <span class="text-amber-950 font-bold">${item.created_at}</span></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-3 border-t border-amber-900/10">
                            <button onclick="printPhoto('${item.id}', '${item.photo_url}')" 
                                    class="w-full py-3 px-4 ${isCompleted ? 'bg-amber-500 hover:bg-amber-600 text-stone-900' : 'bg-[#2D5A27] hover:bg-[#1f401b] text-white'} rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span>${isCompleted ? '🖨️ Cetak / Print Ulang' : '🖨️ Cetak / Print Sekarang'}</span>
                            </button>

                            <div class="grid grid-cols-2 gap-2">
                                ${!isCompleted ? `
                                    <button onclick="updateQueueStatus('${item.id}', 'completed')" 
                                            class="py-2 px-3 bg-emerald-100 text-emerald-900 hover:bg-emerald-200 border border-emerald-300 rounded-xl font-bold text-[11px] transition-all shadow-sm cursor-pointer">
                                        ✓ Tandai Selesai
                                    </button>
                                ` : `
                                    <button onclick="updateQueueStatus('${item.id}', 'pending')" 
                                            class="py-2 px-3 bg-stone-200/80 text-stone-800 hover:bg-stone-300 rounded-xl font-bold text-[11px] transition-all shadow-sm cursor-pointer">
                                        ↩ Jadikan Pending
                                    </button>
                                `}
                                <button onclick="deleteQueueItem('${item.id}')" 
                                        class="py-2 px-3 bg-rose-100 text-rose-700 hover:bg-rose-200 border border-rose-300 rounded-xl font-bold text-[11px] transition-all shadow-sm cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Dedicated Clean Print Handler (Opens print dialog for just the image, borderless)
        function printPhoto(queueId, imageUrl) {
            // 1. Mark status as completed without removing from list
            updateQueueStatus(queueId, 'completed');

            // 2. Open clean print window
            const printWindow = window.open('', '_blank', 'width=800,height=900');
            if (!printWindow) {
                alert('Pop-up terblokir oleh browser. Harap izinkan pop-up untuk mencetak otomatis.');
                return;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cetak Photo Booth</title>
                    <style>
                        @page {
                            size: auto;
                            margin: 0mm;
                        }
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 100vh;
                            background: #fff;
                        }
                        img {
                            width: 100%;
                            height: 100vh;
                            object-fit: contain;
                            display: block;
                        }
                    </style>
                </head>
                <body>
                    <img src="${imageUrl}" onload="window.focus(); window.print(); setTimeout(() => window.close(), 1000);">
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        async function updateQueueStatus(id, status) {
            try {
                await fetch('print_action.php?action=update_status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status })
                });
                fetchQueue();
            } catch (e) {
                console.error(e);
            }
        }

        async function deleteQueueItem(id) {
            if (!confirm('Hapus item ini dari antrean?')) return;
            try {
                await fetch('print_action.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                fetchQueue();
            } catch (e) {
                console.error(e);
            }
        }

        async function clearCompletedQueue() {
            if (!confirm('Bersihkan semua riwayat yang sudah selesai / dibatalkan?')) return;
            try {
                await fetch('print_action.php?action=clear_completed', {
                    method: 'POST'
                });
                fetchQueue();
            } catch (e) {
                console.error(e);
            }
        }

        // ================= DYNAMIC MULTI-SLOT (PHOTO) & MULTI-ITEM TEMPLATE LOGIC =================
        const form = document.getElementById('upload-form');
        const list = document.getElementById('template-list');
        const canvas = document.getElementById('preview-canvas');
        const ctx = canvas.getContext('2d');

        // Dynamic Photo Slots (Tempat Foto Persegi Panjang)
        let photoSlots = [
            { id: 'slot_1', name: 'Foto #1', x: 94, y: 300, width: 760, height: 372, radius: 20 },
            { id: 'slot_2', name: 'Foto #2', x: 894, y: 300, width: 760, height: 372, radius: 20 },
            { id: 'slot_3', name: 'Foto #3', x: 94, y: 717, width: 760, height: 372, radius: 20 },
            { id: 'slot_4', name: 'Foto #4', x: 894, y: 717, width: 760, height: 372, radius: 20 },
            { id: 'slot_5', name: 'Foto #5', x: 94, y: 1134, width: 760, height: 372, radius: 20 },
            { id: 'slot_6', name: 'Foto #6', x: 894, y: 1134, width: 760, height: 372, radius: 20 }
        ];

        // Dynamic Items array with width & height (Stiker / Hiasan)
        let templateItems = [
            { id: 'item_1', name: 'Item 1 (Ketupat)', src: './gambar/ketupat.webp', width: 350, height: 350, size: 350, x: 120, y: 150, slot: 1 },
            { id: 'item_2', name: 'Item 2 (Lampu)', src: './gambar/lampu.webp', width: 300, height: 300, size: 300, x: -100, y: 140, slot: 2 },
            { id: 'item_3', name: 'Item 3 (Stiker)', src: './gambar/rama.png', width: 550, height: 550, size: 550, x: 150, y: 300, slot: 5 }
        ];

        // Cached Image elements for dynamic items
        const itemImageCache = {};

        function getItemImage(src) {
            if (!src) return null;
            if (!itemImageCache[src]) {
                const img = new Image();
                img.onload = () => updateLivePreview();
                img.src = src;
                itemImageCache[src] = img;
            }
            return itemImageCache[src];
        }

        // Preview Outer background image
        let outerPreviewImage = null;

        // Selection & Drag State Variables (Multi-Selection + Marquee Support)
        let selectedTargets = [{ type: 'slot', id: 'slot_1' }]; // Array of { type: 'slot'|'item', id: string }
        let selectedTarget = { type: 'slot', id: 'slot_1' }; // Primary active target
        let slotBounds = {};
        let itemBounds = {};
        let activeDraggedTarget = null; // { type: 'slot'|'item', id: '...' }
        let dragMode = null; // 'move' | 'resize' | 'marquee'
        let hoveredTarget = null;
        let dragStartMouseX = 0;
        let dragStartMouseY = 0;
        let dragInitialX = 0;
        let dragInitialY = 0;
        let dragInitialW = 760;
        let dragInitialH = 372;
        let marqueeStart = { x: 0, y: 0 };
        let marqueeEnd = { x: 0, y: 0 };
        let dragInitialPositions = {}; // id -> { x, y, itmXOff, itmYOff, type }

        // Rectangle Verification Helper: W != H
        function isRectangle(w, h) {
            return Math.abs(w - h) >= 10;
        }

        function getRectangleTypeLabel(w, h) {
            if (!isRectangle(w, h)) return '⚠️ Bukan Persegi Panjang (Bujursangkar)';
            const ratio = (w / h).toFixed(2);
            if (w > h) {
                return `✅ Persegi Panjang Landscape (Rasio ${ratio}:1)`;
            } else {
                return `✅ Persegi Panjang Portrait (Rasio 1:${(h/w).toFixed(2)})`;
            }
        }

        function isTargetSelected(type, id) {
            return selectedTargets.some(t => t.type === type && t.id === id);
        }

        // ================= TOAST NOTIFICATION & FEEDBACK =================
        function showDesignToast(icon, message, type = 'info') {
            let toastEl = document.getElementById('design-action-toast');
            if (!toastEl) {
                toastEl = document.createElement('div');
                toastEl.id = 'design-action-toast';
                toastEl.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl text-xs font-bold transition-all duration-300 transform translate-y-8 opacity-0 pointer-events-none border';
                document.body.appendChild(toastEl);
            }

            const colorClasses = {
                success: 'bg-emerald-900/95 text-emerald-100 border-emerald-400/40',
                warning: 'bg-amber-900/95 text-amber-100 border-amber-400/40',
                info: 'bg-slate-900/95 text-slate-100 border-amber-400/30',
                danger: 'bg-rose-900/95 text-rose-100 border-rose-400/40'
            };

            toastEl.className = `fixed bottom-6 right-6 z-50 flex items-center gap-2.5 px-4 py-2.5 rounded-2xl shadow-2xl text-xs font-bold transition-all duration-200 transform translate-y-0 opacity-100 border backdrop-blur-md ${colorClasses[type] || colorClasses.info}`;
            toastEl.innerHTML = `
                <span class="text-base">${icon}</span>
                <span>${message}</span>
            `;

            if (window._toastTimer) clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(() => {
                toastEl.classList.add('translate-y-8', 'opacity-0');
            }, 2500);

            const statusEl = document.getElementById('drag-coord-status');
            if (statusEl) statusEl.textContent = `${icon} ${message}`;
        }

        // ================= HISTORY (UNDO / REDO) & CLIPBOARD (COPY / PASTE / DUPLICATE / CUT) =================
        const undoStack = [];
        const redoStack = [];
        const MAX_HISTORY = 40;
        let clipboard = [];

        // Try restoring clipboard from session
        try {
            const savedClip = localStorage.getItem('photobooth_clipboard');
            if (savedClip) clipboard = JSON.parse(savedClip);
        } catch(e) {}

        function saveHistoryState(actionLabel = 'Perubahan') {
            try {
                const snapshot = {
                    photoSlots: JSON.parse(JSON.stringify(photoSlots)),
                    templateItems: JSON.parse(JSON.stringify(templateItems)),
                    selectedTargets: JSON.parse(JSON.stringify(selectedTargets)),
                    actionLabel: actionLabel
                };
                undoStack.push(snapshot);
                if (undoStack.length > MAX_HISTORY) undoStack.shift();
                redoStack.length = 0; // Clear redo on fresh action
                updateUndoRedoUI();
            } catch (err) {
                console.error("History snapshot error:", err);
            }
        }

        function updateUndoRedoUI() {
            const btnUndo = document.getElementById('btn-undo');
            const btnRedo = document.getElementById('btn-redo');
            if (btnUndo) {
                btnUndo.disabled = undoStack.length === 0;
                btnUndo.classList.toggle('opacity-40', undoStack.length === 0);
                btnUndo.classList.toggle('cursor-not-allowed', undoStack.length === 0);
            }
            if (btnRedo) {
                btnRedo.disabled = redoStack.length === 0;
                btnRedo.classList.toggle('opacity-40', redoStack.length === 0);
                btnRedo.classList.toggle('cursor-not-allowed', redoStack.length === 0);
            }
        }

        function undo() {
            if (undoStack.length === 0) {
                showDesignToast('↩️', 'Tidak ada riwayat untuk di-undo', 'warning');
                return;
            }
            try {
                const current = {
                    photoSlots: JSON.parse(JSON.stringify(photoSlots)),
                    templateItems: JSON.parse(JSON.stringify(templateItems)),
                    selectedTargets: JSON.parse(JSON.stringify(selectedTargets)),
                    actionLabel: 'Sebelum Undo'
                };
                redoStack.push(current);

                const prev = undoStack.pop();
                photoSlots = prev.photoSlots || [];
                templateItems = prev.templateItems || [];
                selectedTargets = prev.selectedTargets || [];
                selectedTarget = selectedTargets[0] || { type: 'none', id: null };

                renderPhotoSlots();
                renderTemplateItems();
                updateLivePreview();
                updateUndoRedoUI();

                showDesignToast('↩️', `Undo: ${prev.actionLabel || 'Kembali'} (Ctrl+Z)`, 'info');
            } catch (err) {
                console.error("Undo error:", err);
            }
        }

        function redo() {
            if (redoStack.length === 0) {
                showDesignToast('↪️', 'Tidak ada riwayat untuk di-redo', 'warning');
                return;
            }
            try {
                const current = {
                    photoSlots: JSON.parse(JSON.stringify(photoSlots)),
                    templateItems: JSON.parse(JSON.stringify(templateItems)),
                    selectedTargets: JSON.parse(JSON.stringify(selectedTargets)),
                    actionLabel: 'Sebelum Redo'
                };
                undoStack.push(current);

                const next = redoStack.pop();
                photoSlots = next.photoSlots || [];
                templateItems = next.templateItems || [];
                selectedTargets = next.selectedTargets || [];
                selectedTarget = selectedTargets[0] || { type: 'none', id: null };

                renderPhotoSlots();
                renderTemplateItems();
                updateLivePreview();
                updateUndoRedoUI();

                showDesignToast('↪️', 'Redo: Mengulangi perubahan (Ctrl+Y)', 'info');
            } catch (err) {
                console.error("Redo error:", err);
            }
        }

        function copySelected() {
            // Auto-fallback: if nothing selected, select first available item or slot
            if (selectedTargets.length === 0) {
                if (photoSlots.length > 0) {
                    selectedTargets = [{ type: 'slot', id: photoSlots[0].id }];
                } else if (templateItems.length > 0) {
                    selectedTargets = [{ type: 'item', id: templateItems[0].id }];
                } else {
                    showDesignToast('⚠️', 'Belum ada objek untuk disalin. Buat slot foto terlebih dahulu!', 'warning');
                    return;
                }
                renderPhotoSlots();
                renderTemplateItems();
                updateLivePreview();
            }

            clipboard = [];
            selectedTargets.forEach(tgt => {
                if (tgt.type === 'slot') {
                    const slot = photoSlots.find(s => s.id === tgt.id);
                    if (slot) clipboard.push({ type: 'slot', data: JSON.parse(JSON.stringify(slot)) });
                } else {
                    const itm = templateItems.find(i => i.id === tgt.id);
                    if (itm) clipboard.push({ type: 'item', data: JSON.parse(JSON.stringify(itm)) });
                }
            });

            try {
                localStorage.setItem('photobooth_clipboard', JSON.stringify(clipboard));
            } catch(e) {}

            showDesignToast('📋', `${clipboard.length} objek disalin (Ctrl+C)`, 'success');
        }

        function pasteCopied() {
            if (clipboard.length === 0) {
                try {
                    const savedClip = localStorage.getItem('photobooth_clipboard');
                    if (savedClip) clipboard = JSON.parse(savedClip);
                } catch(e) {}
            }

            if (!clipboard || clipboard.length === 0) {
                showDesignToast('⚠️', 'Clipboard kosong! Tekan Ctrl+C untuk salin objek terlebih dahulu.', 'warning');
                return;
            }

            saveHistoryState('Paste ' + clipboard.length + ' Objek');

            const newSelected = [];
            const offset = 40;

            clipboard.forEach(entry => {
                if (entry.type === 'slot') {
                    const newSlot = {
                        ...JSON.parse(JSON.stringify(entry.data)),
                        id: 'slot_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                        name: (entry.data.name || 'Foto') + ' (Salinan)',
                        x: (parseInt(entry.data.x) || 0) + offset,
                        y: (parseInt(entry.data.y) || 0) + offset
                    };
                    photoSlots.push(newSlot);
                    newSelected.push({ type: 'slot', id: newSlot.id });
                } else {
                    const newItem = {
                        ...JSON.parse(JSON.stringify(entry.data)),
                        id: 'item_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                        name: (entry.data.name || 'Item') + ' (Salinan)',
                        x: (parseInt(entry.data.x) || 0) + offset,
                        y: (parseInt(entry.data.y) || 0) + offset
                    };
                    templateItems.push(newItem);
                    newSelected.push({ type: 'item', id: newItem.id });
                }
            });

            selectedTargets = newSelected;
            selectedTarget = selectedTargets[0] || { type: 'none', id: null };

            renderPhotoSlots();
            renderTemplateItems();
            updateLivePreview();

            showDesignToast('📥', `${newSelected.length} objek ditempel (Ctrl+V)`, 'success');
        }

        function duplicateSelected() {
            if (selectedTargets.length === 0) {
                if (photoSlots.length > 0) {
                    selectedTargets = [{ type: 'slot', id: photoSlots[0].id }];
                } else if (templateItems.length > 0) {
                    selectedTargets = [{ type: 'item', id: templateItems[0].id }];
                } else {
                    showDesignToast('⚠️', 'Pilih objek terlebih dahulu untuk diduplikat.', 'warning');
                    return;
                }
            }
            copySelected();
            pasteCopied();
            showDesignToast('📑', 'Objek berhasil diduplikat (Ctrl+D)', 'success');
        }

        function deleteSelected() {
            if (selectedTargets.length === 0) {
                showDesignToast('⚠️', 'Pilih objek yang ingin dihapus terlebih dahulu.', 'warning');
                return;
            }
            const count = selectedTargets.length;
            saveHistoryState('Hapus ' + count + ' Objek');

            const slotIdsToDelete = new Set(selectedTargets.filter(t => t.type === 'slot').map(t => t.id));
            const itemIdsToDelete = new Set(selectedTargets.filter(t => t.type === 'item').map(t => t.id));

            photoSlots = photoSlots.filter(s => !slotIdsToDelete.has(s.id));
            templateItems = templateItems.filter(i => !itemIdsToDelete.has(i.id));
            
            selectedTargets = [];
            selectedTarget = { type: 'none', id: null };

            renderPhotoSlots();
            renderTemplateItems();
            updateLivePreview();

            showDesignToast('🗑️', `${count} objek dihapus (Delete)`, 'danger');
        }

        function cutSelected() {
            if (selectedTargets.length === 0) {
                showDesignToast('⚠️', 'Pilih objek yang ingin dipotong terlebih dahulu.', 'warning');
                return;
            }
            copySelected();
            deleteSelected();
            showDesignToast('✂️', 'Objek dipotong (Ctrl+X)', 'info');
        }

        // ================= RENDER PHOTO SLOTS =================
        function renderPhotoSlots() {
            const container = document.getElementById('dynamic-slots-container');
            if (!container) return;

            if (photoSlots.length === 0) {
                container.innerHTML = `
                    <div class="p-6 bg-white rounded-2xl border-2 border-dashed border-amber-300 text-center text-amber-800 text-xs">
                        <p class="font-bold">Belum ada tempat foto yang ditambahkan.</p>
                        <p class="text-[11px] text-stone-500 mt-0.5">Template membutuhkan minimal 1 tempat foto (bisa ditambah 2, 3, 4, 6, dll).</p>
                        <button type="button" onclick="addNewPhotoSlot()" class="mt-2.5 px-4 py-2 bg-amber-500 text-stone-900 font-bold rounded-xl hover:bg-amber-400 transition-all text-xs shadow-sm">+ Tambah Tempat Foto Pertama</button>
                    </div>
                `;
                renderPreviewButtons();
                return;
            }

            container.innerHTML = photoSlots.map((slot, idx) => {
                const isSelected = isTargetSelected('slot', slot.id);
                const isRect = isRectangle(slot.width, slot.height);
                const rectLabel = getRectangleTypeLabel(slot.width, slot.height);
                const isLocked = slot.lockRatio !== false;

                return `
                    <div id="card-slot-${slot.id}" class="p-4 bg-white rounded-2xl border transition-all ${isSelected ? 'border-amber-500 ring-2 ring-amber-400/50 bg-amber-50/30 shadow-md' : 'border-amber-200/90 shadow-sm'}">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3 pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-amber-400 text-slate-900 font-black text-xs flex items-center justify-center shadow-sm">
                                    #${idx + 1}
                                </span>
                                <input type="text" value="${slot.name || 'Foto #' + (idx + 1)}" onchange="updatePhotoSlotProp('${slot.id}', 'name', this.value)" class="text-xs font-bold text-slate-900 bg-transparent border-b border-gray-300 focus:border-amber-600 outline-none px-1 py-0.5" placeholder="Nama Slot Foto">
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold ${isRect ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300 animate-pulse'}">
                                    ${rectLabel}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="toggleSlotLockRatio('${slot.id}')" class="text-[11px] font-bold px-2.5 py-1 rounded-xl transition-all flex items-center gap-1 ${isLocked ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-stone-100 text-stone-500 border border-stone-200'}">
                                    <span>${isLocked ? '🔒' : '🔓'}</span>
                                    <span>${isLocked ? 'Rasio Terkunci (Anti-Penyet)' : 'Bebas (Tidak Terkunci)'}</span>
                                </button>
                                <button type="button" onclick="selectSlotForDrag('${slot.id}', event.shiftKey || event.ctrlKey)" class="text-[11px] font-bold px-3 py-1 rounded-xl transition-all ${isSelected ? 'bg-amber-500 text-stone-950 font-black shadow' : 'bg-stone-100 hover:bg-stone-200 text-stone-700'}">
                                    ${isSelected ? '🎯 Terpilih' : 'Pilih di Monitor'}
                                </button>
                                <button type="button" onclick="removePhotoSlot('${slot.id}')" class="text-xs text-rose-500 hover:text-rose-700 font-bold p-1 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Slot Foto Ini">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 items-end">
                            <!-- 1. Preset Rasio Persegi Panjang -->
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[10px] uppercase font-bold text-amber-900 mb-1">Rasio Persegi Panjang</label>
                                <select onchange="applySlotRatio('${slot.id}', this.value)" class="w-full px-2 py-1.5 rounded-lg border border-amber-300 text-xs bg-amber-50/50 font-bold text-slate-800 cursor-pointer">
                                    <option value="">-- Kustom Rasio --</option>
                                    <option value="2:1">2:1 (Landscape Lebar)</option>
                                    <option value="16:9">16:9 (Cinema)</option>
                                    <option value="3:2">3:2 (Foto Standar)</option>
                                    <option value="4:3">4:3 (Klasik)</option>
                                    <option value="3:4">3:4 (Portrait Klasik)</option>
                                    <option value="9:16">9:16 (Story/Strip)</option>
                                    <option value="2:3">2:3 (Portrait Standar)</option>
                                </select>
                            </div>

                            <!-- 2. Lebar (Width) -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[10px] uppercase font-bold text-gray-500">Lebar (px)</label>
                                    <span class="text-[9px] text-amber-700 font-bold">W ${isLocked ? '🔒' : ''}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <input type="number" id="slot_w_${slot.id}" value="${slot.width}" oninput="updatePhotoSlotDimension('${slot.id}', 'width', parseInt(this.value)||100)" class="w-full px-2 py-1.5 rounded-lg border ${!isRect ? 'border-rose-400 bg-rose-50' : 'border-gray-200'} text-xs font-semibold">
                                    <div class="flex flex-col gap-0.5">
                                        <button type="button" onclick="scalePhotoSlot('${slot.id}', 1.05)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none" title="Besarkan 5% Skala Proporsional">+</button>
                                        <button type="button" onclick="scalePhotoSlot('${slot.id}', 0.95)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none" title="Kecilkan 5% Skala Proporsional">-</button>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Panjang / Tinggi (Height) -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[10px] uppercase font-bold text-gray-500">Panjang (px)</label>
                                    <span class="text-[9px] text-amber-700 font-bold">H ${isLocked ? '🔒' : ''}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <input type="number" id="slot_h_${slot.id}" value="${slot.height}" oninput="updatePhotoSlotDimension('${slot.id}', 'height', parseInt(this.value)||100)" class="w-full px-2 py-1.5 rounded-lg border ${!isRect ? 'border-rose-400 bg-rose-50' : 'border-gray-200'} text-xs font-semibold">
                                    <div class="flex flex-col gap-0.5">
                                        <button type="button" onclick="scalePhotoSlot('${slot.id}', 1.05)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none" title="Besarkan 5% Skala Proporsional">+</button>
                                        <button type="button" onclick="scalePhotoSlot('${slot.id}', 0.95)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none" title="Kecilkan 5% Skala Proporsional">-</button>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Skala Kelipatan Proporsional -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-amber-900 mb-1">Skala Kelipatan</label>
                                <div class="flex items-center gap-1">
                                    <button type="button" onclick="scalePhotoSlot('${slot.id}', 0.85)" class="flex-1 py-1 px-1 bg-amber-100 hover:bg-amber-200 text-amber-950 font-black rounded-lg text-[10px] transition-all" title="Kecilkan 15%">
                                        -15%
                                    </button>
                                    <button type="button" onclick="scalePhotoSlot('${slot.id}', 1.15)" class="flex-1 py-1 px-1 bg-amber-400 hover:bg-amber-300 text-stone-950 font-black rounded-lg text-[10px] transition-all" title="Besarkan 15%">
                                        +15%
                                    </button>
                                </div>
                            </div>

                            <!-- 5. Posisi X -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Posisi X (px)</label>
                                <input type="number" id="slot_x_${slot.id}" value="${slot.x || 0}" oninput="updatePhotoSlotProp('${slot.id}', 'x', parseInt(this.value)||0)" class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold">
                            </div>

                            <!-- 6. Posisi Y -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Posisi Y (px)</label>
                                <input type="number" id="slot_y_${slot.id}" value="${slot.y || 0}" oninput="updatePhotoSlotProp('${slot.id}', 'y', parseInt(this.value)||0)" class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold">
                            </div>
                        </div>

                        ${!isRect ? `
                        <div class="mt-2.5 p-2 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between text-[11px] text-rose-800">
                            <span>⚠️ <strong>Perhatian:</strong> Ukuran Lebar dan Panjang saat ini sama (${slot.width}x${slot.height}px). Tempat foto wajib berbentuk persegi panjang.</span>
                            <button type="button" onclick="applySlotRatio('${slot.id}', '2:1')" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg shrink-0 ml-2">Ubah ke 2:1</button>
                        </div>
                        ` : ''}
                    </div>
                `;
            }).join('');

            renderPreviewButtons();
        }

        function toggleSlotLockRatio(id) {
            const slot = photoSlots.find(s => s.id === id);
            if (slot) {
                slot.lockRatio = !(slot.lockRatio !== false);
                renderPhotoSlots();
            }
        }

        function scalePhotoSlot(id, factor) {
            const slot = photoSlots.find(s => s.id === id);
            if (!slot) return;

            const currentW = slot.width || 760;
            const currentH = slot.height || 372;
            const newW = Math.max(60, Math.round(currentW * factor));
            const newH = Math.max(40, Math.round(currentH * factor));

            slot.width = newW;
            slot.height = newH;
            
            // Guarantee W != H
            if (slot.width === slot.height) {
                slot.height = Math.round(slot.width * 0.6);
            }

            renderPhotoSlots();
            updateLivePreview();
        }

        function addNewPhotoSlot() {
            const nextNum = photoSlots.length + 1;
            const is6Grid = form.sizeType && form.sizeType.value === 'a5_6grid';
            
            // Standard rectangular default dimensions (760x372 for A5 grid or 1540x650 for A5 strip)
            const defaultW = is6Grid ? 760 : 1540;
            const defaultH = is6Grid ? 372 : 650;
            const defaultY = 250 + ((nextNum - 1) * (defaultH + 50));

            const newSlot = {
                id: 'slot_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                name: 'Foto #' + nextNum,
                x: 94,
                y: Math.min(defaultY, 2000),
                width: defaultW,
                height: defaultH,
                radius: 20,
                lockRatio: true
            };

            photoSlots.push(newSlot);
            renderPhotoSlots();
            selectSlotForDrag(newSlot.id);
        }

        function removePhotoSlot(id) {
            photoSlots = photoSlots.filter(s => s.id !== id);
            selectedTargets = selectedTargets.filter(t => !(t.type === 'slot' && t.id === id));
            if (selectedTargets.length === 0 && photoSlots.length > 0) {
                selectedTargets = [{ type: 'slot', id: photoSlots[0].id }];
            }
            selectedTarget = selectedTargets[0] || { type: 'none', id: null };
            renderPhotoSlots();
            updateLivePreview();
        }

        function updatePhotoSlotProp(id, prop, val) {
            const slot = photoSlots.find(s => s.id === id);
            if (slot) {
                slot[prop] = val;
                updateLivePreview();
            }
        }

        function updatePhotoSlotDimension(id, dim, val) {
            const slot = photoSlots.find(s => s.id === id);
            if (slot) {
                const isLocked = slot.lockRatio !== false;
                const prevW = slot.width || 760;
                const prevH = slot.height || 372;
                const ratio = prevW / prevH;

                if (dim === 'width') {
                    slot.width = Math.max(50, val);
                    if (isLocked) {
                        slot.height = Math.max(40, Math.round(slot.width / ratio));
                    }
                } else if (dim === 'height') {
                    slot.height = Math.max(50, val);
                    if (isLocked) {
                        slot.width = Math.max(40, Math.round(slot.height * ratio));
                    }
                }

                // Check if width == height, if so gently nudge to maintain rectangle
                if (slot.width === slot.height) {
                    if (dim === 'width') slot.height = Math.round(slot.width * 0.55);
                    else slot.width = Math.round(slot.height * 1.8);
                }

                updateLivePreview();
                renderPhotoSlots();
            }
        }

        function applySlotRatio(id, ratioName) {
            const slot = photoSlots.find(s => s.id === id);
            if (!slot || !ratioName) return;

            const w = slot.width || 760;
            switch(ratioName) {
                case '2:1':
                    slot.height = Math.round(w / 2);
                    break;
                case '16:9':
                    slot.height = Math.round(w * 9 / 16);
                    break;
                case '3:2':
                    slot.height = Math.round(w * 2 / 3);
                    break;
                case '4:3':
                    slot.height = Math.round(w * 3 / 4);
                    break;
                case '3:4':
                    slot.height = Math.round(w * 4 / 3);
                    break;
                case '9:16':
                    slot.height = Math.round(w * 16 / 9);
                    break;
                case '2:3':
                    slot.height = Math.round(w * 3 / 2);
                    break;
            }

            // Ensure W != H
            if (slot.width === slot.height) {
                slot.height = Math.round(slot.width * 0.6);
            }

            renderPhotoSlots();
            updateLivePreview();
        }

        function applySlotPreset(presetName) {
            const sizeType = form.sizeType ? form.sizeType.value : 'a5_6grid';
            const isA5 = sizeType.includes('a5') || sizeType.includes('4r');
            const cWidth = isA5 ? 1748 : 1080;
            const cHeight = isA5 ? 2480 : 1920;

            if (presetName === 'a5_6grid') {
                const padX = 94;
                const gapX = 40;
                const gapY = 45;
                const slotW = Math.round((cWidth - (2 * padX) - gapX) / 2); // 760
                const slotH = Math.round(slotW * (450 / 920)); // 372
                const totalH = (3 * slotH) + (2 * gapY);
                const topY = Math.round((cHeight - totalH) / 2);

                photoSlots = [];
                for (let i = 0; i < 6; i++) {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    photoSlots.push({
                        id: 'slot_' + (i + 1),
                        name: 'Foto #' + (i + 1),
                        x: padX + col * (slotW + gapX),
                        y: topY + row * (slotH + gapY),
                        width: slotW,
                        height: slotH,
                        radius: 20,
                        lockRatio: true
                    });
                }
            } else if (presetName === 'a5_3strip') {
                const padX = isA5 ? 104 : 80;
                const slotW = isA5 ? 1540 : 920;
                const slotH = isA5 ? 650 : 450;
                const headerH = isA5 ? 180 : 150;
                const gap = isA5 ? 60 : 80;

                photoSlots = [];
                for (let i = 0; i < 3; i++) {
                    photoSlots.push({
                        id: 'slot_' + (i + 1),
                        name: 'Foto #' + (i + 1),
                        x: padX,
                        y: padX + headerH + (i * (slotH + gap)),
                        width: slotW,
                        height: slotH,
                        radius: 24,
                        lockRatio: true
                    });
                }
            } else if (presetName === 'a5_4grid') {
                const padX = 94;
                const gapX = 40;
                const gapY = 50;
                const slotW = Math.round((cWidth - (2 * padX) - gapX) / 2);
                const slotH = Math.round(slotW * (3 / 4)); // 4:3 ratio (760x570)
                const totalH = (2 * slotH) + gapY;
                const topY = Math.round((cHeight - totalH) / 2);

                photoSlots = [];
                for (let i = 0; i < 4; i++) {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    photoSlots.push({
                        id: 'slot_' + (i + 1),
                        name: 'Foto #' + (i + 1),
                        x: padX + col * (slotW + gapX),
                        y: topY + row * (slotH + gapY),
                        width: slotW,
                        height: slotH,
                        radius: 20,
                        lockRatio: true
                    });
                }
            } else if (presetName === 'a5_2landscape') {
                const padX = 104;
                const slotW = cWidth - (2 * padX); // 1540
                const slotH = Math.round(slotW * (9 / 16)); // 16:9 ratio (866)
                const gap = 80;
                const totalH = (2 * slotH) + gap;
                const topY = Math.round((cHeight - totalH) / 2);

                photoSlots = [
                    { id: 'slot_1', name: 'Foto #1 (Atas)', x: padX, y: topY, width: slotW, height: slotH, radius: 24, lockRatio: true },
                    { id: 'slot_2', name: 'Foto #2 (Bawah)', x: padX, y: topY + slotH + gap, width: slotW, height: slotH, radius: 24, lockRatio: true }
                ];
            } else if (presetName === 'a5_1single') {
                const padX = 104;
                const slotW = cWidth - (2 * padX);
                const slotH = Math.round(slotW * (4 / 3)); // 1540x1155 (Landscape) or 1540x1800
                const topY = Math.round((cHeight - slotH) / 2);

                photoSlots = [
                    { id: 'slot_1', name: 'Foto Utama', x: padX, y: topY, width: slotW, height: slotH, radius: 28, lockRatio: true }
                ];
            }

            selectedTargets = [{ type: 'slot', id: photoSlots[0] ? photoSlots[0].id : null }];
            selectedTarget = selectedTargets[0];
            renderPhotoSlots();
            updateLivePreview();
        }

        function selectSlotForDrag(id, isMulti = false) {
            if (isMulti) {
                const idx = selectedTargets.findIndex(t => t.type === 'slot' && t.id === id);
                if (idx >= 0) selectedTargets.splice(idx, 1);
                else selectedTargets.push({ type: 'slot', id: id });
            } else {
                selectedTargets = [{ type: 'slot', id: id }];
            }
            selectedTarget = selectedTargets[0] || { type: 'none', id: null };
            renderPhotoSlots();
            renderTemplateItems();

            const slot = photoSlots.find(s => s.id === id);
            if (slot) {
                const card = document.getElementById('card-slot-' + id);
                if (card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                const statusEl = document.getElementById('drag-coord-status');
                if (statusEl) {
                    if (selectedTargets.length > 1) {
                        statusEl.textContent = `🎯 ${selectedTargets.length} OBJEK TERPILIH (Tarik untuk geser bersama)`;
                    } else {
                        statusEl.textContent = `📸 ${slot.name.toUpperCase()} (${slot.width}x${slot.height}px) X:${slot.x} Y:${slot.y}`;
                    }
                }
            }
            updateLivePreview();
        }

        function selectItemForDrag(id, isMulti = false) {
            if (isMulti) {
                const idx = selectedTargets.findIndex(t => t.type === 'item' && t.id === id);
                if (idx >= 0) selectedTargets.splice(idx, 1);
                else selectedTargets.push({ type: 'item', id: id });
            } else {
                selectedTargets = [{ type: 'item', id: id }];
            }
            selectedTarget = selectedTargets[0] || { type: 'none', id: null };
            renderTemplateItems();
            renderPhotoSlots();

            const itm = templateItems.find(item => item.id === id);
            if (itm) {
                const card = document.getElementById('card-item-' + id);
                if (card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                const itmW = itm.width || itm.size || 300;
                const itmH = itm.height || itm.size || 300;
                const statusEl = document.getElementById('drag-coord-status');
                if (statusEl) {
                    if (selectedTargets.length > 1) {
                        statusEl.textContent = `🎯 ${selectedTargets.length} OBJEK TERPILIH (Tarik untuk geser bersama)`;
                    } else {
                        statusEl.textContent = `✨ ${(itm.name || 'ITEM').toUpperCase()} (${itmW}x${itmH}) X:${itm.x} Y:${itm.y}`;
                    }
                }
            }
            updateLivePreview();
        }

        function selectAllTargets() {
            selectedTargets = [
                ...photoSlots.map(s => ({ type: 'slot', id: s.id })),
                ...templateItems.map(i => ({ type: 'item', id: i.id }))
            ];
            selectedTarget = selectedTargets[0] || { type: 'none', id: null };
            renderPhotoSlots();
            renderTemplateItems();
            const statusEl = document.getElementById('drag-coord-status');
            if (statusEl) {
                statusEl.textContent = `🎯 SEMUA OBJEK TERPILIH (${selectedTargets.length} Objek) - Tarik untuk geser bersama`;
            }
            updateLivePreview();
        }

        function clearSelection() {
            selectedTargets = [];
            selectedTarget = { type: 'none', id: null };
            renderPhotoSlots();
            renderTemplateItems();
            const statusEl = document.getElementById('drag-coord-status');
            if (statusEl) {
                statusEl.textContent = `Pilihan dibatalkan. Klik item atau tarik kotak seleksi di monitor.`;
            }
            updateLivePreview();
        }

        function nudgeSelectedTargets(deltaX, deltaY) {
            if (selectedTargets.length === 0) {
                selectAllTargets();
            }

            selectedTargets.forEach(tgt => {
                if (tgt.type === 'slot') {
                    const slot = photoSlots.find(s => s.id === tgt.id);
                    if (slot) {
                        slot.x = (parseInt(slot.x) || 0) + deltaX;
                        slot.y = (parseInt(slot.y) || 0) + deltaY;
                        const inX = document.getElementById('slot_x_' + slot.id);
                        const inY = document.getElementById('slot_y_' + slot.id);
                        if (inX) inX.value = slot.x;
                        if (inY) inY.value = slot.y;
                    }
                } else {
                    const itm = templateItems.find(item => item.id === tgt.id);
                    if (itm) {
                        itm.x = (parseInt(itm.x) || 0) + deltaX;
                        itm.y = (parseInt(itm.y) || 0) + deltaY;
                        const inX = document.getElementById('item_x_' + itm.id);
                        const inY = document.getElementById('item_y_' + itm.id);
                        if (inX) inX.value = itm.x;
                        if (inY) inY.value = itm.y;
                    }
                }
            });

            const statusEl = document.getElementById('drag-coord-status');
            if (statusEl) {
                statusEl.textContent = `🎯 ${selectedTargets.length} OBJEK DIGESER (ΔX: ${deltaX}, ΔY: ${deltaY})`;
            }
            updateLivePreview();
        }

        // ================= RENDER TEMPLATE ITEMS (STICKERS) =================
        function renderTemplateItems() {
            const container = document.getElementById('dynamic-items-container');
            if (!container) return;

            if (templateItems.length === 0) {
                container.innerHTML = `
                    <div class="p-6 bg-stone-50 rounded-2xl border-2 border-dashed border-stone-200 text-center text-stone-400 text-xs">
                        <p class="font-bold">Belum ada item / stiker tambahan.</p>
                        <button type="button" onclick="addNewTemplateItem()" class="mt-2 text-emerald-700 font-bold hover:underline">+ Tambah Item Pertama</button>
                    </div>
                `;
                renderPreviewButtons();
                return;
            }

            container.innerHTML = templateItems.map((itm, idx) => {
                const isSelected = isTargetSelected('item', itm.id);
                const colors = ['bg-emerald-500', 'bg-amber-500', 'bg-indigo-500', 'bg-purple-500', 'bg-rose-500', 'bg-blue-500'];
                const dotColor = colors[idx % colors.length];
                const itmW = itm.width || itm.size || 300;
                const itmH = itm.height || itm.size || 300;

                return `
                    <div id="card-item-${itm.id}" class="p-4 bg-gray-50 rounded-2xl border transition-all ${isSelected ? 'border-amber-400 ring-2 ring-amber-400/40 bg-amber-50/20 shadow-md' : 'border-gray-200'}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 ${dotColor} rounded-full"></div>
                                <input type="text" value="${itm.name || 'Item ' + (idx + 1)}" onchange="updateItemProp('${itm.id}', 'name', this.value)" class="text-xs font-bold text-slate-800 bg-transparent border-b border-gray-300 focus:border-emerald-600 outline-none px-1 py-0.5" placeholder="Nama Item">
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="selectItemForDrag('${itm.id}', event.shiftKey || event.ctrlKey)" class="text-[11px] font-bold px-2.5 py-1 rounded-lg ${isSelected ? 'bg-amber-500 text-stone-900 font-black shadow' : 'bg-stone-200 text-stone-700 hover:bg-stone-300'}">
                                    ${isSelected ? '🎯 Terpilih' : 'Pilih di Monitor'}
                                </button>
                                <button type="button" onclick="removeTemplateItem('${itm.id}')" class="text-xs text-rose-500 hover:text-rose-700 font-bold p-1 hover:bg-rose-50 rounded-lg" title="Hapus Item">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 items-end">
                            <!-- 1. Gambar -->
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Gambar</label>
                                <div class="flex items-center gap-2">
                                    <img src="${itm.src || './gambar/ketupat.webp'}" id="thumb-${itm.id}" class="w-9 h-9 object-contain bg-white rounded-lg border border-gray-200 p-0.5 shrink-0">
                                    <input type="file" accept="image/*" onchange="handleItemFileUpload('${itm.id}', this)" class="w-full text-[10px] text-slate-500 file:mr-1.5 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[9px] file:font-bold file:bg-emerald-50 file:text-emerald-800">
                                </div>
                            </div>

                            <!-- 2. Slot Patokan -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Patokan Slot</label>
                                <select onchange="updateItemProp('${itm.id}', 'slot', parseInt(this.value))" class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-xs bg-white font-medium">
                                    <option value="0" ${itm.slot === 0 ? 'selected' : ''}>Slot #1</option>
                                    <option value="1" ${itm.slot === 1 ? 'selected' : ''}>Slot #2</option>
                                    <option value="2" ${itm.slot === 2 ? 'selected' : ''}>Slot #3</option>
                                    <option value="3" ${itm.slot === 3 ? 'selected' : ''}>Slot #4</option>
                                    <option value="4" ${itm.slot === 4 ? 'selected' : ''}>Slot #5</option>
                                    <option value="5" ${itm.slot === 5 ? 'selected' : ''}>Slot #6</option>
                                </select>
                            </div>

                            <!-- 3. Lebar (Width) -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[10px] uppercase font-bold text-gray-400">Lebar (px)</label>
                                    <span class="text-[9px] text-emerald-700 font-bold">W</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <input type="number" id="item_w_${itm.id}" value="${itmW}" oninput="updateItemDimension('${itm.id}', 'width', parseInt(this.value)||50)" class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold">
                                    <div class="flex flex-col gap-0.5">
                                        <button type="button" onclick="stepItemDimension('${itm.id}', 'width', 20)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none">+</button>
                                        <button type="button" onclick="stepItemDimension('${itm.id}', 'width', -20)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none">-</button>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Panjang / Tinggi (Height) -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[10px] uppercase font-bold text-gray-400">Panjang (px)</label>
                                    <span class="text-[9px] text-emerald-700 font-bold">H</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <input type="number" id="item_h_${itm.id}" value="${itmH}" oninput="updateItemDimension('${itm.id}', 'height', parseInt(this.value)||50)" class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold">
                                    <div class="flex flex-col gap-0.5">
                                        <button type="button" onclick="stepItemDimension('${itm.id}', 'height', 20)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none">+</button>
                                        <button type="button" onclick="stepItemDimension('${itm.id}', 'height', -20)" class="px-1 py-0.5 bg-stone-200 hover:bg-stone-300 text-[9px] font-bold rounded leading-none">-</button>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Geser X -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                <input type="number" id="item_x_${itm.id}" value="${itm.x || 0}" oninput="updateItemProp('${itm.id}', 'x', parseInt(this.value)||0)" class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold">
                            </div>

                            <!-- 6. Geser Y -->
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                <input type="number" id="item_y_${itm.id}" value="${itm.y || 0}" oninput="updateItemProp('${itm.id}', 'y', parseInt(this.value)||0)" class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold">
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            renderPreviewButtons();
        }

        function addNewTemplateItem() {
            const nextIdx = templateItems.length + 1;
            const defaultImages = ['./gambar/ketupat.webp', './gambar/lampu.webp', './gambar/rama.png'];
            const defaultImg = defaultImages[(nextIdx - 1) % defaultImages.length];

            const newItem = {
                id: 'item_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                name: 'Item ' + nextIdx + ' (Stiker)',
                src: defaultImg,
                width: 320,
                height: 320,
                size: 320,
                x: 0,
                y: 0,
                slot: (nextIdx - 1) % 6
            };

            templateItems.push(newItem);
            renderTemplateItems();
            selectItemForDrag(newItem.id);
        }

        function removeTemplateItem(id) {
            templateItems = templateItems.filter(item => item.id !== id);
            selectedTargets = selectedTargets.filter(t => !(t.type === 'item' && t.id === id));
            if (selectedTargets.length === 0 && photoSlots.length > 0) {
                selectedTargets = [{ type: 'slot', id: photoSlots[0].id }];
            }
            selectedTarget = selectedTargets[0] || { type: 'none', id: null };
            renderTemplateItems();
            updateLivePreview();
        }

        function updateItemProp(id, prop, val) {
            const itm = templateItems.find(item => item.id === id);
            if (itm) {
                itm[prop] = val;
                updateLivePreview();
            }
        }

        function updateItemDimension(id, dim, val) {
            const itm = templateItems.find(item => item.id === id);
            if (itm) {
                itm[dim] = Math.max(30, val);
                itm.size = Math.max(itm.width || 300, itm.height || 300);
                updateLivePreview();
            }
        }

        function stepItemDimension(id, dim, delta) {
            const itm = templateItems.find(item => item.id === id);
            if (itm) {
                const currentVal = parseInt(itm[dim]) || parseInt(itm.size) || 300;
                const nextVal = Math.max(30, currentVal + delta);
                itm[dim] = nextVal;
                itm.size = Math.max(itm.width || 300, itm.height || 300);
                
                const inputEl = document.getElementById((dim === 'width' ? 'item_w_' : 'item_h_') + id);
                if (inputEl) inputEl.value = nextVal;
                
                updateLivePreview();
            }
        }

        function handleItemFileUpload(id, input) {
            const file = input.files && input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const itm = templateItems.find(item => item.id === id);
                if (itm) {
                    itm.src = e.target.result;
                    itm.file = file;
                    const thumb = document.getElementById('thumb-' + id);
                    if (thumb) thumb.src = e.target.result;
                    // Preload into cache
                    const img = new Image();
                    img.onload = () => updateLivePreview();
                    img.src = e.target.result;
                    itemImageCache[e.target.result] = img;
                    updateLivePreview();
                }
            };
            reader.readAsDataURL(file);
        }

        // Render buttons above canvas for quick selection
        function renderPreviewButtons() {
            const buttonsContainer = document.getElementById('preview-item-buttons');
            if (!buttonsContainer) return;

            let html = '';
            
            // Photo slots badges
            photoSlots.forEach((slot, idx) => {
                const isSelected = isTargetSelected('slot', slot.id);
                html += `
                    <button type="button" onclick="selectSlotForDrag('${slot.id}', event.shiftKey || event.ctrlKey)" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 shadow-sm ${isSelected ? 'bg-amber-500 text-stone-950 font-black border-2 border-amber-600 scale-105 shadow-md' : 'bg-white text-stone-800 border border-amber-300 hover:bg-amber-50'}">
                        <span>📸</span>
                        <span>${slot.name || 'Foto #' + (idx + 1)} (${slot.width}x${slot.height})</span>
                        ${isSelected ? '<span class="text-[9px] bg-amber-950 text-white rounded-full px-1.5 py-0.2">✓</span>' : ''}
                    </button>
                `;
            });

            // Sticker items badges
            templateItems.forEach((itm, idx) => {
                const isSelected = isTargetSelected('item', itm.id);
                const itmW = itm.width || itm.size || 300;
                const itmH = itm.height || itm.size || 300;
                html += `
                    <button type="button" onclick="selectItemForDrag('${itm.id}', event.shiftKey || event.ctrlKey)" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 shadow-sm ${isSelected ? 'bg-emerald-600 text-white font-black border-2 border-emerald-700 scale-105 shadow-md' : 'bg-stone-100 text-stone-700 border border-stone-200 hover:bg-stone-200'}">
                        <span>✨</span>
                        <span>${itm.name || 'Item ' + (idx + 1)} (${itmW}x${itmH})</span>
                        ${isSelected ? '<span class="text-[9px] bg-emerald-950 text-white rounded-full px-1.5 py-0.2">✓</span>' : ''}
                    </button>
                `;
            });

            buttonsContainer.innerHTML = html || '<span class="text-xs text-stone-400 italic">Belum ada slot / item</span>';
        }

        // Handle Outer Background Image Upload
        const outerInput = document.getElementById('outer-input');
        if (outerInput) {
            outerInput.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (re) => {
                        const img = new Image();
                        img.onload = () => {
                            outerPreviewImage = img;
                            updateLivePreview();
                        };
                        img.src = re.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // ================= LIVE PREVIEW MONITOR RENDERING =================
        function updateLivePreview() {
            const sizeType = form.sizeType ? form.sizeType.value : 'a5_6grid';
            const is6Grid = sizeType === 'a5_6grid';
            const isA5 = sizeType.includes('a5') || sizeType.includes('4r');

            const outerLabel = document.getElementById('outer-label');
            if (outerLabel) {
                outerLabel.innerHTML = isA5 
                    ? `Tema Luar Utama (A5: 1748x2480 px) <span class="text-red-500">*</span>` 
                    : `Tema Luar Utama (Strip: 1080x1920 px) <span class="text-red-500">*</span>`;
            }

            canvas.width = isA5 ? 1748 : 1080;
            canvas.height = isA5 ? 2480 : 1920;
            slotBounds = {};
            itemBounds = {};
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#FFFDF5';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const isOverlay = form.overlayMode.checked;

            // 1. Draw background image (if not overlay)
            if (!isOverlay && outerPreviewImage && outerPreviewImage.complete) {
                ctx.drawImage(outerPreviewImage, 0, 0, canvas.width, canvas.height);
            }

            // 2. Draw all Photo Slots (Tempat Foto Persegi Panjang)
            photoSlots.forEach((slot, idx) => {
                const sx = parseInt(slot.x) || 0;
                const sy = parseInt(slot.y) || 0;
                const sw = parseInt(slot.width) || 760;
                const sh = parseInt(slot.height) || 372;
                const sRadius = parseInt(slot.radius !== undefined ? slot.radius : 20);

                slotBounds[slot.id] = {
                    type: 'slot',
                    id: slot.id,
                    name: slot.name || ('Foto #' + (idx + 1)),
                    x: sx,
                    y: sy,
                    width: sw,
                    height: sh
                };

                // Draw photo placeholder box
                ctx.save();
                ctx.beginPath();
                if (ctx.roundRect) ctx.roundRect(sx, sy, sw, sh, sRadius);
                else ctx.rect(sx, sy, sw, sh);

                // Gradient / soft fill
                const grad = ctx.createLinearGradient(sx, sy, sx + sw, sy + sh);
                grad.addColorStop(0, '#E2E8F0');
                grad.addColorStop(1, '#CBD5E1');
                ctx.fillStyle = grad;
                ctx.fill();

                // Frame stroke (Gold)
                ctx.strokeStyle = '#D4AF37';
                ctx.lineWidth = 6;
                ctx.stroke();

                // Inner crosshair & slot label
                ctx.strokeStyle = 'rgba(212, 175, 55, 0.3)';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(sx + 30, sy + sh / 2);
                ctx.lineTo(sx + sw - 30, sy + sh / 2);
                ctx.moveTo(sx + sw / 2, sy + 30);
                ctx.lineTo(sx + sw / 2, sy + sh - 30);
                ctx.stroke();

                // Camera icon & slot text
                ctx.fillStyle = '#0F172A';
                ctx.font = 'bold 36px sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(`📸 ${slot.name || 'Foto #' + (idx + 1)}`, sx + sw / 2, sy + sh / 2 - 14);

                // Dimension text
                ctx.font = 'bold 24px monospace';
                ctx.fillStyle = '#475569';
                ctx.fillText(`${sw} x ${sh} px (Persegi Panjang)`, sx + sw / 2, sy + sh / 2 + 26);

                ctx.restore();
            });

            // 3. Draw Dynamic Sticker Items
            templateItems.forEach(itm => {
                const img = getItemImage(itm.src);
                if (!img || !img.complete) return;

                const width = parseInt(itm.width) || parseInt(itm.size) || 300;
                const height = parseInt(itm.height) || parseInt(itm.size) || 300;
                
                // Reference slot base position if applicable
                const refSlotIdx = parseInt(itm.slot) || 0;
                const refSlot = photoSlots[refSlotIdx] || photoSlots[0];
                let baseX = 0;
                let baseY = 0;
                let baseW = canvas.width;
                let baseH = canvas.height;

                if (refSlot) {
                    baseX = refSlot.x;
                    baseY = refSlot.y;
                    baseW = refSlot.width;
                    baseH = refSlot.height;
                }

                const x = baseX + baseW - width + (parseInt(itm.x) || 0);
                const y = baseY + baseH - height + (parseInt(itm.y) || 0);

                itemBounds[itm.id] = {
                    type: 'item',
                    id: itm.id,
                    name: itm.name || 'Item',
                    x: x,
                    y: y,
                    width: width,
                    height: height,
                    xOff: itm.x || 0,
                    yOff: itm.y || 0
                };

                ctx.drawImage(img, x, y, width, height);
            });

            // 4. Draw overlay theme (if overlayMode)
            if (isOverlay && outerPreviewImage && outerPreviewImage.complete) {
                ctx.drawImage(outerPreviewImage, 0, 0, canvas.width, canvas.height);
            }

            // 5. Highlight All Selected Targets
            const allBounds = { ...slotBounds, ...itemBounds };
            let groupMinX = Infinity, groupMinY = Infinity, groupMaxX = -Infinity, groupMaxY = -Infinity;

            selectedTargets.forEach(tgt => {
                const b = allBounds[tgt.id];
                if (!b) return;

                groupMinX = Math.min(groupMinX, b.x);
                groupMinY = Math.min(groupMinY, b.y);
                groupMaxX = Math.max(groupMaxX, b.x + b.width);
                groupMaxY = Math.max(groupMaxY, b.y + b.height);

                const isDraggingThis = activeDraggedTarget && activeDraggedTarget.id === b.id;
                const isSlot = b.type === 'slot';

                ctx.save();
                ctx.strokeStyle = isDraggingThis ? '#3B82F6' : (isSlot ? '#F59E0B' : '#10B981');
                ctx.lineWidth = 6;
                ctx.setLineDash([16, 10]);
                ctx.strokeRect(b.x, b.y, b.width, b.height);

                // Single Selection corner resize handles
                if (selectedTargets.length === 1) {
                    ctx.lineWidth = 4;
                    ctx.setLineDash([]);
                    
                    const corners = [
                        { x: b.x, y: b.y, isResize: false },
                        { x: b.x + b.width, y: b.y, isResize: false },
                        { x: b.x, y: b.y + b.height, isResize: false },
                        { x: b.x + b.width, y: b.y + b.height, isResize: true }
                    ];

                    corners.forEach(c => {
                        ctx.beginPath();
                        if (c.isResize) {
                            ctx.fillStyle = isDraggingThis && dragMode === 'resize' ? '#3B82F6' : '#F59E0B';
                            ctx.strokeStyle = '#FFFFFF';
                            ctx.arc(c.x, c.y, 22, 0, Math.PI * 2);
                            ctx.fill();
                            ctx.stroke();

                            // Diagonal resize arrow
                            ctx.strokeStyle = '#FFFFFF';
                            ctx.lineWidth = 3;
                            ctx.beginPath();
                            ctx.moveTo(c.x - 8, c.y - 8);
                            ctx.lineTo(c.x + 8, c.y + 8);
                            ctx.moveTo(c.x + 8, c.y + 2);
                            ctx.lineTo(c.x + 8, c.y + 8);
                            ctx.lineTo(c.x + 2, c.y + 8);
                            ctx.stroke();
                        } else {
                            ctx.fillStyle = isSlot ? '#D97706' : '#059669';
                            ctx.strokeStyle = '#FFFFFF';
                            ctx.arc(c.x, c.y, 14, 0, Math.PI * 2);
                            ctx.fill();
                            ctx.stroke();
                        }
                    });
                }

                // Tag Pill Badge
                const tagText = `${isSlot ? '📸' : '✨'} ${b.name} (${b.width}x${b.height}px | X:${b.x}, Y:${b.y})`;
                ctx.font = 'bold 28px sans-serif';
                const tagWidth = ctx.measureText(tagText).width + 40;
                const tagHeight = 52;
                const tagX = Math.max(10, Math.min(canvas.width - tagWidth - 10, b.x));
                const tagY = Math.max(tagHeight + 10, b.y - 14);

                ctx.fillStyle = isSlot ? 'rgba(120, 53, 15, 0.95)' : 'rgba(27, 67, 50, 0.95)';
                ctx.beginPath();
                if (ctx.roundRect) ctx.roundRect(tagX, tagY - tagHeight, tagWidth, tagHeight, 16);
                else ctx.rect(tagX, tagY - tagHeight, tagWidth, tagHeight);
                ctx.fill();

                ctx.strokeStyle = isDraggingThis ? '#3B82F6' : '#D4AF37';
                ctx.lineWidth = 3;
                ctx.stroke();

                ctx.fillStyle = '#FFFDF5';
                ctx.textAlign = 'left';
                ctx.fillText(tagText, tagX + 20, tagY - 16);
                ctx.restore();
            });

            // 6. Draw Group Selection Box if Multiple Items are Selected
            if (selectedTargets.length > 1 && groupMinX !== Infinity) {
                const pad = 15;
                const gx = groupMinX - pad;
                const gy = groupMinY - pad;
                const gw = (groupMaxX - groupMinX) + (2 * pad);
                const gh = (groupMaxY - groupMinY) + (2 * pad);

                ctx.save();
                ctx.strokeStyle = '#3B82F6';
                ctx.lineWidth = 5;
                ctx.setLineDash([20, 10]);
                ctx.strokeRect(gx, gy, gw, gh);

                // Multi-selection badge header
                const groupTag = `🎯 ${selectedTargets.length} OBJEK TERPILIH (Tarik untuk geser bersama)`;
                ctx.font = 'bold 30px sans-serif';
                const gTagWidth = ctx.measureText(groupTag).width + 40;
                const gTagHeight = 56;
                const gTagX = Math.max(10, Math.min(canvas.width - gTagWidth - 10, gx));
                const gTagY = Math.max(gTagHeight + 10, gy - 16);

                ctx.fillStyle = '#1E40AF';
                ctx.beginPath();
                if (ctx.roundRect) ctx.roundRect(gTagX, gTagY - gTagHeight, gTagWidth, gTagHeight, 18);
                else ctx.rect(gTagX, gTagY - gTagHeight, gTagWidth, gTagHeight);
                ctx.fill();

                ctx.strokeStyle = '#93C5FD';
                ctx.lineWidth = 3;
                ctx.stroke();

                ctx.fillStyle = '#FFFFFF';
                ctx.textAlign = 'left';
                ctx.fillText(groupTag, gTagX + 20, gTagY - 16);
                ctx.restore();
            }

            // 7. Draw Marquee Box Selection (Kotak Kursor Seleksi Area)
            if (dragMode === 'marquee') {
                const bx = Math.min(marqueeStart.x, marqueeEnd.x);
                const by = Math.min(marqueeStart.y, marqueeEnd.y);
                const bw = Math.abs(marqueeEnd.x - marqueeStart.x);
                const bh = Math.abs(marqueeEnd.y - marqueeStart.y);

                ctx.save();
                ctx.fillStyle = 'rgba(59, 130, 246, 0.22)';
                ctx.fillRect(bx, by, bw, bh);
                
                ctx.strokeStyle = '#2563EB';
                ctx.lineWidth = 4;
                ctx.setLineDash([14, 8]);
                ctx.strokeRect(bx, by, bw, bh);

                // Marquee counter tag
                ctx.fillStyle = '#1E3A8A';
                ctx.fillRect(bx, Math.max(10, by - 40), 220, 40);
                ctx.fillStyle = '#FFFFFF';
                ctx.font = 'bold 22px sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText(`📦 Seleksi: ${selectedTargets.length} objek`, bx + 12, Math.max(10, by - 40) + 28);
                ctx.restore();
            }
        }

        // ================= CANVAS DRAG, RESIZE & MARQUEE INTERACTION =================
        function getCanvasMousePos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function hitTestResizeHandle(pos) {
            // Only active if 1 single target is selected
            if (selectedTargets.length === 1) {
                const allBounds = { ...slotBounds, ...itemBounds };
                const b = allBounds[selectedTargets[0].id];
                if (b) {
                    const handleX = b.x + b.width;
                    const handleY = b.y + b.height;
                    const dist = Math.hypot(pos.x - handleX, pos.y - handleY);
                    if (dist <= 50) {
                        return { type: b.type, id: b.id };
                    }
                }
            }
            return null;
        }

        function hitTestTarget(pos) {
            // Check items first (topmost), then photo slots
            const itemIds = Object.keys(itemBounds).reverse();
            for (const id of itemIds) {
                const b = itemBounds[id];
                if (b && pos.x >= b.x && pos.x <= (b.x + b.width) && pos.y >= b.y && pos.y <= (b.y + b.height)) {
                    return { type: 'item', id: b.id };
                }
            }

            const slotIds = Object.keys(slotBounds).reverse();
            for (const id of slotIds) {
                const b = slotBounds[id];
                if (b && pos.x >= b.x && pos.x <= (b.x + b.width) && pos.y >= b.y && pos.y <= (b.y + b.height)) {
                    return { type: 'slot', id: b.id };
                }
            }
            return null;
        }

        const startCanvasDrag = (e) => {
            const pos = getCanvasMousePos(e);
            const isMultiKey = e.shiftKey || e.ctrlKey;
            
            // 1. Check if clicking on bottom-right resize handle (Single item resize)
            const resizeHit = hitTestResizeHandle(pos);
            if (resizeHit) {
                saveHistoryState('Ubah Ukuran');
                activeDraggedTarget = resizeHit;
                dragMode = 'resize';
                dragStartMouseX = pos.x;
                dragStartMouseY = pos.y;

                if (resizeHit.type === 'slot') {
                    const slot = photoSlots.find(s => s.id === resizeHit.id);
                    dragInitialW = slot ? (parseInt(slot.width) || 760) : 760;
                    dragInitialH = slot ? (parseInt(slot.height) || 372) : 372;
                } else {
                    const itm = templateItems.find(item => item.id === resizeHit.id);
                    dragInitialW = itm ? (parseInt(itm.width) || parseInt(itm.size) || 300) : 300;
                    dragInitialH = itm ? (parseInt(itm.height) || parseInt(itm.size) || 300) : 300;
                }

                canvas.style.cursor = 'nwse-resize';
                if (e.cancelable) e.preventDefault();
                return;
            }

            // 2. Check if clicking inside an item or photo slot
            const hit = hitTestTarget(pos);
            if (hit) {
                saveHistoryState('Geser Objek');
                if (isMultiKey) {
                    // Toggle in selection
                    const idx = selectedTargets.findIndex(t => t.type === hit.type && t.id === hit.id);
                    if (idx >= 0) selectedTargets.splice(idx, 1);
                    else selectedTargets.push(hit);
                } else {
                    // If clicking an object that is NOT already in the selection, make it the sole selected object
                    if (!isTargetSelected(hit.type, hit.id)) {
                        selectedTargets = [hit];
                    }
                    // If already in selection, keep the whole group selection!
                }

                activeDraggedTarget = hit;
                dragMode = 'move';
                dragStartMouseX = pos.x;
                dragStartMouseY = pos.y;

                // Record initial positions for ALL selected targets to move them synchronously
                dragInitialPositions = {};
                selectedTargets.forEach(tgt => {
                    if (tgt.type === 'slot') {
                        const slot = photoSlots.find(s => s.id === tgt.id);
                        if (slot) {
                            dragInitialPositions[tgt.id] = {
                                x: parseInt(slot.x) || 0,
                                y: parseInt(slot.y) || 0,
                                type: 'slot'
                            };
                        }
                    } else {
                        const itm = templateItems.find(item => item.id === tgt.id);
                        if (itm) {
                            dragInitialPositions[tgt.id] = {
                                itmXOff: parseInt(itm.x) || 0,
                                itmYOff: parseInt(itm.y) || 0,
                                type: 'item'
                            };
                        }
                    }
                });

                renderPhotoSlots();
                renderTemplateItems();
                canvas.style.cursor = 'grabbing';
                if (e.cancelable) e.preventDefault();
                return;
            }

            // 3. Clicked on EMPTY canvas space -> Start Marquee Box Selection (Kotak Kursor)
            if (!isMultiKey) {
                selectedTargets = [];
            }
            dragMode = 'marquee';
            marqueeStart = { x: pos.x, y: pos.y };
            marqueeEnd = { x: pos.x, y: pos.y };
            canvas.style.cursor = 'crosshair';

            renderPhotoSlots();
            renderTemplateItems();
            updateLivePreview();
            if (e.cancelable) e.preventDefault();
        };

        const onCanvasDragMove = (e) => {
            const pos = getCanvasMousePos(e);

            if (dragMode === 'marquee') {
                marqueeEnd = { x: pos.x, y: pos.y };
                
                // Calculate selection rectangle
                const minX = Math.min(marqueeStart.x, marqueeEnd.x);
                const maxX = Math.max(marqueeStart.x, marqueeEnd.x);
                const minY = Math.min(marqueeStart.y, marqueeEnd.y);
                const maxY = Math.max(marqueeStart.y, marqueeEnd.y);

                // Select all slots & items that intersect with this marquee box
                const allBounds = { ...slotBounds, ...itemBounds };
                const intersected = [];
                for (const k in allBounds) {
                    const b = allBounds[k];
                    if (b && b.x < maxX && (b.x + b.width) > minX && b.y < maxY && (b.y + b.height) > minY) {
                        intersected.push({ type: b.type, id: b.id });
                    }
                }

                selectedTargets = intersected;
                selectedTarget = selectedTargets[0] || { type: 'none', id: null };
                
                const statusEl = document.getElementById('drag-coord-status');
                if (statusEl) {
                    statusEl.textContent = `📦 KOTAK SELEKSI: ${selectedTargets.length} objek di dalam area`;
                }

                renderPhotoSlots();
                renderTemplateItems();
                updateLivePreview();
                if (e.cancelable) e.preventDefault();
                return;
            }

            if (dragMode === 'resize' && activeDraggedTarget) {
                const isSlot = activeDraggedTarget.type === 'slot';
                const deltaW = Math.round(pos.x - dragStartMouseX);
                const deltaH = Math.round(pos.y - dragStartMouseY);
                
                let newW = Math.max(50, dragInitialW + deltaW);
                let newH = Math.max(50, dragInitialH + deltaH);

                // For Photo Slots: Enforce Rectangle & Proportional Lock
                if (isSlot) {
                    const slot = photoSlots.find(s => s.id === activeDraggedTarget.id);
                    const isLocked = slot ? (slot.lockRatio !== false) : true;

                    if (isLocked && dragInitialW > 0 && dragInitialH > 0) {
                        // Proportional scaling: maintain exact ratio multiple
                        const ratio = dragInitialW / dragInitialH;
                        newW = Math.max(60, dragInitialW + deltaW);
                        newH = Math.max(40, Math.round(newW / ratio));
                    }

                    // Guarantee rectangle constraint (W != H)
                    if (Math.abs(newW - newH) < 15) {
                        if (newW >= newH) newH = Math.max(40, newW - 25);
                        else newW = Math.max(40, newH + 25);
                    }

                    if (slot) {
                        slot.width = newW;
                        slot.height = newH;
                        const inputW = document.getElementById('slot_w_' + slot.id);
                        const inputH = document.getElementById('slot_h_' + slot.id);
                        if (inputW) inputW.value = newW;
                        if (inputH) inputH.value = newH;
                        
                        const statusEl = document.getElementById('drag-coord-status');
                        if (statusEl) {
                            statusEl.textContent = `📸 ${slot.name.toUpperCase()} UKURAN: ${newW}x${newH}px (${getRectangleTypeLabel(newW, newH)})`;
                        }
                    }
                } else {
                    const itm = templateItems.find(item => item.id === activeDraggedTarget.id);
                    if (itm) {
                        itm.width = newW;
                        itm.height = newH;
                        itm.size = Math.max(newW, newH);
                        const inputW = document.getElementById('item_w_' + itm.id);
                        const inputH = document.getElementById('item_h_' + itm.id);
                        if (inputW) inputW.value = newW;
                        if (inputH) inputH.value = newH;

                        const statusEl = document.getElementById('drag-coord-status');
                        if (statusEl) {
                            statusEl.textContent = `✨ ${(itm.name || 'ITEM').toUpperCase()} UKURAN: ${newW}x${newH}px`;
                        }
                    }
                }

                updateLivePreview();
                if (e.cancelable) e.preventDefault();
                return;
            }

            if (dragMode === 'move' && selectedTargets.length > 0) {
                const deltaX = Math.round(pos.x - dragStartMouseX);
                const deltaY = Math.round(pos.y - dragStartMouseY);

                // Move ALL selected targets together
                selectedTargets.forEach(tgt => {
                    const init = dragInitialPositions[tgt.id];
                    if (!init) return;

                    if (tgt.type === 'slot') {
                        const slot = photoSlots.find(s => s.id === tgt.id);
                        if (slot) {
                            slot.x = init.x + deltaX;
                            slot.y = init.y + deltaY;
                            const inputX = document.getElementById('slot_x_' + slot.id);
                            const inputY = document.getElementById('slot_y_' + slot.id);
                            if (inputX) inputX.value = slot.x;
                            if (inputY) inputY.value = slot.y;
                        }
                    } else {
                        const itm = templateItems.find(item => item.id === tgt.id);
                        if (itm) {
                            itm.x = (init.itmXOff || 0) + deltaX;
                            itm.y = (init.itmYOff || 0) + deltaY;
                            const inputX = document.getElementById('item_x_' + itm.id);
                            const inputY = document.getElementById('item_y_' + itm.id);
                            if (inputX) inputX.value = itm.x;
                            if (inputY) inputY.value = itm.y;
                        }
                    }
                });

                const statusEl = document.getElementById('drag-coord-status');
                if (statusEl) {
                    if (selectedTargets.length > 1) {
                        statusEl.textContent = `🎯 ${selectedTargets.length} OBJEK DIGESER BERSAMA (ΔX: ${deltaX}, ΔY: ${deltaY})`;
                    } else {
                        const single = selectedTargets[0];
                        if (single.type === 'slot') {
                            const slot = photoSlots.find(s => s.id === single.id);
                            if (slot) statusEl.textContent = `📸 ${slot.name.toUpperCase()} X:${slot.x} Y:${slot.y}`;
                        } else {
                            const itm = templateItems.find(i => i.id === single.id);
                            if (itm) statusEl.textContent = `✨ ${(itm.name || 'ITEM').toUpperCase()} X:${itm.x} Y:${itm.y}`;
                        }
                    }
                }

                updateLivePreview();
                if (e.cancelable) e.preventDefault();
                return;
            }

            // Hover state checks
            const resizeHit = hitTestResizeHandle(pos);
            if (resizeHit) {
                canvas.style.cursor = 'nwse-resize';
            } else {
                const hit = hitTestTarget(pos);
                if (hit) {
                    canvas.style.cursor = isTargetSelected(hit.type, hit.id) ? 'grab' : 'pointer';
                } else {
                    canvas.style.cursor = 'crosshair';
                }
            }
        };

        const endCanvasDrag = () => {
            if (dragMode) {
                dragMode = null;
                activeDraggedTarget = null;
                canvas.style.cursor = 'default';
                renderPhotoSlots();
                renderTemplateItems();
                updateLivePreview();
            }
        };

        canvas.addEventListener('mousedown', startCanvasDrag);
        window.addEventListener('mousemove', onCanvasDragMove);
        window.addEventListener('mouseup', endCanvasDrag);

        canvas.addEventListener('touchstart', startCanvasDrag, { passive: false });
        window.addEventListener('touchmove', onCanvasDragMove, { passive: false });
        window.addEventListener('touchend', endCanvasDrag);
        window.addEventListener('touchcancel', endCanvasDrag);

        // ================= DIRECT DRAG & DROP FILE ONTO CANVAS MONITOR =================
        const dropContainer = document.getElementById('canvas-dropzone-container');
        const dropOverlay = document.getElementById('canvas-drop-overlay');

        if (dropContainer && dropOverlay) {
            let dragCounter = 0;

            const isImageTransfer = (e) => {
                if (!e.dataTransfer) return false;
                if (e.dataTransfer.types) {
                    for (let i = 0; i < e.dataTransfer.types.length; i++) {
                        if (e.dataTransfer.types[i] === 'Files') return true;
                    }
                }
                return false;
            };

            dropContainer.addEventListener('dragenter', (e) => {
                if (!isImageTransfer(e)) return;
                e.preventDefault();
                e.stopPropagation();
                dragCounter++;
                dropOverlay.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                dropOverlay.classList.add('opacity-100', 'scale-100');
            });

            dropContainer.addEventListener('dragover', (e) => {
                if (!isImageTransfer(e)) return;
                e.preventDefault();
                e.stopPropagation();
                e.dataTransfer.dropEffect = 'copy';
            });

            dropContainer.addEventListener('dragleave', (e) => {
                if (!isImageTransfer(e)) return;
                e.preventDefault();
                e.stopPropagation();
                dragCounter--;
                if (dragCounter <= 0) {
                    dragCounter = 0;
                    dropOverlay.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    dropOverlay.classList.remove('opacity-100', 'scale-100');
                }
            });

            dropContainer.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dragCounter = 0;
                dropOverlay.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                dropOverlay.classList.remove('opacity-100', 'scale-100');

                const files = e.dataTransfer ? Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')) : [];
                if (files.length === 0) return;

                const pos = getCanvasMousePos(e);
                saveHistoryState('Drop ' + files.length + ' Item Baru');

                files.forEach((file, fIdx) => {
                    const reader = new FileReader();
                    reader.onload = (re) => {
                        const dataUrl = re.target.result;
                        const img = new Image();
                        img.onload = () => {
                            // Preload into cache
                            itemImageCache[dataUrl] = img;

                            // Calculate natural aspect ratio & proper default dimensions (anti-penyet)
                            const naturalW = img.naturalWidth || 300;
                            const naturalH = img.naturalHeight || 300;
                            const ratio = naturalW / naturalH;

                            // Standard baseline size: 350px width, scale height according to exact ratio
                            const targetW = Math.min(600, Math.max(150, Math.round(naturalW > 700 ? 400 : naturalW)));
                            const targetH = Math.round(targetW / ratio);

                            // Center the dropped item at drop position with slight stagger if multiple files
                            const stagger = fIdx * 30;
                            const placedCanvasX = Math.round(pos.x - (targetW / 2)) + stagger;
                            const placedCanvasY = Math.round(pos.y - (targetH / 2)) + stagger;

                            // Reference slot base position calculation (Slot #1 default)
                            const refSlot = photoSlots[0];
                            let offX = placedCanvasX;
                            let offY = placedCanvasY;
                            if (refSlot) {
                                offX = placedCanvasX - (refSlot.x + refSlot.width - targetW);
                                offY = placedCanvasY - (refSlot.y + refSlot.height - targetH);
                            }

                            const cleanName = file.name.replace(/\.[^/.]+$/, "").substring(0, 30);
                            const newItem = {
                                id: 'item_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                                name: cleanName || ('Item ' + (templateItems.length + 1)),
                                src: dataUrl,
                                file: file,
                                width: targetW,
                                height: targetH,
                                size: Math.max(targetW, targetH),
                                x: offX,
                                y: offY,
                                slot: 0
                            };

                            templateItems.push(newItem);
                            selectedTargets = [{ type: 'item', id: newItem.id }];
                            selectedTarget = selectedTargets[0];

                            renderTemplateItems();
                            updateLivePreview();

                            const statusEl = document.getElementById('drag-coord-status');
                            if (statusEl) {
                                statusEl.textContent = `✨ Item "${newItem.name}" (${targetW}x${targetH}px) berhasil ditambahkan dari drop!`;
                            }
                        };
                        img.src = dataUrl;
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Prevent window from opening dropped file in browser tab
            window.addEventListener('dragover', (e) => {
                if (e.dataTransfer && e.dataTransfer.types && Array.from(e.dataTransfer.types).includes('Files')) {
                    e.preventDefault();
                }
            });
            window.addEventListener('drop', (e) => {
                if (e.dataTransfer && e.dataTransfer.types && Array.from(e.dataTransfer.types).includes('Files')) {
                    e.preventDefault();
                }
            });
        }

        // Auto-blur input fields when clicking anywhere outside form fields so canvas shortcuts work immediately
        document.addEventListener('pointerdown', (e) => {
            const isInput = e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT';
            if (!isInput && document.activeElement && (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA')) {
                document.activeElement.blur();
            }
        });

        // Keyboard navigation (Ctrl+Z, Ctrl+Y, Ctrl+C, Ctrl+V, Ctrl+D, Ctrl+X, Delete, Arrow keys, Esc, Ctrl+A)
        window.addEventListener('keydown', (e) => {
            const templateTab = document.getElementById('tab-content-templates');
            if (templateTab && templateTab.classList.contains('hidden')) return;

            const activeEl = document.activeElement;
            const isTyping = activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.tagName === 'SELECT');
            
            const key = (e.key || '').toLowerCase();
            const code = e.code || '';
            const isCtrl = e.ctrlKey || e.metaKey;

            if (isTyping) {
                if (key === 'escape' || code === 'Escape') {
                    activeEl.blur();
                }
                return;
            }

            // 1. Undo / Redo (Ctrl+Z, Ctrl+Y, Ctrl+Shift+Z)
            if (isCtrl && (key === 'z' || code === 'KeyZ')) {
                e.preventDefault();
                if (e.shiftKey) {
                    redo();
                } else {
                    undo();
                }
                return;
            }
            if (isCtrl && (key === 'y' || code === 'KeyY')) {
                e.preventDefault();
                redo();
                return;
            }

            // 2. Copy / Paste / Duplicate / Cut
            if (isCtrl && (key === 'c' || code === 'KeyC')) {
                e.preventDefault();
                copySelected();
                return;
            }
            if (isCtrl && (key === 'v' || code === 'KeyV')) {
                e.preventDefault();
                pasteCopied();
                return;
            }
            if (isCtrl && (key === 'd' || code === 'KeyD')) {
                e.preventDefault();
                duplicateSelected();
                return;
            }
            if (isCtrl && (key === 'x' || code === 'KeyX')) {
                e.preventDefault();
                cutSelected();
                return;
            }

            // 3. Delete / Backspace
            if (key === 'delete' || key === 'backspace' || code === 'Delete' || code === 'Backspace') {
                if (selectedTargets.length > 0) {
                    e.preventDefault();
                    deleteSelected();
                    return;
                }
            }

            // 4. Select All (Ctrl+A)
            if (isCtrl && (key === 'a' || code === 'KeyA')) {
                e.preventDefault();
                selectAllTargets();
                return;
            }

            // 5. Escape (Clear Selection)
            if (key === 'escape' || code === 'Escape') {
                clearSelection();
                return;
            }

            // 6. Arrow Keys (Nudge Movement)
            const step = e.shiftKey ? 50 : 10;
            if (key === 'arrowup' || code === 'ArrowUp') {
                e.preventDefault();
                nudgeSelectedTargets(0, -step);
            } else if (key === 'arrowdown' || code === 'ArrowDown') {
                e.preventDefault();
                nudgeSelectedTargets(0, step);
            } else if (key === 'arrowleft' || code === 'ArrowLeft') {
                e.preventDefault();
                nudgeSelectedTargets(-step, 0);
            } else if (key === 'arrowright' || code === 'ArrowRight') {
                e.preventDefault();
                nudgeSelectedTargets(step, 0);
            }
        });

        document.querySelectorAll('#form-overlay, #form-size-type').forEach(el => {
            el.addEventListener('change', () => {
                updateLivePreview();
            });
        });

        // Initialize Photo Slots & Dynamic Items
        if (photoSlots.length > 0) {
            selectedTargets = [{ type: 'slot', id: photoSlots[0].id }];
            selectedTarget = selectedTargets[0];
        }
        renderPhotoSlots();
        renderTemplateItems();
        updateLivePreview();
        saveHistoryState('Inisialisasi Awal');

        let loadedTemplatesList = [];

        async function fetchTemplates() {
            try {
                const res = await fetch('manage_templates.php?action=list&_t=' + Date.now());
                const data = await res.json();
                loadedTemplatesList = data || [];
                renderTemplates(loadedTemplatesList);
            } catch (e) {
                console.error(e);
            }
        }

        function renderTemplates(templates) {
            if (!templates || templates.length === 0) {
                list.innerHTML = '<p class="text-gray-500 italic col-span-2">Belum ada template.</p>';
                return;
            }

            list.innerHTML = templates.map(t => {
                const is6 = t.sizeType === 'a5_6grid' || t.sizeType === '4r_6grid';
                const isA5 = t.sizeType === 'a5' || is6 || t.sizeType === '4r';
                const isActive = t.active !== false;
                const thumbSrc = t.outer || t.ketupat || t.lampu || t.rama || '';
                const slotCount = (t.photoSlots && Array.isArray(t.photoSlots)) ? t.photoSlots.length : (is6 ? 6 : 3);
                const itemCount = (t.items && Array.isArray(t.items)) ? t.items.length : 3;

                return `
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border ${isActive ? 'border-emerald-200 ring-1 ring-emerald-400/30' : 'border-gray-200 opacity-75'} p-4 transition-all hover:shadow-md flex flex-col justify-between">
                    <div>
                        <div class="aspect-[16/9] bg-slate-900 rounded-xl mb-3 relative overflow-hidden flex items-center justify-center border border-slate-700">
                            ${thumbSrc ? `<img src="${thumbSrc}" class="absolute inset-0 w-full h-full object-cover opacity-60">` : `<span class="text-2xl">🖼️</span>`}
                            <div class="relative z-10 text-center px-2">
                                <span class="text-xs font-black text-amber-300 uppercase drop-shadow-md">${t.name}</span>
                                <div class="mt-1.5 flex flex-wrap items-center justify-center gap-1">
                                    <span class="px-2 py-0.5 ${is6 ? 'bg-amber-400 text-slate-900' : (isA5 ? 'bg-emerald-400 text-slate-900' : 'bg-blue-400 text-slate-900')} rounded-full text-[9px] font-black shadow-sm">
                                        ${is6 ? '📸 A5 (6-Grid)' : (isA5 ? '📄 A5 (3-Strip)' : '📱 Strip (9:16)')}
                                    </span>
                                    <span class="px-2 py-0.5 bg-amber-400/90 text-slate-950 rounded-full text-[9px] font-bold">
                                        📸 ${slotCount} Slot Foto
                                    </span>
                                    <span class="px-2 py-0.5 ${t.overlayMode ? 'bg-emerald-500/80 text-white' : 'bg-slate-700/80 text-slate-200'} rounded-full text-[9px] font-bold">
                                        ${t.overlayMode ? '✓ OVERLAY' : 'BACKGROUND'}
                                    </span>
                                    <span class="px-2 py-0.5 bg-purple-500/80 text-white rounded-full text-[9px] font-bold">
                                        ${itemCount} Item
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Active Toggle Checkbox -->
                        <label class="flex items-center justify-between p-2.5 rounded-xl ${isActive ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-slate-100 border border-slate-200 text-slate-600'} cursor-pointer hover:scale-[1.01] transition-all">
                            <span class="text-[11px] font-bold flex items-center gap-1.5">
                                <span>${isActive ? '✅' : '⚪'}</span> ${isActive ? 'Aktif untuk Pengunjung' : 'Nonaktif (Disembunyikan)'}
                            </span>
                            <input type="checkbox" ${isActive ? 'checked' : ''} onchange="toggleTemplateActive('${t.id}', this.checked)" class="w-4 h-4 accent-emerald-600 cursor-pointer">
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <button onclick="loadTemplateToEditor('${t.id}')" class="py-2 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1 active:scale-95 cursor-pointer">
                            <span>✏️</span> Edit / Sesuaikan
                        </button>
                        <button onclick="deleteTemplate('${t.id}')" class="py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1 active:scale-95 cursor-pointer">
                            <span>🗑️</span> Hapus
                        </button>
                    </div>
                </div>
            `}).join('');
        }

        window.loadTemplateToEditor = function(id) {
            const t = loadedTemplatesList.find(item => item.id === id);
            if (!t) return;

            // Populate form fields
            form.name.value = t.name || '';
            if (form.sizeType) form.sizeType.value = t.sizeType || 'a5_6grid';
            if (form.overlayMode) form.overlayMode.checked = !!t.overlayMode;

            // Populate photo slots
            if (t.photoSlots && Array.isArray(t.photoSlots) && t.photoSlots.length > 0) {
                photoSlots = t.photoSlots.map((s, idx) => ({
                    id: s.id || ('slot_' + (idx + 1)),
                    name: s.name || ('Foto #' + (idx + 1)),
                    x: parseInt(s.x) || 0,
                    y: parseInt(s.y) || 0,
                    width: parseInt(s.width) || 760,
                    height: parseInt(s.height) || 372,
                    radius: parseInt(s.radius !== undefined ? s.radius : 20)
                }));
            } else {
                // Generate default slots according to sizeType
                applySlotPreset(t.sizeType === 'a5_6grid' ? 'a5_6grid' : (t.sizeType === 'a5' ? 'a5_3strip' : 'a5_6grid'));
            }

            // Populate items array
            if (t.items && Array.isArray(t.items) && t.items.length > 0) {
                templateItems = t.items.map((itm, idx) => ({
                    id: itm.id || ('item_' + (idx + 1)),
                    name: itm.name || ('Item ' + (idx + 1)),
                    src: itm.src || '',
                    width: parseInt(itm.width) || parseInt(itm.size) || 300,
                    height: parseInt(itm.height) || parseInt(itm.size) || 300,
                    size: parseInt(itm.size) || 300,
                    x: parseInt(itm.x) || 0,
                    y: parseInt(itm.y) || 0,
                    slot: parseInt(itm.slot) || 0
                }));
            }

            // Load outer image preview if exists
            if (t.outer) {
                const img = new Image();
                img.onload = () => {
                    outerPreviewImage = img;
                    updateLivePreview();
                };
                img.src = t.outer;
            } else {
                outerPreviewImage = null;
            }

            selectedTarget = { type: 'slot', id: photoSlots[0] ? photoSlots[0].id : null };
            renderPhotoSlots();
            renderTemplateItems();
            updateLivePreview();

            // Scroll to editor
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        window.toggleTemplateActive = async function(id, active) {
            try {
                const res = await fetch('manage_templates.php?action=toggle_active', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, active })
                });
                const data = await res.json();
                if (data.success) {
                    const t = loadedTemplatesList.find(item => item.id === id);
                    if (t) t.active = active;
                    renderTemplates(loadedTemplatesList);
                } else {
                    alert('Gagal mengubah status: ' + (data.error || 'Server error'));
                }
            } catch (err) {
                alert('Gagal menghubungi server');
            }
        };

        form.onsubmit = (e) => {
            e.preventDefault();

            // Validation 1: At least 1 photo slot
            if (photoSlots.length === 0) {
                alert('Template harus memiliki minimal 1 tempat / slot foto!');
                return;
            }

            // Validation 2: Ensure all photo slots are rectangular (W != H)
            for (let i = 0; i < photoSlots.length; i++) {
                const s = photoSlots[i];
                if (!isRectangle(s.width, s.height)) {
                    alert(`Slot "${s.name}" tidak boleh bujursangkar (${s.width}x${s.height}px)! Harap pastikan berbentuk persegi panjang.`);
                    selectSlotForDrag(s.id);
                    return;
                }
            }

            const formData = new FormData(form);
            if (form.overlayMode.checked) formData.set('overlayMode', 'true');
            
            // Serialize Photo Slots (Tempat Foto Persegi Panjang)
            formData.set('photo_slots_json', JSON.stringify(photoSlots));

            // Serialize items metadata with width, height, size, x, y, slot
            const serializedItems = templateItems.map((itm, idx) => ({
                id: itm.id,
                name: itm.name || ('Item ' + (idx + 1)),
                src: (itm.src && !itm.src.startsWith('data:')) ? itm.src : '',
                width: itm.width || itm.size || 300,
                height: itm.height || itm.size || 300,
                size: itm.size || Math.max(itm.width || 300, itm.height || 300),
                x: itm.x || 0,
                y: itm.y || 0,
                slot: itm.slot || 0
            }));
            formData.set('items_json', JSON.stringify(serializedItems));

            // Append each item's file
            templateItems.forEach((itm, idx) => {
                if (itm.file) {
                    formData.append('item_file_' + idx, itm.file);
                }
            });

            // Also set legacy fields for backward compatibility
            if (templateItems[0]) {
                formData.set('ketupat_x', templateItems[0].x || 120);
                formData.set('ketupat_y', templateItems[0].y || 150);
                formData.set('ketupat_size', templateItems[0].width || templateItems[0].size || 350);
            }
            if (templateItems[1]) {
                formData.set('lampu_x', templateItems[1].x || -100);
                formData.set('lampu_y', templateItems[1].y || 140);
                formData.set('lampu_size', templateItems[1].width || templateItems[1].size || 300);
            }
            if (templateItems[2]) {
                formData.set('rama_x', templateItems[2].x || 150);
                formData.set('rama_y', templateItems[2].y || 300);
                formData.set('rama_size', templateItems[2].width || templateItems[2].size || 550);
            }

            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Mengunggah & Menyimpan...';

            uploadWithProgress('manage_templates.php?action=upload', formData, {
                title: 'Mengunggah Template, Slot Foto & Semua Item...',
                onSuccess: (result) => {
                    btn.disabled = false;
                    btn.innerText = originalText;
                    if (result.success) {
                        alert('Template, slot tempat foto & semua item berhasil disimpan!');
                        fetchTemplates();
                    } else {
                        alert('Gagal: ' + (result.error || 'Terjadi kesalahan'));
                    }
                },
                onError: (errMsg) => {
                    btn.disabled = false;
                    btn.innerText = originalText;
                    alert('Gagal menyimpan template: ' + errMsg);
                }
            });
        };

        async function deleteTemplate(id) {
            if (!confirm('Yakin ingin menghapus template ini?')) return;
            try {
                const res = await fetch('manage_templates.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if ((await res.json()).success) fetchTemplates();
            } catch (err) {
                alert('Gagal menghapus');
            }
        }

        // ================= BRANDING & BOOTH APPEARANCE LOGIC =================
        function applyAdminTheme(s) {
            if (!s) return;
            const root = document.documentElement;
            if (s.bgColor) root.style.setProperty('--booth-bg', s.bgColor);
            if (s.primaryColor) root.style.setProperty('--booth-primary', s.primaryColor);
            if (s.secondaryColor) root.style.setProperty('--booth-secondary', s.secondaryColor);
            if (s.goldColor) root.style.setProperty('--booth-gold', s.goldColor);

            if (s.bgImage) {
                document.body.style.backgroundImage = `url('${s.bgImage}')`;
                document.body.style.backgroundSize = 'cover';
                document.body.style.backgroundPosition = 'center';
                document.body.style.backgroundAttachment = 'fixed';
            } else if (s.bgColor) {
                document.body.style.backgroundColor = s.bgColor;
                document.body.style.backgroundImage = 'radial-gradient(circle at 10% 15%, rgba(212, 140, 18, 0.18) 0%, transparent 35%), radial-gradient(circle at 90% 85%, rgba(212, 140, 18, 0.18) 0%, transparent 35%)';
            }
        }

        async function fetchBrandingSettings() {
            try {
                const res = await fetch('manage_settings.php?action=get');
                const data = await res.json();
                if (data.success && data.settings) {
                    currentBranding = data.settings;
                    populateBrandingForm(data.settings);
                    updateMockupPreview();
                    applyAdminTheme(data.settings);
                }
            } catch (err) {
                console.error("Failed to fetch branding settings:", err);
            }
        }

        function populateBrandingForm(s) {
            document.getElementById('brand-title').value = s.title || '';
            document.getElementById('brand-subtitle').value = s.subtitle || '';
            
            document.getElementById('brand-title-color').value = s.titleColor || '#D48C12';
            document.getElementById('brand-title-color-val').innerText = (s.titleColor || '#D48C12').toUpperCase();

            document.getElementById('brand-subtitle-color').value = s.subtitleColor || '#D48C12';
            document.getElementById('brand-subtitle-color-val').innerText = (s.subtitleColor || '#D48C12').toUpperCase();

            document.getElementById('brand-bg-color').value = s.bgColor || '#2D5A27';
            document.getElementById('brand-bg-color-val').innerText = (s.bgColor || '#2D5A27').toUpperCase();

            document.getElementById('brand-primary-color').value = s.primaryColor || '#D48C12';
            document.getElementById('brand-primary-color-val').innerText = (s.primaryColor || '#D48C12').toUpperCase();

            document.getElementById('brand-gold-color').value = s.goldColor || '#D4AF37';
            document.getElementById('brand-gold-color-val').innerText = (s.goldColor || '#D4AF37').toUpperCase();

            document.getElementById('brand-secondary-color').value = s.secondaryColor || '#63392E';
            document.getElementById('brand-secondary-color-val').innerText = (s.secondaryColor || '#63392E').toUpperCase();

            document.getElementById('brand-show-deco').checked = s.showDeco !== false;

            // Background image preview box
            const bgPreviewBox = document.getElementById('bg-image-preview-box');
            const bgThumb = document.getElementById('bg-thumb');
            const removeBgInput = document.getElementById('remove-bg-image-input');
            removeBgInput.value = 'false';
            if (s.bgImage) {
                bgThumb.src = s.bgImage;
                bgPreviewBox.classList.remove('hidden');
            } else {
                bgPreviewBox.classList.add('hidden');
            }

            // Deco Previews
            if (s.decoTopLeft) document.getElementById('preview-deco-tl').src = s.decoTopLeft;
            if (s.decoTopRight) document.getElementById('preview-deco-tr').src = s.decoTopRight;
            if (s.decoBottomLeft) document.getElementById('preview-deco-bl').src = s.decoBottomLeft;
            if (s.decoBottomRight) document.getElementById('preview-deco-br').src = s.decoBottomRight;
        }

        function removeBackgroundImage() {
            document.getElementById('remove-bg-image-input').value = 'true';
            document.getElementById('bg-image-preview-box').classList.add('hidden');
            document.getElementById('brand-bg-image').value = '';
            updateMockupPreview();
        }

        function resetDecoSlot(key) {
            const inputReset = document.getElementById('reset-' + key);
            if (inputReset) inputReset.value = 'true';
            
            const defaultDecos = {
                decoTopLeft: './gambar/lampu.webp',
                decoTopRight: './gambar/lampu.webp',
                decoBottomLeft: './gambar/ketupat.webp',
                decoBottomRight: './gambar/ketupat.webp'
            };

            const previewMap = {
                decoTopLeft: 'preview-deco-tl',
                decoTopRight: 'preview-deco-tr',
                decoBottomLeft: 'preview-deco-bl',
                decoBottomRight: 'preview-deco-br'
            };

            if (previewMap[key]) {
                document.getElementById(previewMap[key]).src = defaultDecos[key];
            }
            updateMockupPreview();
        }

        function updateMockupPreview() {
            const mockup = document.getElementById('booth-screen-mockup');
            const titleEl = document.getElementById('mock-title');
            const subtitleEl = document.getElementById('mock-subtitle');
            const btnEl = document.getElementById('mock-btn');
            const decoContainer = document.getElementById('mock-deco-container');

            const title = document.getElementById('brand-title').value || 'Berbuka Bersama';
            const subtitle = document.getElementById('brand-subtitle').value || 'Mahaghora Group';
            const titleColor = document.getElementById('brand-title-color').value;
            const subtitleColor = document.getElementById('brand-subtitle-color').value;
            const bgColor = document.getElementById('brand-bg-color').value;
            const primaryColor = document.getElementById('brand-primary-color').value;
            const showDeco = document.getElementById('brand-show-deco').checked;

            titleEl.textContent = title;
            titleEl.style.color = titleColor;
            subtitleEl.textContent = subtitle;
            subtitleEl.style.color = subtitleColor;
            btnEl.style.backgroundColor = primaryColor;

            decoContainer.style.display = showDeco ? 'block' : 'none';

            // Check if remove bg was clicked
            const isBgRemoved = document.getElementById('remove-bg-image-input').value === 'true';
            const bgFileInput = document.getElementById('brand-bg-image');

            if (bgFileInput.files && bgFileInput.files[0]) {
                const url = URL.createObjectURL(bgFileInput.files[0]);
                mockup.style.backgroundImage = `url('${url}')`;
            } else if (!isBgRemoved && currentBranding && currentBranding.bgImage) {
                mockup.style.backgroundImage = `url('${currentBranding.bgImage}')`;
            } else {
                mockup.style.backgroundImage = 'none';
                mockup.style.backgroundColor = bgColor;
            }

            // Live local preview for deco file inputs
            const decoInputs = [
                { input: 'input-deco-tl', preview: 'preview-deco-tl', mock: 'mock-deco-tl' },
                { input: 'input-deco-tr', preview: 'preview-deco-tr', mock: 'mock-deco-tr' },
                { input: 'input-deco-bl', preview: 'preview-deco-bl', mock: 'mock-deco-bl' },
                { input: 'input-deco-br', preview: 'preview-deco-br', mock: 'mock-deco-br' }
            ];

            decoInputs.forEach(d => {
                const inp = document.getElementById(d.input);
                if (inp && inp.files && inp.files[0]) {
                    const objUrl = URL.createObjectURL(inp.files[0]);
                    document.getElementById(d.preview).src = objUrl;
                    document.getElementById(d.mock).src = objUrl;
                } else {
                    document.getElementById(d.mock).src = document.getElementById(d.preview).src;
                }
            });
        }

        // Setup real-time event listeners on branding inputs
        const inputsToListen = [
            'brand-title', 'brand-subtitle', 
            'brand-title-color', 'brand-subtitle-color', 
            'brand-bg-color', 'brand-primary-color', 
            'brand-gold-color', 'brand-secondary-color', 
            'brand-show-deco'
        ];

        inputsToListen.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', () => {
                    const valDisplay = document.getElementById(id + '-val');
                    if (valDisplay) valDisplay.innerText = el.value.toUpperCase();
                    updateMockupPreview();
                });
            }
        });

        // File inputs listeners
        ['brand-bg-image', 'input-deco-tl', 'input-deco-tr', 'input-deco-bl', 'input-deco-br'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', updateMockupPreview);
            }
        });

        // Branding Form Submit with Realtime Progress
        const brandingForm = document.getElementById('branding-form');
        if (brandingForm) {
            brandingForm.onsubmit = (e) => {
                e.preventDefault();
                const formData = new FormData(brandingForm);
                formData.set('showDeco', document.getElementById('brand-show-deco').checked ? 'true' : 'false');
                
                const btn = document.getElementById('btn-save-branding');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span>Mengunggah & Menyimpan...</span>';

                uploadWithProgress('manage_settings.php?action=save', formData, {
                    title: 'Mengunggah Pengaturan Tampilan & Background...',
                    onSuccess: (data) => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        if (data.success) {
                            alert('Pengaturan tampilan booth berhasil disimpan!');
                            fetchBrandingSettings();
                        } else {
                            alert('Gagal menyimpan: ' + (data.error || 'Terjadi kesalahan'));
                        }
                    },
                    onError: (errMsg) => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        alert('Gagal koneksi saat menyimpan pengaturan: ' + errMsg);
                    }
                });
            };
        }

        async function resetAllBranding() {
            if (!confirm('Kembalikan semua pengaturan tampilan booth ke standar awal?')) return;
            try {
                const res = await fetch('manage_settings.php?action=reset', { method: 'POST' });
                const data = await res.json();
                if (data.success) {
                    alert('Tampilan booth telah dikembalikan ke standar.');
                    fetchBrandingSettings();
                }
            } catch (err) {
                alert('Gagal reset');
            }
        }

        // ================= REUSABLE PROGRESS UPLOAD HANDLER =================
        function uploadWithProgress(url, formData, { title = 'Mengunggah Berkas...', onSuccess, onError }) {
            const modal = document.getElementById('upload-progress-modal');
            const titleEl = document.getElementById('upload-modal-title');
            const bar = document.getElementById('upload-progress-bar');
            const bytesEl = document.getElementById('upload-progress-bytes');
            const percentEl = document.getElementById('upload-progress-percent');
            const subtextEl = document.getElementById('upload-status-subtext');

            if (modal) {
                if (titleEl) titleEl.textContent = title;
                if (bar) bar.style.width = '0%';
                if (bytesEl) bytesEl.textContent = 'Menghitung...';
                if (percentEl) percentEl.textContent = '0%';
                if (subtextEl) {
                    subtextEl.innerHTML = '<span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span><span>Mengunggah file ke server... Jangan tutup halaman ini.</span>';
                }
                modal.classList.remove('hidden');
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    const percent = Math.min(100, Math.round((e.loaded / e.total) * 100));
                    const loadedMB = (e.loaded / (1024 * 1024)).toFixed(1);
                    const totalMB = (e.total / (1024 * 1024)).toFixed(1);

                    if (bar) bar.style.width = percent + '%';
                    if (percentEl) percentEl.textContent = percent + '%';
                    if (bytesEl) bytesEl.textContent = `${loadedMB} MB / ${totalMB} MB`;

                    if (percent >= 100 && subtextEl) {
                        subtextEl.innerHTML = '<span class="w-3 h-3 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span><span class="text-emerald-950 font-black">Upload 100% Selesai! Memproses & menyimpan data di server...</span>';
                    }
                }
            };

            xhr.onload = () => {
                if (modal) modal.classList.add('hidden');
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        onSuccess(res);
                    } catch (err) {
                        onError('Respons server tidak valid: ' + xhr.responseText);
                    }
                } else {
                    onError(`Upload gagal (Status ${xhr.status}): ` + xhr.statusText);
                }
            };

            xhr.onerror = () => {
                if (modal) modal.classList.add('hidden');
                onError('Terjadi kesalahan jaringan atau koneksi terputus');
            };

            xhr.ontimeout = () => {
                if (modal) modal.classList.add('hidden');
                onError('Upload timeout (waktu unggah habis)');
            };

            xhr.send(formData);
        }

        // Init
        fetchQueue();
        setInterval(fetchQueue, 3000); // Polling every 3 seconds
    </script>

    <!-- REAL-TIME UPLOAD PROGRESS MODAL -->
    <div id="upload-progress-modal" class="fixed inset-0 z-[9999] bg-black/80 backdrop-blur-md flex items-center justify-center p-4 hidden">
        <div class="glass-card w-full max-w-md rounded-3xl p-6 sm:p-8 border-2 border-amber-400/50 shadow-2xl text-center relative overflow-hidden">
            <!-- Glow background -->
            <div class="absolute -top-12 -left-12 w-40 h-40 bg-amber-400/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-500/20 border border-amber-400/40 flex items-center justify-center shadow-inner">
                    <svg class="w-8 h-8 text-amber-900 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>

                <h3 id="upload-modal-title" class="text-lg sm:text-xl font-black text-amber-950 mb-1">Mengunggah Berkas...</h3>
                <p id="upload-modal-subtitle" class="text-xs font-semibold text-amber-800/80 mb-6">Harap tunggu, berkas resolusi tinggi sedang dikirim ke server</p>

                <!-- Progress Bar -->
                <div class="w-full bg-black/10 rounded-full h-5 p-1 border border-amber-900/20 mb-3 shadow-inner overflow-hidden">
                    <div id="upload-progress-bar" class="h-full bg-gradient-to-r from-amber-500 via-amber-400 to-emerald-500 rounded-full transition-all duration-150 ease-out shadow-sm" style="width: 0%;"></div>
                </div>

                <!-- Progress Numbers -->
                <div class="flex items-center justify-between text-xs font-black text-amber-950 px-1 mb-4">
                    <span id="upload-progress-bytes" class="text-amber-800 font-bold font-mono">0 MB / 0 MB</span>
                    <span id="upload-progress-percent" class="text-base text-amber-950 font-black font-mono">0%</span>
                </div>

                <!-- Status subtext -->
                <div id="upload-status-subtext" class="text-[11px] sm:text-xs font-bold text-amber-950 bg-amber-500/15 py-2.5 px-3.5 rounded-2xl border border-amber-500/30 flex items-center justify-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                    <span>Mengunggah file ke server... Jangan tutup halaman ini.</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

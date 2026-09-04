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

                            <div class="space-y-6">
                                <!-- Item 1 Settings -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <h3 class="font-bold text-sm text-[#2D6A4F] mb-3 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div> Item 1 / Hiasan 1 (Optional)
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">File Gambar (PNG)</label>
                                            <input type="file" name="ketupat" accept="image/*" class="w-full text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran (px)</label>
                                            <input type="number" name="ketupat_size" placeholder="350" value="350" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                            <input type="number" name="ketupat_x" placeholder="120" value="120" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                            <input type="number" name="ketupat_y" placeholder="150" value="150" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- Item 2 Settings -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <h3 class="font-bold text-sm text-[#2D6A4F] mb-3 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-amber-500 rounded-full"></div> Item 2 / Hiasan 2 (Optional)
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">File Gambar (PNG)</label>
                                            <input type="file" name="lampu" accept="image/*" class="w-full text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran (px)</label>
                                            <input type="number" name="lampu_size" placeholder="300" value="300" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                            <input type="number" name="lampu_x" placeholder="-100" value="-100" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                            <input type="number" name="lampu_y" placeholder="140" value="140" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- Item 3 Settings -->
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <h3 class="font-bold text-sm text-[#2D6A4F] mb-3 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div> Item 3 / Hiasan 3 (Optional)
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">File Gambar (PNG)</label>
                                            <input type="file" name="rama" accept="image/*" class="w-full text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran (px)</label>
                                            <input type="number" name="rama_size" placeholder="550" value="550" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                            <input type="number" name="rama_x" placeholder="150" value="150" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                            <input type="number" name="rama_y" placeholder="300" value="300" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t">
                                <label id="outer-label" class="block text-sm font-semibold mb-2">Tema Luar Utama (A5: 1748x2480 px) <span class="text-red-500">*</span></label>
                                <input type="file" name="outerImage" id="outer-input" accept="image/*" required class="w-full text-sm">
                            </div>

                            <button type="submit" class="w-full px-8 py-4 bg-[#2D6A4F] text-white rounded-2xl font-bold hover:scale-[1.01] transition-all shadow-lg active:scale-95">
                                Upload Template & Save Layout
                            </button>
                        </form>
                    </section>

                    <!-- Template List -->
                    <section>
                        <h2 class="text-xl font-bold mb-4 text-[#2D6A4F]">Daftar Template</h2>
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
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        <button type="button" onclick="selectDecoForDrag('ketupat')" id="btn-select-ketupat" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300 hover:bg-emerald-200 transition-all flex items-center gap-1.5 shrink-0 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Item 1 (Ketupat)
                        </button>
                        <button type="button" onclick="selectDecoForDrag('lampu')" id="btn-select-lampu" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 transition-all flex items-center gap-1.5 shrink-0 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Item 2 (Lampu)
                        </button>
                        <button type="button" onclick="selectDecoForDrag('rama')" id="btn-select-rama" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-100 text-indigo-900 border border-indigo-300 hover:bg-indigo-200 transition-all flex items-center gap-1.5 shrink-0 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Item 3 (Stiker)
                        </button>
                    </div>

                    <div class="relative bg-white rounded-[3rem] p-4 shadow-2xl border-8 border-gray-100 overflow-hidden aspect-[9/16] w-full max-w-[350px] mx-auto select-none touch-none">
                        <canvas id="preview-canvas" width="1080" height="1920" class="w-full h-full object-contain rounded-[2rem] bg-[#FFFDF5] cursor-grab"></canvas>
                        <div class="absolute inset-0 pointer-events-none border-[12px] border-white/50 rounded-[2.5rem]"></div>
                    </div>

                    <div class="bg-[#1B4332] text-ramadan-cream p-4 rounded-2xl text-xs space-y-1.5 shadow-md">
                        <div class="flex items-center justify-between border-b border-ramadan-cream/20 pb-1.5 mb-1.5 font-bold">
                            <span>🖐️ CARA GESER POSISI ITEM</span>
                            <span id="drag-coord-status" class="font-mono text-[11px] text-amber-300">Siap digeser</span>
                        </div>
                        <p>• <strong>Klik / sentuh & geser</strong> item di gambar monitor di atas untuk memposisikannya secara visual.</p>
                        <p>• Nilai <strong>Geser X</strong> dan <strong>Geser Y</strong> pada form akan terisi otomatis mengikuti posisi geseran Anda.</p>
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
            btnQueue.classList.remove('active');
            btnTemplates.classList.remove('active');
            btnBranding.classList.remove('active');
            contentQueue.classList.add('hidden');
            contentTemplates.classList.add('hidden');
            contentBranding.classList.add('hidden');
            queueControls.classList.add('hidden');

            if (tab === 'queue') {
                btnQueue.classList.add('active');
                contentQueue.classList.remove('hidden');
                queueControls.classList.remove('hidden');
                fetchQueue();
            } else if (tab === 'templates') {
                btnTemplates.classList.add('active');
                contentTemplates.classList.remove('hidden');
                fetchTemplates();
                setTimeout(updateLivePreview, 200);
            } else if (tab === 'branding') {
                btnBranding.classList.add('active');
                contentBranding.classList.remove('hidden');
                fetchBrandingSettings();
            }
        }

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

        // ================= TEMPLATE MANAGER LOGIC =================
        const form = document.getElementById('upload-form');
        const list = document.getElementById('template-list');
        const canvas = document.getElementById('preview-canvas');
        const ctx = canvas.getContext('2d');

        // Preview State & Drag Variables
        let itemBounds = {};
        let activeDraggedItem = null;
        let selectedDeco = null;
        let hoveredDeco = null;
        let dragStartMouseX = 0;
        let dragStartMouseY = 0;
        let dragInitialXOff = 0;
        let dragInitialYOff = 0;

        const previewImages = {
            outer: null,
            ketupat: null,
            lampu: null,
            rama: null,
            default_ketupat: new Image(),
            default_lampu: new Image(),
            default_rama: new Image()
        };

        const loadDefault = (key, src) => {
            previewImages[key] = new Image();
            previewImages[key].onload = updateLivePreview;
            previewImages[key].src = src;
        };
        
        loadDefault('default_ketupat', './gambar/ketupat.webp');
        loadDefault('default_lampu', './gambar/lampu.webp');
        loadDefault('default_rama', './gambar/rama.png');

        function updateLivePreview() {
            const sizeType = form.sizeType ? form.sizeType.value : 'a5_6grid';
            const is6Grid = sizeType === 'a5_6grid';
            const isA5 = sizeType === 'a5' || is6Grid;

            const outerLabel = document.getElementById('outer-label');
            if (outerLabel) {
                outerLabel.innerHTML = isA5 
                    ? `Tema Luar Utama (A5: 1748x2480 px) <span class="text-red-500">*</span>` 
                    : `Tema Luar Utama (Strip: 1080x1920 px) <span class="text-red-500">*</span>`;
            }

            canvas.width = isA5 ? 1748 : 1080;
            canvas.height = isA5 ? 2480 : 1920;
            itemBounds = {}; // Reset bounds for hit testing
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#FFFDF5';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const isOverlay = form.overlayMode.checked;

            if (!isOverlay && previewImages.outer && previewImages.outer.complete) {
                ctx.drawImage(previewImages.outer, 0, 0, canvas.width, canvas.height);
            }

            if (is6Grid) {
                // Draw 6 photo slots (2 cols x 3 rows)
                const paddingX = 94;
                const gapX = 40;
                const gapY = 45;
                const imgW = Math.round((canvas.width - (2 * paddingX) - gapX) / 2);
                const imgH = Math.round(imgW * (450 / 920));
                const totalGridH = (3 * imgH) + (2 * gapY);
                const topY = Math.round((canvas.height - totalGridH) / 2);

                ctx.fillStyle = '#E5E7EB';
                for (let i = 0; i < 6; i++) {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    const posX = paddingX + col * (imgW + gapX);
                    const posY = topY + row * (imgH + gapY);

                    ctx.beginPath();
                    if (ctx.roundRect) ctx.roundRect(posX, posY, imgW, imgH, 20);
                    else ctx.rect(posX, posY, imgW, imgH);
                    ctx.fill();

                    ctx.strokeStyle = '#D4AF37';
                    ctx.lineWidth = 4;
                    ctx.stroke();
                }

                // Draw Ornaments for 6 Grid
                const drawDeco6 = (type, slotIdx, label) => {
                    const img = previewImages[type] || previewImages[`default_${type}`];
                    if (!img || !img.complete) return;

                    const size = parseInt(form[`${type}_size`].value) || 0;
                    const xOff = parseInt(form[`${type}_x`].value) || 0;
                    const yOff = parseInt(form[`${type}_y`].value) || 0;

                    const col = slotIdx % 2;
                    const row = Math.floor(slotIdx / 2);
                    const slotX = paddingX + col * (imgW + gapX);
                    const slotY = topY + row * (imgH + gapY);

                    let x, y;
                    if (type === 'lampu') {
                        x = slotX + xOff;
                    } else {
                        x = slotX + imgW - size + xOff;
                    }
                    y = slotY + imgH - size + yOff;

                    itemBounds[type] = {
                        type,
                        label,
                        x,
                        y,
                        width: size,
                        height: size,
                        xOff,
                        yOff
                    };

                    ctx.drawImage(img, x, y, size, size);
                };

                drawDeco6('ketupat', 1, 'Item 1 / Ketupat'); // Slot #2 Kanan Atas
                drawDeco6('lampu', 2, 'Item 2 / Lampu');   // Slot #3 Kiri Tengah
                drawDeco6('rama', 5, 'Item 3 / Stiker');    // Slot #6 Kanan Bawah

            } else {
                let imgWidth, imgHeight, padding, headerHeight, gap;
                if (isA5) {
                    imgWidth = 1540;
                    imgHeight = 650;
                    padding = 104;
                    headerHeight = 180;
                    gap = 60;
                } else {
                    imgWidth = 920;
                    imgHeight = 450;
                    padding = 80;
                    headerHeight = 150;
                    gap = 80;
                }

                ctx.fillStyle = '#E5E7EB';
                for(let i=0; i<3; i++) {
                    const yPos = padding + headerHeight + (i * (imgHeight + gap));
                    ctx.beginPath();
                    if (ctx.roundRect) {
                        ctx.roundRect(padding, yPos, imgWidth, imgHeight, 20);
                    } else {
                        ctx.rect(padding, yPos, imgWidth, imgHeight);
                    }
                    ctx.fill();
                }

                const drawDeco = (type, index, label) => {
                    const img = previewImages[type] || previewImages[`default_${type}`];
                    if (!img || !img.complete) return;

                    const size = parseInt(form[`${type}_size`].value) || 0;
                    const xOff = parseInt(form[`${type}_x`].value) || 0;
                    const yOff = parseInt(form[`${type}_y`].value) || 0;
                    
                    const yPos = padding + headerHeight + (index * (imgHeight + gap));
                    
                    let x, y;
                    if (type === 'lampu') {
                        x = padding + xOff;
                    } else {
                        x = padding + imgWidth - size + xOff;
                    }
                    y = yPos + imgHeight - size + yOff;

                    itemBounds[type] = {
                        type,
                        label,
                        x,
                        y,
                        width: size,
                        height: size,
                        xOff,
                        yOff
                    };

                    ctx.drawImage(img, x, y, size, size);
                };

                drawDeco('ketupat', 0, 'Item 1 / Ketupat');
                drawDeco('lampu', 1, 'Item 2 / Lampu');
                drawDeco('rama', 2, 'Item 3 / Stiker');
            }

            if (isOverlay && previewImages.outer && previewImages.outer.complete) {
                ctx.drawImage(previewImages.outer, 0, 0, canvas.width, canvas.height);
            }

            // Draw Interactive Highlight Selection Boxes for Active / Hovered Items
            const targetHighlight = activeDraggedItem || hoveredDeco || selectedDeco;
            if (targetHighlight && itemBounds[targetHighlight]) {
                const b = itemBounds[targetHighlight];
                ctx.save();
                ctx.strokeStyle = activeDraggedItem ? '#22C55E' : '#D4AF37';
                ctx.lineWidth = 5;
                ctx.setLineDash([16, 10]);
                ctx.strokeRect(b.x, b.y, b.width, b.height);

                // Corner handles
                ctx.fillStyle = activeDraggedItem ? '#22C55E' : '#1B4332';
                ctx.strokeStyle = '#FFFFFF';
                ctx.lineWidth = 4;
                ctx.setLineDash([]);
                const corners = [
                    [b.x, b.y],
                    [b.x + b.width, b.y],
                    [b.x, b.y + b.height],
                    [b.x + b.width, b.y + b.height]
                ];
                corners.forEach(([cx, cy]) => {
                    ctx.beginPath();
                    ctx.arc(cx, cy, 12, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.stroke();
                });

                // Pill label badge
                const tagText = `${b.label} (X: ${b.xOff}, Y: ${b.yOff})`;
                ctx.font = 'bold 26px sans-serif';
                const tagWidth = ctx.measureText(tagText).width + 36;
                const tagHeight = 46;
                const tagX = Math.max(10, Math.min(canvas.width - tagWidth - 10, b.x));
                const tagY = Math.max(tagHeight + 10, b.y - 12);

                ctx.fillStyle = 'rgba(27, 67, 50, 0.95)';
                ctx.beginPath();
                if (ctx.roundRect) ctx.roundRect(tagX, tagY - tagHeight, tagWidth, tagHeight, 14);
                else ctx.rect(tagX, tagY - tagHeight, tagWidth, tagHeight);
                ctx.fill();

                ctx.strokeStyle = activeDraggedItem ? '#22C55E' : '#D4AF37';
                ctx.lineWidth = 3;
                ctx.stroke();

                ctx.fillStyle = '#FFFDF5';
                ctx.fillText(tagText, tagX + 18, tagY - 14);
                ctx.restore();
            }
        }

        // ================= CANVAS DRAG & DROP INTERACTION =================
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

        function hitTestDeco(pos) {
            const types = ['rama', 'lampu', 'ketupat']; // Top to bottom hit test
            for (const type of types) {
                const b = itemBounds[type];
                if (b && pos.x >= b.x && pos.x <= (b.x + b.width) && pos.y >= b.y && pos.y <= (b.y + b.height)) {
                    return type;
                }
            }
            return null;
        }

        function selectDecoForDrag(type) {
            selectedDeco = type;
            ['ketupat', 'lampu', 'rama'].forEach(t => {
                const btn = document.getElementById('btn-select-' + t);
                if (btn) {
                    if (t === type) {
                        btn.className = 'px-3 py-1.5 rounded-xl text-xs font-black bg-amber-500 text-stone-950 border-2 border-amber-600 shadow-md scale-105 flex items-center gap-1.5 shrink-0 transition-all';
                    } else {
                        btn.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-100 text-stone-700 border border-stone-200 hover:bg-stone-200 flex items-center gap-1.5 shrink-0 transition-all';
                    }
                }
            });

            // Focus on input
            const inputX = form[type + '_x'];
            if (inputX) {
                inputX.scrollIntoView({ behavior: 'smooth', block: 'center' });
                inputX.focus();
            }

            const statusEl = document.getElementById('drag-coord-status');
            if (statusEl && form[type + '_x'] && form[type + '_y']) {
                statusEl.textContent = `${type.toUpperCase()} X:${form[type + '_x'].value} Y:${form[type + '_y'].value}`;
            }

            updateLivePreview();
        }

        const startCanvasDrag = (e) => {
            const pos = getCanvasMousePos(e);
            const hit = hitTestDeco(pos);
            if (hit) {
                activeDraggedItem = hit;
                selectedDeco = hit;
                dragStartMouseX = pos.x;
                dragStartMouseY = pos.y;
                dragInitialXOff = parseInt(form[hit + '_x'].value) || 0;
                dragInitialYOff = parseInt(form[hit + '_y'].value) || 0;
                canvas.style.cursor = 'grabbing';
                selectDecoForDrag(hit);
                if (e.cancelable) e.preventDefault();
            }
        };

        const onCanvasDragMove = (e) => {
            const pos = getCanvasMousePos(e);
            if (activeDraggedItem) {
                const deltaX = Math.round(pos.x - dragStartMouseX);
                const deltaY = Math.round(pos.y - dragStartMouseY);
                
                const newX = dragInitialXOff + deltaX;
                const newY = dragInitialYOff + deltaY;

                form[activeDraggedItem + '_x'].value = newX;
                form[activeDraggedItem + '_y'].value = newY;

                const statusEl = document.getElementById('drag-coord-status');
                if (statusEl) {
                    statusEl.textContent = `${activeDraggedItem.toUpperCase()} X:${newX} Y:${newY}`;
                }

                updateLivePreview();
                if (e.cancelable) e.preventDefault();
            } else {
                const hit = hitTestDeco(pos);
                if (hit !== hoveredDeco) {
                    hoveredDeco = hit;
                    canvas.style.cursor = hit ? 'grab' : 'default';
                    updateLivePreview();
                }
            }
        };

        const endCanvasDrag = () => {
            if (activeDraggedItem) {
                activeDraggedItem = null;
                canvas.style.cursor = hoveredDeco ? 'grab' : 'default';
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

        document.querySelectorAll('.deco-input, #form-overlay, #form-size-type').forEach(el => {
            el.addEventListener('input', () => {
                const statusEl = document.getElementById('drag-coord-status');
                if (statusEl && selectedDeco && form[selectedDeco + '_x'] && form[selectedDeco + '_y']) {
                    statusEl.textContent = `${selectedDeco.toUpperCase()} X:${form[selectedDeco + '_x'].value} Y:${form[selectedDeco + '_y'].value}`;
                }
                updateLivePreview();
            });
            el.addEventListener('change', updateLivePreview);
        });

        const handleFileInput = (inputName, previewKey) => {
            const el = form[inputName];
            el.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (re) => {
                        const img = new Image();
                        img.onload = updateLivePreview;
                        img.src = re.target.result;
                        previewImages[previewKey] = img;
                    };
                    reader.readAsDataURL(file);
                }
            });
        };

        handleFileInput('outerImage', 'outer');
        handleFileInput('ketupat', 'ketupat');
        handleFileInput('lampu', 'lampu');
        handleFileInput('rama', 'rama');

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
                                    <span class="px-2 py-0.5 ${t.overlayMode ? 'bg-emerald-500/80 text-white' : 'bg-slate-700/80 text-slate-200'} rounded-full text-[9px] font-bold">
                                        ${t.overlayMode ? '✓ OVERLAY' : 'BACKGROUND'}
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

                    <button onclick="deleteTemplate('${t.id}')" class="mt-3 w-full py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 active:scale-95 cursor-pointer">
                        <span>🗑️</span> Hapus Template
                    </button>
                </div>
            `}).join('');
        }

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
            const formData = new FormData(form);
            if (form.overlayMode.checked) formData.set('overlayMode', 'true');
            
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Mengunggah & Menyimpan...';

            uploadWithProgress('manage_templates.php?action=upload', formData, {
                title: 'Mengunggah File Template & Background...',
                onSuccess: (result) => {
                    btn.disabled = false;
                    btn.innerText = originalText;
                    if (result.success) {
                        alert('Template & Layout berhasil disimpan!');
                        fetchTemplates();
                        form.reset();
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

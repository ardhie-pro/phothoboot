<?php
/**
 * History & Repurposing Gallery - Photo Booth
 * Allows browsing all past photo sessions and applying active templates
 */

$uploadsDir = __DIR__ . '/uploads/';
$sessions = [];

if (is_dir($uploadsDir)) {
    $dirItems = @scandir($uploadsDir) ?: [];
    foreach ($dirItems as $item) {
        if ($item === '.' || $item === '..' || $item === 'templates' || $item === 'branding') continue;
        $dir = $uploadsDir . $item;
        if (!is_dir($dir)) continue;

        $files = @scandir($dir) ?: [];
        $strips = [];
        $rawPhotos = [];

        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) continue;

            $relUrl = 'uploads/' . $item . '/' . $f;
            if (stripos($f, 'strip') !== false) {
                $strips[] = $relUrl;
            } else {
                $rawPhotos[] = $relUrl;
            }
        }

        if (empty($strips) && empty($rawPhotos)) continue;

        // Natural sort photos
        natsort($rawPhotos);
        $rawPhotos = array_values($rawPhotos);
        natsort($strips);
        $strips = array_values($strips);

        // Parse timestamp from directory name or file modification time
        $timestamp = @filemtime($dir) ?: time();
        $formattedDate = date('d M Y, H:i', $timestamp);
        if (preg_match('/^(\d{4})(\d{2})(\d{2})_(\d{2})(\d{2})(\d{2})/', $item, $m)) {
            $dt = DateTime::createFromFormat('Ymd_His', "{$m[1]}{$m[2]}{$m[3]}_{$m[4]}{$m[5]}{$m[6]}");
            if ($dt) {
                $formattedDate = $dt->format('d M Y, H:i') . ' WIB';
                $timestamp = $dt->getTimestamp();
            }
        }

        $sessions[] = [
            'id' => $item,
            'timestamp' => $timestamp,
            'date_str' => $formattedDate,
            'photos' => $rawPhotos,
            'strips' => $strips,
            'thumbnail' => !empty($strips) ? $strips[0] : (!empty($rawPhotos) ? $rawPhotos[0] : ''),
            'photo_count' => count($rawPhotos)
        ];
    }

    // Sort newest session first
    usort($sessions, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
}

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

$totalSessions = count($sessions);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎞️ Riwayat Sesi Foto | <?= htmlspecialchars($boothTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --booth-bg: <?= htmlspecialchars($settings['bgColor']) ?>;
            --booth-primary: <?= htmlspecialchars($settings['primaryColor']) ?>;
            --booth-secondary: <?= htmlspecialchars($settings['secondaryColor']) ?>;
            --booth-gold: <?= htmlspecialchars($settings['goldColor']) ?>;
            --booth-cream: #FFFDF5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
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
            color: #FFFDF5;
            min-height: 100vh;
            padding-bottom: 80px;
        }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .glass-card {
            background: rgba(255, 253, 245, 0.94);
            backdrop-filter: blur(20px);
            border: 1.5px solid rgba(212, 140, 18, 0.25);
            box-shadow: 0 25px 50px -12px rgba(45, 90, 39, 0.3);
            color: var(--booth-secondary);
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, var(--booth-primary) 0%, #B4730A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--booth-primary) 0%, #B4730A 100%);
            color: #FFFDF5;
            font-weight: 700;
            box-shadow: 0 8px 20px -4px rgba(212, 140, 18, 0.4);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(212, 140, 18, 0.6);
        }
        .btn-emerald {
            background: linear-gradient(135deg, var(--booth-bg) 0%, #173814 100%);
            color: #FFFDF5;
            font-weight: 700;
            box-shadow: 0 8px 20px -4px rgba(45, 90, 39, 0.4);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-emerald:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(45, 90, 39, 0.6);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--booth-bg); }
        ::-webkit-scrollbar-thumb { background: var(--booth-gold); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--booth-primary); }
    </style>
</head>
<body class="p-4 sm:p-6 md:p-8">

    <div class="max-w-7xl mx-auto">
        <!-- Top Navigation Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 glass-card p-4 sm:p-5 rounded-3xl shadow-xl">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-2xl shadow-inner">
                    🎞️
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold font-playfair text-amber-950">Riwayat Sesi Foto</h1>
                    <p class="text-xs text-amber-800/80 font-medium">Total <span class="text-amber-900 font-bold"><?= $totalSessions ?> Sesi Foto</span> tersimpan • <span class="font-semibold"><?= htmlspecialchars($boothTitle) ?></span></p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="index.html" class="px-5 py-2.5 btn-emerald rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 hover:scale-105 active:scale-95">
                    <span>📸</span> Ke Booth Foto
                </a>
                <a href="admin.php" class="px-4 py-2.5 bg-stone-800 hover:bg-stone-700 text-stone-100 rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 border border-stone-600 hover:scale-105 active:scale-95">
                    <span>⚙️</span> Dashboard Admin
                </a>
            </div>
        </div>

        <!-- Notification Toast -->
        <div id="toast" class="fixed top-5 left-1/2 -translate-x-1/2 z-[250] hidden transition-all duration-300 transform -translate-y-4 opacity-0 max-w-sm w-full px-4">
            <div id="toast-content" class="p-4 rounded-2xl shadow-2xl flex items-center gap-3 border text-sm font-medium"></div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="glass-card p-4 rounded-2xl mb-8 flex flex-wrap items-center justify-between gap-4 shadow-lg">
            <div class="relative flex-1 min-w-[260px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-500">🔍</span>
                <input type="text" id="search-input" placeholder="Cari ID sesi atau tanggal..." class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/90 border border-amber-600/30 text-stone-900 placeholder-stone-400 text-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 shadow-inner">
            </div>
            <div class="flex items-center gap-2 text-xs text-amber-900/90 font-medium">
                <span>⚡ Tips: Klik <b>"🎨 Pasang Template Baru"</b> pada sesi mana pun untuk memasang desain tema frame yang aktif!</span>
            </div>
        </div>

        <!-- Session Grid -->
        <?php if (empty($sessions)): ?>
            <div class="glass-card rounded-3xl p-12 text-center">
                <p class="text-4xl mb-3">📷</p>
                <p class="text-lg font-bold text-amber-950">Belum Ada Riwayat Sesi Foto</p>
                <p class="text-xs text-amber-800/80 mt-1">Lakukan pemotretan pertama di Photobooth untuk melihat riwayat di sini.</p>
                <a href="index.html" class="inline-block mt-4 px-6 py-2.5 btn-gold rounded-full text-xs font-bold shadow-lg">Mulai Foto Sekarang</a>
            </div>
        <?php else: ?>
            <div id="session-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($sessions as $s): ?>
                    <div class="session-card glass-card rounded-3xl overflow-hidden flex flex-col justify-between border-2 border-amber-500/25 hover:border-amber-500/60 transition-all hover:shadow-2xl group" data-id="<?= htmlspecialchars($s['id']) ?>" data-date="<?= htmlspecialchars($s['date_str']) ?>">
                        <!-- Card Header -->
                        <div class="p-4 border-b border-amber-900/10 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-amber-900 bg-amber-400/20 border border-amber-400/40 px-2.5 py-1 rounded-full flex items-center gap-1 shadow-sm">
                                📅 <?= htmlspecialchars($s['date_str']) ?>
                            </span>
                            <span class="text-[10px] text-stone-600 bg-stone-200/80 px-2 py-0.5 rounded-md font-mono font-bold">
                                #<?= htmlspecialchars(substr($s['id'], -8)) ?>
                            </span>
                        </div>

                        <!-- Card Preview -->
                        <div class="p-4 flex flex-col items-center justify-center">
                            <div class="relative w-full aspect-[3/4] rounded-2xl bg-black/10 overflow-hidden flex items-center justify-center border-2 border-amber-500/30 group-hover:scale-[1.02] transition-transform p-1.5 shadow-inner">
                                <?php if (!empty($s['thumbnail'])): ?>
                                    <img src="<?= htmlspecialchars($s['thumbnail']) ?>" alt="Strip Preview" class="w-full h-full object-contain rounded-xl" loading="lazy">
                                <?php else: ?>
                                    <span class="text-3xl text-stone-400">🖼️</span>
                                <?php endif; ?>
                                <span class="absolute bottom-3 right-3 bg-black/80 text-amber-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-amber-500/40 backdrop-blur-sm shadow-md">
                                    📸 <?= $s['photo_count'] ?> Foto
                                </span>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="p-4 pt-0 space-y-2">
                            <button onclick="openCustomizer('<?= htmlspecialchars($s['id']) ?>', <?= htmlspecialchars(json_encode($s['photos'])) ?>)" class="w-full py-2.5 px-4 btn-gold rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-md cursor-pointer hover:scale-[1.02] active:scale-95">
                                <span>🎨</span> Pasang / Ganti Template
                            </button>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="view.php?s=<?= urlencode($s['id']) ?>" target="_blank" class="py-2.5 px-3 bg-stone-800 hover:bg-stone-700 text-stone-100 rounded-xl text-[11px] font-bold transition-all text-center flex items-center justify-center gap-1 border border-stone-600 shadow-sm hover:scale-[1.02] active:scale-95">
                                    <span>👁️</span> Buka Galeri
                                </a>
                                <button onclick="quickPrint('<?= htmlspecialchars($s['id']) ?>', '<?= htmlspecialchars(!empty($s['strips']) ? $s['strips'][0] : '') ?>')" class="py-2.5 px-3 btn-emerald text-white rounded-xl text-[11px] font-bold transition-all text-center flex items-center justify-center gap-1 shadow-sm cursor-pointer hover:scale-[1.02] active:scale-95">
                                    <span>🖨️</span> Cetak Sesi
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ========================================================================= -->
    <!-- INTERACTIVE TEMPLATE CUSTOMIZER MODAL -->
    <!-- ========================================================================= -->
    <div id="customizer-modal" class="fixed inset-0 z-[300] bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-3 md:p-6 overflow-y-auto">
        <div class="glass-card max-w-5xl w-full rounded-[32px] border-2 border-amber-500/40 p-4 md:p-6 max-h-[92vh] flex flex-col relative animate-fadeIn shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-amber-900/15 mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">🎨</span>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold font-playfair text-amber-950">Pasang Template Baru untuk Sesi Ini</h2>
                        <p class="text-xs text-amber-800/80 font-medium">Pilih template aktif yang dicentang admin, lalu susun fotomu ke dalam frame A5!</p>
                    </div>
                </div>
                <button onclick="closeCustomizer()" class="w-9 h-9 rounded-full bg-stone-200/80 hover:bg-stone-300 text-stone-700 flex items-center justify-center transition-colors text-lg cursor-pointer font-bold">
                    ✕
                </button>
            </div>

            <!-- Modal Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 overflow-y-auto pr-1 flex-1">
                <!-- Left: Controls & Template Picker (7 cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- 1. Layout Mode Switcher -->
                    <div class="bg-black/5 p-3 rounded-2xl border border-amber-500/30 flex items-center justify-between gap-2">
                        <span class="text-xs font-bold text-amber-950">Format Layout:</span>
                        <div class="flex gap-2">
                            <button id="cust-layout-6-btn" onclick="setCustomizerLayout('6-grid')" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-400 text-amber-950 shadow transition-all cursor-pointer">
                                📸 6-Grid A5
                            </button>
                            <button id="cust-layout-3-btn" onclick="setCustomizerLayout('3-strip')" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-200/90 text-stone-700 hover:bg-stone-300 transition-all cursor-pointer">
                                🎞️ 3-Strip A5
                            </button>
                        </div>
                    </div>

                    <!-- 2. Active Templates Gallery (Checked by Admin) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>✨</span> Pilih Template Aktif
                            </label>
                            <span id="active-template-count" class="text-[11px] text-amber-800/70 font-medium">Memuat template...</span>
                        </div>
                        <div id="customizer-templates-list" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-[190px] overflow-y-auto p-1.5 bg-black/5 rounded-2xl border border-amber-500/20">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- 3. Photo Pool & Slot Picker -->
                    <div>
                        <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">
                            📷 Foto dari Sesi Ini (Klik Foto lalu Klik Slot di Kanan):
                        </label>
                        <div id="customizer-photo-pool" class="flex flex-wrap gap-2 p-2.5 bg-black/5 rounded-2xl border border-amber-500/20 min-h-[75px] items-center">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- 4. Slots Mapping -->
                    <div>
                        <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">
                            🎯 Susunan Slot Foto:
                        </label>
                        <div id="customizer-slots-grid" class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>
                </div>

                <!-- Right: Live Canvas Preview (5 cols) -->
                <div class="lg:col-span-5 flex flex-col items-center justify-between bg-black/10 p-4 rounded-2xl border border-amber-500/30">
                    <div class="w-full flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-amber-950">🖼️ Preview Hasil Jadi (A5):</span>
                        <span id="preview-status-text" class="text-[11px] text-amber-800/70 font-medium">Live Render</span>
                    </div>

                    <div class="relative flex-1 flex items-center justify-center max-h-[380px] w-full overflow-hidden p-2">
                        <canvas id="customizer-canvas" class="max-h-[360px] w-auto max-w-full object-contain rounded-xl shadow-2xl border-2 border-amber-500/40 bg-[#FFFDF5]"></canvas>
                    </div>

                    <!-- Action Buttons -->
                    <div class="w-full space-y-2 mt-4">
                        <button onclick="saveAndPrintCustomized()" id="cust-save-print-btn" class="w-full py-3 px-4 btn-gold rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-lg cursor-pointer hover:scale-[1.02] active:scale-95">
                            <span>🖨️</span> Simpan &amp; Kirim Antrean Cetak
                        </button>
                        <button onclick="downloadCustomizedStrip()" class="w-full py-2.5 px-4 bg-stone-800 hover:bg-stone-700 text-stone-100 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 border border-stone-600 cursor-pointer shadow hover:scale-[1.02] active:scale-95">
                            <span>📥</span> Unduh Foto Strip (JPEG)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT LOGIC -->
    <!-- ========================================================================= -->
    <script>
        // State
        let activeTemplates = [];
        let currentSessionPhotos = [];
        let currentCustomSessionId = '';
        let customizerLayoutMode = '6-grid'; // '6-grid' or '3-strip'
        let selectedTemplate = null;
        let selectedSlots = [0, 1, 2, 3, 4, 5];
        let activePoolIndex = null;

        // Image Cache
        const imgCache = new Map();
        function loadImg(src) {
            if (!src || typeof src !== 'string' || src.trim() === '') return Promise.resolve(null);
            if (imgCache.has(src)) {
                const c = imgCache.get(src);
                if (c && c.complete && c.naturalWidth > 0) return Promise.resolve(c);
            }
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => { imgCache.set(src, img); resolve(img); };
                img.onerror = () => { resolve(null); };
                img.src = src;
            });
        }

        // Toast Notification Helper
        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toast-content');
            toastContent.innerHTML = isSuccess 
                ? `<span class="text-xl">✅</span> <span>${message}</span>`
                : `<span class="text-xl">⚠️</span> <span>${message}</span>`;
            
            toastContent.className = isSuccess
                ? 'p-4 rounded-2xl shadow-2xl flex items-center gap-3 border text-sm font-medium bg-emerald-950/95 text-emerald-200 border-emerald-500/50'
                : 'p-4 rounded-2xl shadow-2xl flex items-center gap-3 border text-sm font-medium bg-rose-950/95 text-rose-200 border-rose-500/50';

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.remove('opacity-0', '-translate-y-4');
                toast.classList.add('opacity-100', 'translate-y-0');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', '-translate-y-4');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 3500);
        }

        // Search Filter
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase().trim();
                document.querySelectorAll('.session-card').forEach(card => {
                    const id = card.getAttribute('data-id').toLowerCase();
                    const date = card.getAttribute('data-date').toLowerCase();
                    if (id.includes(q) || date.includes(q)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        }

        // Fetch Active Templates on Load (Checked by Admin)
        async function loadActiveTemplates() {
            try {
                const res = await fetch('manage_templates.php?action=list&only_active=1&_t=' + Date.now());
                activeTemplates = await res.json();
                const countEl = document.getElementById('active-template-count');
                if (countEl) countEl.innerText = `${activeTemplates.length} Template Aktif`;
            } catch (err) {
                console.error("Gagal memuat template aktif:", err);
            }
        }

        // Quick Print from History Card
        async function quickPrint(sessionId, photoUrl) {
            if (!photoUrl) {
                showToast("File strip belum tersedia untuk sesi ini.", false);
                return;
            }
            try {
                const res = await fetch('print_action.php?action=request_print', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: sessionId,
                        photo_url: photoUrl,
                        label: 'Cetak Ulang dari History',
                        copies: 1
                    })
                });
                const data = await res.json();
                if (data.success) {
                    showToast("🖨️ Permintaan cetak berhasil dikirim ke antrean Admin!");
                } else {
                    throw new Error(data.error || "Gagal memproses print");
                }
            } catch (err) {
                showToast(err.message, false);
            }
        }

        // Open Customizer Modal
        function openCustomizer(sessionId, photos) {
            currentCustomSessionId = sessionId;
            currentSessionPhotos = photos || [];
            if (currentSessionPhotos.length === 0) {
                showToast("Tidak ada foto pada sesi ini", false);
                return;
            }

            // Initialize Slots
            selectedSlots = [0, 1, 2, 3, 4, 5].map(i => i % currentSessionPhotos.length);
            activePoolIndex = null;

            // Pick default template if none selected
            if (!selectedTemplate && activeTemplates.length > 0) {
                selectedTemplate = activeTemplates[0];
            }

            renderTemplatePicker();
            renderPhotoPool();
            renderSlotsUI();
            renderCustomizerCanvas();

            document.getElementById('customizer-modal').classList.remove('hidden');
        }

        function closeCustomizer() {
            document.getElementById('customizer-modal').classList.add('hidden');
        }

        function setCustomizerLayout(mode) {
            customizerLayoutMode = mode;
            const btn6 = document.getElementById('cust-layout-6-btn');
            const btn3 = document.getElementById('cust-layout-3-btn');
            if (mode === '6-grid') {
                btn6.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-400 text-slate-900 shadow transition-all cursor-pointer';
                btn3.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-700 text-slate-300 hover:bg-slate-600 transition-all cursor-pointer';
            } else {
                btn3.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-400 text-slate-900 shadow transition-all cursor-pointer';
                btn6.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-700 text-slate-300 hover:bg-slate-600 transition-all cursor-pointer';
            }
            renderSlotsUI();
            renderCustomizerCanvas();
        }

        function renderTemplatePicker() {
            const list = document.getElementById('customizer-templates-list');
            if (!list) return;

            if (activeTemplates.length === 0) {
                list.innerHTML = `<p class="col-span-3 text-center text-xs text-slate-400 p-3 italic">Belum ada template aktif dari Admin.</p>`;
                return;
            }

            list.innerHTML = activeTemplates.map(t => {
                const isSel = selectedTemplate && selectedTemplate.id === t.id;
                const thumb = t.outer || t.ketupat || t.lampu || t.rama || '';
                return `
                    <div onclick="selectCustomizerTemplate('${t.id}')" class="p-2 rounded-xl border transition-all cursor-pointer flex flex-col items-center justify-between text-center ${isSel ? 'bg-amber-500/20 border-amber-400 ring-2 ring-amber-400/50' : 'bg-slate-900 border-slate-700 hover:border-slate-500'}">
                        <div class="w-full aspect-[16/9] bg-slate-950 rounded-lg overflow-hidden mb-1.5 flex items-center justify-center">
                            ${thumb ? `<img src="${thumb}" class="w-full h-full object-cover">` : `<span class="text-sm">🖼️</span>`}
                        </div>
                        <span class="text-[11px] font-bold ${isSel ? 'text-amber-300' : 'text-slate-300'} truncate w-full">${t.name}</span>
                        <span class="text-[9px] text-slate-400 uppercase">${t.sizeType === 'a5_6grid' ? '6-Grid' : 'A5 Strip'}</span>
                    </div>
                `;
            }).join('');
        }

        window.selectCustomizerTemplate = function(id) {
            selectedTemplate = activeTemplates.find(t => t.id === id) || null;
            if (selectedTemplate) {
                if (selectedTemplate.sizeType === 'a5_6grid' || selectedTemplate.sizeType === '4r_6grid') {
                    customizerLayoutMode = '6-grid';
                } else {
                    customizerLayoutMode = '3-strip';
                }
                setCustomizerLayout(customizerLayoutMode);
            }
            renderTemplatePicker();
            renderCustomizerCanvas();
        };

        function renderPhotoPool() {
            const pool = document.getElementById('customizer-photo-pool');
            if (!pool) return;
            pool.innerHTML = currentSessionPhotos.map((url, idx) => {
                const isAct = activePoolIndex === idx;
                return `
                    <div onclick="selectPoolPhoto(${idx})" class="relative w-14 h-14 rounded-xl overflow-hidden cursor-pointer border-2 transition-all ${isAct ? 'border-amber-400 ring-2 ring-amber-400/80 scale-105' : 'border-slate-700 hover:border-slate-500'}">
                        <img src="${url}" class="w-full h-full object-cover">
                        <span class="absolute top-0.5 left-0.5 bg-black/80 text-[9px] font-bold text-amber-300 px-1 rounded">#${idx+1}</span>
                    </div>
                `;
            }).join('');
        }

        window.selectPoolPhoto = function(idx) {
            activePoolIndex = (activePoolIndex === idx) ? null : idx;
            renderPhotoPool();
        };

        function renderSlotsUI() {
            const grid = document.getElementById('customizer-slots-grid');
            if (!grid) return;
            const slotCount = customizerLayoutMode === '6-grid' ? 6 : 3;

            grid.innerHTML = '';
            for (let i = 0; i < slotCount; i++) {
                const photoIdx = selectedSlots[i] !== undefined ? selectedSlots[i] : (i % currentSessionPhotos.length);
                const photoUrl = currentSessionPhotos[photoIdx];
                const slotCard = document.createElement('div');
                slotCard.className = 'p-2 bg-slate-900 border border-slate-700 rounded-xl flex flex-col items-center justify-between cursor-pointer hover:border-amber-400 transition-all';
                slotCard.onclick = () => assignSlot(i);
                slotCard.innerHTML = `
                    <span class="text-[10px] font-bold text-amber-300 mb-1">Slot #${i+1}</span>
                    <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-slate-950 border border-slate-800 flex items-center justify-center">
                        ${photoUrl ? `<img src="${photoUrl}" class="w-full h-full object-cover">` : `<span class="text-xs text-slate-500">Kosong</span>`}
                    </div>
                    <span class="text-[9px] text-slate-400 mt-1 font-mono">Foto #${photoIdx + 1}</span>
                `;
                grid.appendChild(slotCard);
            }
        }

        function assignSlot(slotIdx) {
            if (activePoolIndex !== null) {
                selectedSlots[slotIdx] = activePoolIndex;
                activePoolIndex = null;
                renderPhotoPool();
            } else {
                // Rotate to next photo
                selectedSlots[slotIdx] = (selectedSlots[slotIdx] + 1) % currentSessionPhotos.length;
            }
            renderSlotsUI();
            renderCustomizerCanvas();
        }

        // Live Canvas Renderer (A5 Resolution 1748 x 2480 at 300 DPI)
        async function renderCustomizerCanvas() {
            const canvas = document.getElementById('customizer-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            canvas.width = 1748;
            canvas.height = 2480;

            const t = selectedTemplate;
            const isOverlay = t && t.overlayMode;

            // 1. Draw Background
            if (!isOverlay) {
                let bgSource = (t && t.outer) ? t.outer : './gambar/background.png';
                const bgImg = await loadImg(bgSource);
                if (bgImg) {
                    ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
                } else {
                    ctx.fillStyle = '#FFFDF5';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }
            } else {
                ctx.fillStyle = '#FFFDF5';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }

            // 2. Draw Photos according to layout
            if (customizerLayoutMode === '6-grid') {
                const paddingX = 94;
                const gapX = 40;
                const gapY = 45;
                const imgW = Math.round((canvas.width - (2 * paddingX) - gapX) / 2); // ~760
                const imgH = Math.round(imgW * (450 / 920)); // ~372
                const totalGridH = (3 * imgH) + (2 * gapY); // ~1206
                const topY = Math.round((canvas.height - totalGridH) / 2); // ~637

                for (let i = 0; i < 6; i++) {
                    const pIdx = selectedSlots[i] !== undefined ? selectedSlots[i] : (i % currentSessionPhotos.length);
                    const photoUrl = currentSessionPhotos[pIdx];
                    if (!photoUrl) continue;

                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    const posX = paddingX + col * (imgW + gapX);
                    const posY = topY + row * (imgH + gapY);

                    const img = await loadImg(photoUrl);
                    if (img) {
                        ctx.save();
                        ctx.beginPath();
                        ctx.roundRect(posX, posY, imgW, imgH, 24);
                        ctx.clip();
                        ctx.drawImage(img, posX, posY, imgW, imgH);
                        ctx.restore();

                        ctx.strokeStyle = '#D4AF37';
                        ctx.lineWidth = 6;
                        ctx.beginPath();
                        ctx.roundRect(posX, posY, imgW, imgH, 24);
                        ctx.stroke();
                    }
                }

                // Ornaments for 6-Grid
                const drawOrn = async (type, slotIdx) => {
                    const src = (t && t[type]) ? t[type] : `./gambar/${type === 'rama' ? 'rama.png' : type + '.webp'}`;
                    const img = await loadImg(src);
                    if (!img) return;
                    const layout = (t && t.layout && t.layout[type]) ? t.layout[type] : { size: 350, x: 0, y: 0 };
                    const size = parseInt(layout.size) || 0;
                    if (size <= 0) return;

                    const col = slotIdx % 2;
                    const row = Math.floor(slotIdx / 2);
                    const slotX = paddingX + col * (imgW + gapX);
                    const slotY = topY + row * (imgH + gapY);

                    let x, y;
                    if (type === 'lampu') x = slotX + parseInt(layout.x || 0);
                    else x = slotX + imgW - size + parseInt(layout.x || 0);
                    y = slotY + imgH - size + parseInt(layout.y || 0);

                    ctx.drawImage(img, x, y, size, size);
                };

                await drawOrn('ketupat', 1);
                await drawOrn('lampu', 2);
                await drawOrn('rama', 5);

            } else {
                // 3-Strip Vertical on A5
                const imgW = 1540;
                const imgH = 650;
                const padding = 104;
                const headerH = 180;
                const gap = 60;

                for (let i = 0; i < 3; i++) {
                    const pIdx = selectedSlots[i] !== undefined ? selectedSlots[i] : (i % currentSessionPhotos.length);
                    const photoUrl = currentSessionPhotos[pIdx];
                    if (!photoUrl) continue;
                    const yPos = padding + headerH + (i * (imgH + gap));

                    const img = await loadImg(photoUrl);
                    if (img) {
                        ctx.save();
                        ctx.beginPath();
                        ctx.roundRect(padding, yPos, imgW, imgH, 32);
                        ctx.clip();
                        ctx.drawImage(img, padding, yPos, imgW, imgH);
                        ctx.restore();

                        ctx.strokeStyle = '#D4AF37';
                        ctx.lineWidth = 6;
                        ctx.beginPath();
                        ctx.roundRect(padding, yPos, imgW, imgH, 32);
                        ctx.stroke();
                    }
                }

                // Ornaments for 3-Strip
                const drawOrn3 = async (type, index) => {
                    const src = (t && t[type]) ? t[type] : `./gambar/${type === 'rama' ? 'rama.png' : type + '.webp'}`;
                    const img = await loadImg(src);
                    if (!img) return;
                    const layout = (t && t.layout && t.layout[type]) ? t.layout[type] : { size: 350, x: 0, y: 0 };
                    const size = parseInt(layout.size) || 0;
                    if (size <= 0) return;

                    const yPos = padding + headerH + (index * (imgH + gap));
                    let x, y;
                    if (type === 'lampu') x = padding + parseInt(layout.x || 0);
                    else x = padding + imgW - size + parseInt(layout.x || 0);
                    y = yPos + imgH - size + parseInt(layout.y || 0);

                    ctx.drawImage(img, x, y, size, size);
                };

                await drawOrn3('ketupat', 0);
                await drawOrn3('lampu', 1);
                await drawOrn3('rama', 2);
            }

            // Overlay mode draw
            if (isOverlay && t && t.outer) {
                const ovImg = await loadImg(t.outer);
                if (ovImg) ctx.drawImage(ovImg, 0, 0, canvas.width, canvas.height);
            }
        }

        // Save & Print Customized Strip
        async function saveAndPrintCustomized() {
            const btn = document.getElementById('cust-save-print-btn');
            btn.disabled = true;
            btn.innerText = 'Mengunggah & Mengirim Cetak...';

            try {
                const canvas = document.getElementById('customizer-canvas');
                const stripDataUrl = canvas.toDataURL('image/jpeg', 0.92);

                const res = await fetch('upload.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: currentCustomSessionId,
                        images: [stripDataUrl]
                    })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error || "Gagal upload strip");

                const stripFile = data.saved_files ? data.saved_files[0] : 'round_custom_strip.jpeg';
                const photoUrl = `uploads/${data.session_id}/${stripFile}`;

                // Send to print queue
                const templateName = selectedTemplate ? selectedTemplate.name : 'Tema Kustom';
                await fetch('print_action.php?action=request_print', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: data.session_id,
                        photo_url: photoUrl,
                        label: `${templateName} (${customizerLayoutMode === '6-grid' ? '6-Grid A5' : '3-Strip A5'})`,
                        copies: 1
                    })
                });

                showToast("🖨️ Strip foto berhasil dibuat dan dikirim ke antrean cetak!");
                setTimeout(() => closeCustomizer(), 1500);

            } catch (err) {
                showToast("Gagal: " + err.message, false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<span>🖨️</span> Simpan &amp; Kirim Antrean Cetak`;
            }
        }

        // Download Customized Strip
        function downloadCustomizedStrip() {
            try {
                const canvas = document.getElementById('customizer-canvas');
                const link = document.createElement('a');
                link.download = `photobooth_${currentCustomSessionId}_custom.jpg`;
                link.href = canvas.toDataURL('image/jpeg', 0.95);
                link.click();
                showToast("📥 Foto strip berhasil diunduh!");
            } catch (err) {
                showToast("Gagal unduh: " + err.message, false);
            }
        }

        // Initialize on page load
        window.addEventListener('load', () => {
            loadActiveTemplates();
        });
    </script>
</body>
</html>

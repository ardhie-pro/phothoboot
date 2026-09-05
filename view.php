<?php
// Gallery view page - accessed via QR code scan
$sessionId = isset($_GET['s']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['s']) : '';

if (empty($sessionId)) {
    http_response_code(400);
    echo '<!DOCTYPE html><html><body style="background:#2D5A27;color:#FFFDF5;text-align:center;padding:50px;font-family:sans-serif;"><h1>Session tidak ditemukan</h1></body></html>';
    exit();
}

$sessionDir = __DIR__ . '/uploads/' . $sessionId . '/';

if (!is_dir($sessionDir)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body style="background:#2D5A27;color:#FFFDF5;text-align:center;padding:50px;font-family:sans-serif;"><h1>Foto tidak ditemukan</h1><p>Session ini mungkin sudah dihapus.</p></body></html>';
    exit();
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
$badgeText = $boothTitle . ($boothSubtitle ? ' - ' . $boothSubtitle : '');

// Get all image files and organize by round
$rawList = @scandir($sessionDir) ?: [];
$rounds = [];

foreach ($rawList as $fname) {
    if ($fname === '.' || $fname === '..') continue;
    $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
    if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) continue;

    $url = 'uploads/' . $sessionId . '/' . $fname;

    if (preg_match('/round_(\d+)_(strip|photo_\d+)/i', $fname, $m)) {
        $roundNum = (int)$m[1];
        $type = strtolower($m[2]);
        if (!isset($rounds[$roundNum])) {
            $rounds[$roundNum] = ['photos' => [], 'strip' => null];
        }
        if ($type === 'strip') {
            $rounds[$roundNum]['strip'] = $url;
        } else {
            $rounds[$roundNum]['photos'][] = $url;
        }
    } else if (stripos($fname, 'strip') !== false) {
        // Default strip for round 1
        if (!isset($rounds[1])) {
            $rounds[1] = ['photos' => [], 'strip' => null];
        }
        $rounds[1]['strip'] = $url;
    } else {
        // Individual photo (photo_1.png, etc)
        if (!isset($rounds[1])) {
            $rounds[1] = ['photos' => [], 'strip' => null];
        }
        $rounds[1]['photos'][] = $url;
    }
}

// Sort rounds by number
ksort($rounds);

// Sort photos within each round
foreach ($rounds as &$round) {
    natsort($round['photos']);
    $round['photos'] = array_values($round['photos']);
}
unset($round);

$totalRounds = count($rounds);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                radial-gradient(circle at 10% 20%, rgba(212, 140, 18, 0.18) 0%, transparent 35%),
                radial-gradient(circle at 90% 80%, rgba(212, 140, 18, 0.18) 0%, transparent 35%);
            <?php endif; ?>
            color: #FFFDF5;
            min-height: 100vh;
            padding-bottom: 120px;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .glass-card {
            background: rgba(255, 253, 245, 0.94);
            backdrop-filter: blur(20px);
            border: 1.5px solid rgba(212, 140, 18, 0.25);
            box-shadow: 0 25px 50px -12px rgba(45, 90, 39, 0.3);
            color: var(--booth-secondary);
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #FFFDF5 0%, #FDE68A 45%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--booth-primary) 0%, #B4730A 100%);
            color: #FFFDF5;
            box-shadow: 0 8px 20px -4px rgba(212, 140, 18, 0.4);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(212, 140, 18, 0.6);
        }

        .btn-gold:active {
            transform: translateY(0);
        }

        .btn-emerald {
            background: linear-gradient(135deg, var(--booth-bg) 0%, #173814 100%);
            color: #FFFDF5;
            box-shadow: 0 8px 20px -4px rgba(45, 90, 39, 0.4);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-emerald:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(45, 90, 39, 0.6);
        }

        .btn-emerald:active {
            transform: translateY(0);
        }

        /* Pulse animation for pending print */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .animate-pulse-ring {
            animation: pulse-ring 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="p-4 sm:p-6 md:p-8">

    <div class="max-w-xl mx-auto">
        <!-- Header -->
        <header class="text-center py-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/20 border border-amber-300/40 text-amber-100 text-xs font-bold uppercase tracking-wider mb-3 shadow-sm backdrop-blur-md">
                ✨ <?= htmlspecialchars($badgeText) ?> ✨
            </div>
            <h1 class="font-playfair text-3xl sm:text-4xl font-bold gold-gradient-text mb-2 drop-shadow-md">Kenangan Fotomu</h1>
            <p class="text-amber-100/90 text-sm max-w-md mx-auto mb-5 drop-shadow-sm">
                Pilih foto atau Photo Strip untuk didownload atau dikirim ke operator booth untuk dicetak fisik!
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="history.php" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-black/60 hover:bg-black/80 border-2 border-amber-400/40 text-amber-200 text-xs font-bold transition-all shadow-lg backdrop-blur-md">
                    <span>🎞️</span> Riwayat Semua Sesi Foto
                </a>
                <a href="index.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-emerald-900/80 hover:bg-emerald-800 border-2 border-emerald-400/40 text-emerald-100 text-xs font-bold transition-all shadow-lg backdrop-blur-md">
                    <span>📸</span> Ke Booth Foto
                </a>
            </div>
        </header>

        <!-- Notification Toast -->
        <div id="toast" class="fixed top-5 left-1/2 -translate-x-1/2 z-[150] hidden transition-all duration-300 transform -translate-y-4 opacity-0 max-w-sm w-full px-4">
            <div id="toast-content" class="p-4 rounded-2xl shadow-2xl flex items-center gap-3 border text-sm font-medium"></div>
        </div>

        <?php if (empty($rounds)): ?>
            <div class="glass-card rounded-3xl p-12 text-center mt-6">
                <p class="text-lg font-bold text-amber-900">😕 Tidak ada foto ditemukan pada sesi ini.</p>
                <p class="text-xs text-amber-800/80 mt-2">Mungkin sesi ini sudah berakhir atau file telah dibersihkan.</p>
            </div>
        <?php else: ?>

            <div class="space-y-8 mt-4">
                <?php foreach ($rounds as $roundNum => $round): ?>
                    <section class="glass-card rounded-[2rem] p-5 sm:p-6 relative overflow-hidden">
                        <!-- Round Header -->
                        <div class="flex items-center justify-between border-b border-amber-900/15 pb-4 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center font-bold text-amber-800 text-sm">
                                    #<?= $roundNum ?>
                                </div>
                                <div>
                                    <h2 class="font-playfair text-lg sm:text-xl font-bold text-amber-950">Sesi Foto ke-<?= $roundNum ?></h2>
                                    <p class="text-[11px] text-amber-800/70 font-medium"><?= count($round['photos']) ?> Pose Foto + 1 Photo Strip</p>
                                </div>
                            </div>
                        </div>

                        <!-- 1. Photo Strip (Primary Feature) -->
                        <?php if ($round['strip']): ?>
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-800 flex items-center gap-1.5">
                                        🎞️ Photo Strip Siap Cetak
                                    </span>
                                    <span class="text-[11px] text-amber-700/80 font-semibold">Ukuran Pas Cetak</span>
                                </div>

                                <div class="relative group rounded-2xl overflow-hidden border-2 border-amber-500/30 bg-black/10 shadow-lg p-1.5">
                                    <img src="<?= $round['strip'] ?>" 
                                         alt="Strip Sesi <?= $roundNum ?>" 
                                         loading="lazy"
                                         decoding="async"
                                         class="w-full h-auto object-contain max-h-[480px] mx-auto rounded-xl cursor-pointer hover:scale-[1.01] transition-transform"
                                         onclick="openModal('<?= $round['strip'] ?>', 'Sesi_<?= $roundNum ?>_Strip', 'Sesi <?= $roundNum ?> - Photo Strip')">
                                    
                                    <!-- Print Status Badge if exists -->
                                    <div id="badge-<?= md5($round['strip']) ?>" class="hidden absolute top-3 right-3 z-10"></div>
                                </div>

                                <!-- Action Buttons for Strip -->
                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <button onclick="requestPrint('<?= $round['strip'] ?>', 'Sesi <?= $roundNum ?> - Photo Strip', this)"
                                            class="btn-emerald py-3 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-95 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        <span>Cetak Strip</span>
                                    </button>

                                    <a href="<?= $round['strip'] ?>" download="Sesi_<?= $roundNum ?>_Photo_Strip.png"
                                       class="btn-gold py-3 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 text-center hover:scale-[1.02] active:scale-95 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- 2. Individual Photos Grid -->
                        <?php if (!empty($round['photos'])): ?>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-amber-800 mb-2.5">
                                    📷 Foto Per-Pose (Original)
                                </div>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <?php foreach ($round['photos'] as $i => $photo): ?>
                                        <div class="relative group rounded-xl overflow-hidden border border-amber-600/30 bg-black/10 aspect-[8/5]">
                                            <img src="<?= $photo ?>" 
                                                 alt="Pose <?= $i + 1 ?>" 
                                                 loading="lazy"
                                                 decoding="async"
                                                 class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-all duration-300"
                                                 onclick="openModal('<?= $photo ?>', 'Sesi_<?= $roundNum ?>_Foto_<?= $i + 1 ?>', 'Sesi <?= $roundNum ?> - Pose <?= $i + 1 ?>')">
                                            
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-between p-2 pointer-events-none">
                                                <span class="text-[10px] font-bold text-amber-300">Pose <?= $i + 1 ?></span>
                                            </div>

                                            <div id="badge-<?= md5($photo) ?>" class="hidden absolute top-1.5 right-1.5 z-10"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <!-- Footer -->
        <footer class="text-center mt-10 text-xs text-amber-200/70 drop-shadow-sm">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($boothTitle) ?> - Photo Booth Experience
        </footer>
    </div>

    <!-- Preview & Print Modal -->
    <div id="preview-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/85 backdrop-blur-md hidden">
        <div class="glass-card max-w-lg w-full rounded-[2.5rem] p-5 sm:p-6 border-2 border-amber-500/40 flex flex-col items-center relative overflow-hidden shadow-2xl">
            <!-- Close Button -->
            <button onclick="closeModal()" class="absolute top-4 right-4 p-2 text-stone-600 hover:text-stone-900 rounded-full bg-stone-200/80 hover:bg-stone-300 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 id="modal-title" class="font-playfair text-xl font-bold text-amber-950 mb-3 text-center">Preview Foto</h3>

            <!-- Modal Image Container -->
            <div class="w-full max-h-[60vh] overflow-y-auto rounded-2xl border border-amber-500/30 bg-black/70 mb-5 p-2 flex items-center justify-center">
                <img id="modal-img" src="" alt="Preview" class="w-full h-auto object-contain max-h-[56vh] rounded-xl">
            </div>

            <!-- Modal Action Buttons -->
            <div class="w-full grid grid-cols-2 gap-3">
                <button id="modal-print-btn" onclick="triggerModalPrint()"
                        class="btn-emerald py-3.5 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-95 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>🖨️ Cetak Foto Ini</span>
                </button>

                <a id="modal-download" href="" download=""
                   class="btn-gold py-3.5 px-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 text-center hover:scale-[1.02] active:scale-95 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>📥 Download</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        const sessionId = "<?= $sessionId ?>";
        const modal = document.getElementById('preview-modal');
        const modalImg = document.getElementById('modal-img');
        const modalTitle = document.getElementById('modal-title');
        const modalDownload = document.getElementById('modal-download');
        const modalPrintBtn = document.getElementById('modal-print-btn');

        let currentModalUrl = '';
        let currentModalLabel = '';

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const content = document.getElementById('toast-content');
            
            if (type === 'success') {
                content.className = 'p-4 rounded-2xl shadow-2xl flex items-center gap-3 bg-emerald-950/90 border border-emerald-500/50 text-emerald-200 text-sm font-medium';
                content.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-lg shrink-0">✓</div>
                    <div>${message}</div>
                `;
            } else if (type === 'warning') {
                content.className = 'p-4 rounded-2xl shadow-2xl flex items-center gap-3 bg-amber-950/90 border border-amber-500/50 text-amber-200 text-sm font-medium';
                content.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-lg shrink-0">!</div>
                    <div>${message}</div>
                `;
            } else {
                content.className = 'p-4 rounded-2xl shadow-2xl flex items-center gap-3 bg-rose-950/90 border border-rose-500/50 text-rose-200 text-sm font-medium';
                content.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-lg shrink-0">✕</div>
                    <div>${message}</div>
                `;
            }

            toast.classList.remove('hidden', '-translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            setTimeout(() => {
                toast.classList.add('-translate-y-4', 'opacity-0');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 4000);
        }

        function openModal(src, downloadName, label) {
            currentModalUrl = src;
            currentModalLabel = label || 'Foto Kenangan';
            modalImg.src = src;
            modalTitle.innerText = currentModalLabel;
            modalDownload.href = src;
            modalDownload.download = downloadName + '.png';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        function triggerModalPrint() {
            requestPrint(currentModalUrl, currentModalLabel, modalPrintBtn);
        }

        // Request Print function
        async function requestPrint(photoUrl, label, btnElement) {
            if (!photoUrl || !sessionId) return;

            const originalHtml = btnElement ? btnElement.innerHTML : null;
            if (btnElement) {
                btnElement.disabled = true;
                btnElement.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Mengirim...</span>
                `;
            }

            try {
                const res = await fetch('print_action.php?action=request_print', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: sessionId,
                        photo_url: photoUrl,
                        label: label,
                        copies: 1
                    })
                });

                const data = await res.json();
                if (data.success) {
                    showToast(data.message || 'Permintaan cetak berhasil dikirim ke Admin Booth! Silakan tunggu hasil cetak.', 'success');
                    pollSessionPrintStatus();
                } else {
                    showToast(data.error || 'Gagal mengirim permintaan cetak.', 'error');
                }
            } catch (err) {
                console.error("Print request error:", err);
                showToast('Koneksi bermasalah. Pastikan terhubung dengan server.', 'error');
            } finally {
                if (btnElement && originalHtml) {
                    btnElement.disabled = false;
                    btnElement.innerHTML = originalHtml;
                }
            }
        }

        // MD5 utility for badge element targeting
        function md5(str) {
            // simple quick hash for dom id
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = ((hash << 5) - hash) + str.charCodeAt(i);
                hash |= 0;
            }
            return 'h' + Math.abs(hash);
        }

        // Poll session queue status to update UI badges with robust matching
        let pollCount = 0;
        let pollInterval = null;

        async function pollSessionPrintStatus() {
            pollCount++;
            // Stop polling after 40 polls (approx 3.5 minutes) to save server resources
            if (pollCount > 40 && pollInterval) {
                clearInterval(pollInterval);
                return;
            }

            try {
                const res = await fetch(`print_action.php?action=get_session_queue&session_id=${sessionId}&_t=${Date.now()}`);
                const data = await res.json();
                if (!data.success || !data.items || data.items.length === 0) return;

                let allCompleted = true;

                data.items.forEach(item => {
                    if (item.status !== 'completed' && item.status !== 'cancelled') {
                        allCompleted = false;
                    }

                    // Robust matching: match exact URL or filename substring
                    const itemFilename = item.photo_url.split('/').pop();
                    const imgs = document.querySelectorAll('img');
                    
                    imgs.forEach(img => {
                        const src = img.getAttribute('src') || '';
                        if (src === item.photo_url || src.endsWith('/' + itemFilename) || (itemFilename && src.includes(itemFilename))) {
                            const parent = img.closest('.relative');
                            if (parent) {
                                let badge = parent.querySelector('.print-status-badge');
                                if (!badge) {
                                    badge = document.createElement('div');
                                    parent.appendChild(badge);
                                }

                                if (item.status === 'pending') {
                                    badge.className = 'print-status-badge absolute top-3 right-3 z-10 px-2.5 py-1 rounded-full text-[11px] font-bold shadow-lg flex items-center gap-1.5 backdrop-blur-md bg-amber-500/90 text-slate-900 animate-pulse-ring';
                                    badge.innerHTML = `<span>⏳</span><span>Menunggu Cetak</span>`;
                                } else if (item.status === 'printing') {
                                    badge.className = 'print-status-badge absolute top-3 right-3 z-10 px-2.5 py-1 rounded-full text-[11px] font-bold shadow-lg flex items-center gap-1.5 backdrop-blur-md bg-blue-500/90 text-white animate-pulse';
                                    badge.innerHTML = `<span>🖨️</span><span>Sedang Dicetak</span>`;
                                } else if (item.status === 'completed') {
                                    badge.className = 'print-status-badge absolute top-3 right-3 z-10 px-2.5 py-1 rounded-full text-[11px] font-bold shadow-lg flex items-center gap-1.5 backdrop-blur-md bg-emerald-500/90 text-white';
                                    badge.innerHTML = `<span>✅</span><span>Selesai Dicetak</span>`;
                                }
                            }
                        }
                    });
                });

                // If all printed, stop polling to avoid unnecessary server load
                if (allCompleted && data.items.length > 0 && pollInterval) {
                    clearInterval(pollInterval);
                }
            } catch (err) {
                // silent
            }
        }

        // Initial poll and smart polling every 5s
        pollSessionPrintStatus();
        pollInterval = setInterval(pollSessionPrintStatus, 5000);
    </script>
</body>
</html>

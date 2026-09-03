<?php
// Gallery view page - accessed via QR code scan
$sessionId = isset($_GET['s']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['s']) : '';

if (empty($sessionId)) {
    http_response_code(400);
    echo '<!DOCTYPE html><html><body style="background:#1a1a2e;color:#fff;text-align:center;padding:50px;font-family:sans-serif;"><h1>Session tidak ditemukan</h1></body></html>';
    exit();
}

$sessionDir = __DIR__ . '/uploads/' . $sessionId . '/';

if (!is_dir($sessionDir)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body style="background:#1a1a2e;color:#fff;text-align:center;padding:50px;font-family:sans-serif;"><h1>Foto tidak ditemukan</h1><p>Session ini mungkin sudah dihapus.</p></body></html>';
    exit();
}

// Get all image files and organize by round
$files = glob($sessionDir . '*.{png,jpg,jpeg,gif}', GLOB_BRACE);
$rounds = [];

foreach ($files as $file) {
    $basename = basename($file);
    $url = 'uploads/' . $sessionId . '/' . $basename;

    if (preg_match('/round_(\d+)_(strip|photo_\d+)/', $basename, $m)) {
        $roundNum = (int)$m[1];
        $type = $m[2];
        if (!isset($rounds[$roundNum])) {
            $rounds[$roundNum] = ['photos' => [], 'strip' => null];
        }
        if ($type === 'strip') {
            $rounds[$roundNum]['strip'] = $url;
        } else {
            $rounds[$roundNum]['photos'][] = $url;
        }
    }
}

// Sort rounds by number
ksort($rounds);

// Sort photos within each round
foreach ($rounds as &$round) {
    sort($round['photos']);
}
unset($round);

$totalRounds = count($rounds);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📸 Kenangan Photo Booth</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(212,175,55,0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(45,90,39,0.2) 0%, transparent 40%);
            color: #FFFDF5;
            min-height: 100vh;
            padding-bottom: 120px;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #FDE68A 0%, #D4AF37 50%, #B4730A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #B4730A 100%);
            color: #FFFDF5;
            box-shadow: 0 8px 20px -4px rgba(212, 175, 55, 0.4);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(212, 175, 55, 0.6);
        }

        .btn-gold:active {
            transform: translateY(0);
        }

        .btn-emerald {
            background: linear-gradient(135deg, #10B981 0%, #047857 100%);
            color: #FFFDF5;
            box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.4);
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
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-semibold uppercase tracking-wider mb-3">
                ✨ Ramadan Kareem Photo Booth ✨
            </div>
            <h1 class="font-playfair text-3xl sm:text-4xl font-bold gold-gradient-text mb-2">Kenangan Fotomu</h1>
            <p class="text-slate-400 text-sm max-w-md mx-auto mb-4">
                Pilih foto atau Photo Strip untuk didownload atau dikirim ke operator booth untuk dicetak fisik!
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="history.php" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-slate-800/90 hover:bg-slate-700 border border-amber-500/30 text-amber-300 text-xs font-bold transition-all shadow-md">
                    <span>🎞️</span> Riwayat Semua Sesi Foto
                </a>
                <a href="index.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-emerald-800/80 hover:bg-emerald-700 border border-emerald-500/30 text-emerald-200 text-xs font-bold transition-all shadow-md">
                    <span>📸</span> Ke Booth Foto
                </a>
            </div>
        </header>

        <!-- Notification Toast -->
        <div id="toast" class="fixed top-5 left-1/2 -translate-x-1/2 z-[150] hidden transition-all duration-300 transform -translate-y-4 opacity-0 max-w-sm w-full px-4">
            <div id="toast-content" class="p-4 rounded-2xl shadow-2xl flex items-center gap-3 border text-sm font-medium"></div>
        </div>

        <?php if (empty($rounds)): ?>
            <div class="glass-card rounded-3xl p-12 text-center text-slate-400 mt-6">
                <p class="text-lg font-semibold">😕 Tidak ada foto ditemukan pada sesi ini.</p>
                <p class="text-xs mt-2">Mungkin sesi ini sudah berakhir atau file telah dibersihkan.</p>
            </div>
        <?php else: ?>

            <div class="space-y-8 mt-4">
                <?php foreach ($rounds as $roundNum => $round): ?>
                    <section class="glass-card rounded-[2rem] p-5 sm:p-6 relative overflow-hidden">
                        <!-- Round Header -->
                        <div class="flex items-center justify-between border-b border-slate-700/60 pb-4 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center font-bold text-amber-400 text-sm">
                                    #<?= $roundNum ?>
                                </div>
                                <div>
                                    <h2 class="font-playfair text-lg sm:text-xl font-bold text-slate-100">Sesi Foto ke-<?= $roundNum ?></h2>
                                    <p class="text-[11px] text-slate-400"><?= count($round['photos']) ?> Pose Foto + 1 Photo Strip</p>
                                </div>
                            </div>
                        </div>

                        <!-- 1. Photo Strip (Primary Feature) -->
                        <?php if ($round['strip']): ?>
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                                        🎞️ Photo Strip Siap Cetak
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium">Ukuran Pas Cetak</span>
                                </div>

                                <div class="relative group rounded-2xl overflow-hidden border-2 border-amber-500/30 bg-slate-900/60 shadow-xl">
                                    <img src="<?= $round['strip'] ?>" 
                                         alt="Strip Sesi <?= $roundNum ?>" 
                                         class="w-full h-auto object-contain max-h-[480px] mx-auto cursor-pointer hover:scale-[1.01] transition-transform"
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
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
                                    📷 Foto Per-Pose (Original)
                                </div>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <?php foreach ($round['photos'] as $i => $photo): ?>
                                        <div class="relative group rounded-xl overflow-hidden border border-slate-700 bg-slate-900/50 aspect-[8/5]">
                                            <img src="<?= $photo ?>" 
                                                 alt="Pose <?= $i + 1 ?>" 
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
        <footer class="text-center mt-10 text-xs text-slate-500">
            &copy; 2026 Ramadan Kareem Photo Booth Experience
        </footer>
    </div>

    <!-- Preview & Print Modal -->
    <div id="preview-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md hidden">
        <div class="glass-card max-w-lg w-full rounded-[2.5rem] p-5 sm:p-6 border border-amber-500/30 flex flex-col items-center relative overflow-hidden">
            <!-- Close Button -->
            <button onclick="closeModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-white rounded-full bg-slate-800/80 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 id="modal-title" class="font-playfair text-xl font-bold gold-gradient-text mb-3 text-center">Preview Foto</h3>

            <!-- Modal Image Container -->
            <div class="w-full max-h-[60vh] overflow-y-auto rounded-2xl border border-slate-700/80 bg-slate-900/80 mb-5 p-2 flex items-center justify-center">
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

        // Poll session queue status to update UI badges
        async function pollSessionPrintStatus() {
            try {
                const res = await fetch(`print_action.php?action=get_session_queue&session_id=${sessionId}`);
                const data = await res.json();
                if (!data.success || !data.items) return;

                data.items.forEach(item => {
                    // Update badges if elements exist
                    const photoUrls = document.querySelectorAll(`img[src="${item.photo_url}"]`);
                    photoUrls.forEach(img => {
                        const parent = img.closest('.relative');
                        if (parent) {
                            let badge = parent.querySelector('.print-status-badge');
                            if (!badge) {
                                badge = document.createElement('div');
                                badge.className = 'print-status-badge absolute top-3 right-3 z-10 px-2.5 py-1 rounded-full text-[11px] font-bold shadow-lg flex items-center gap-1.5 backdrop-blur-md';
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
                    });
                });
            } catch (err) {
                // silent
            }
        }

        // Initial poll and recurring poll every 5s
        pollSessionPrintStatus();
        setInterval(pollSessionPrintStatus, 5000);
    </script>
</body>
</html>

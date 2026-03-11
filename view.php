<?php
// Gallery view page - accessed via QR code scan
$sessionId = isset($_GET['s']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['s']) : '';

if (empty($sessionId)) {
    http_response_code(400);
    echo '<!DOCTYPE html><html><body><h1>Session tidak ditemukan</h1></body></html>';
    exit();
}

$sessionDir = __DIR__ . '/uploads/' . $sessionId . '/';

if (!is_dir($sessionDir)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><h1>Foto tidak ditemukan</h1><p>Session ini mungkin sudah dihapus.</p></body></html>';
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
    <title>📸 Foto Booth - Kenanganmu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #1a1a2e;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(212,175,55,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(212,140,18,0.08) 0%, transparent 50%);
            color: #FFFDF5;
            min-height: 100vh;
            padding: 16px;
            padding-bottom: 100px;
        }

        .header {
            text-align: center;
            padding: 24px 0 16px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #D4AF37, #F0D060, #D4AF37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 6px;
        }

        .header p {
            color: rgba(255,253,245,0.5);
            font-size: 0.85rem;
        }

        .round-section {
            max-width: 600px;
            margin: 0 auto 24px;
        }

        .round-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: #D4AF37;
            margin-bottom: 12px;
            padding-left: 12px;
            border-left: 3px solid #D4AF37;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .round-title .badge {
            background: rgba(212,175,55,0.15);
            color: #D4AF37;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }

        /* Photo Grid */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .photo-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255,253,245,0.05);
            border: 1px solid rgba(212,175,55,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            aspect-ratio: 8/5;
        }

        .photo-card:hover, .photo-card:active {
            transform: translateY(-2px);
            border-color: rgba(212,175,55,0.5);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .photo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-card .label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 4px 8px;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            font-size: 0.65rem;
            font-weight: 600;
            color: #D4AF37;
        }

        /* Strip Card */
        .strip-card {
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255,253,245,0.05);
            border: 1px solid rgba(212,175,55,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .strip-card:hover, .strip-card:active {
            border-color: rgba(212,175,55,0.5);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .strip-card img {
            width: 100%;
            height: auto;
            display: block;
        }

        .strip-card .strip-label {
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .strip-card .strip-label span {
            font-weight: 600;
            color: #D4AF37;
            font-size: 0.8rem;
        }

        .dl-hint {
            font-size: 0.65rem;
            color: rgba(255,253,245,0.35);
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid rgba(212,175,55,0.1);
            margin: 20px auto;
            max-width: 600px;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 100;
            background: rgba(0,0,0,0.95);
            backdrop-filter: blur(10px);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-close {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
            z-index: 10;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.1);
        }

        .modal-img-container {
            max-width: 95vw;
            max-height: 75vh;
            overflow: auto;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .modal-img-container img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 12px;
        }

        .modal-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 36px;
            background: linear-gradient(135deg, #D4AF37, #B4730A);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(212,175,55,0.3);
        }

        .modal-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(212,175,55,0.4);
        }

        .modal-download svg {
            width: 20px;
            height: 20px;
        }

        .empty {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255,253,245,0.4);
        }

        .footer {
            text-align: center;
            padding: 30px 0 10px;
            color: rgba(255,253,245,0.2);
            font-size: 0.7rem;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>✨ Kenanganmu ✨</h1>
        <p><?= $totalRounds ?> sesi foto · Tap untuk preview & download</p>
    </div>

    <?php if (empty($rounds)): ?>
        <div class="empty">
            <p>😕 Tidak ada foto ditemukan.</p>
        </div>
    <?php else: ?>

        <?php foreach ($rounds as $roundNum => $round): ?>
            <div class="round-section">
                <div class="round-title">
                    📷 Sesi <?= $roundNum ?>
                    <span class="badge"><?= count($round['photos']) ?> foto</span>
                </div>

                <?php if (!empty($round['photos'])): ?>
                    <div class="photo-grid">
                        <?php foreach ($round['photos'] as $i => $photo): ?>
                            <div class="photo-card" onclick="openModal('<?= $photo ?>', 'Sesi_<?= $roundNum ?>_Foto_<?= $i + 1 ?>')">
                                <img src="<?= $photo ?>" alt="Foto <?= $i + 1 ?>" loading="lazy">
                                <div class="label">Foto <?= $i + 1 ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($round['strip']): ?>
                    <div class="strip-card" onclick="openModal('<?= $round['strip'] ?>', 'Sesi_<?= $roundNum ?>_Strip')">
                        <img src="<?= $round['strip'] ?>" alt="Strip Sesi <?= $roundNum ?>" loading="lazy">
                        <div class="strip-label">
                            <span>🎞️ Photo Strip</span>
                            <span class="dl-hint">Tap untuk download</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($roundNum < $totalRounds): ?>
                <hr class="divider">
            <?php endif; ?>
        <?php endforeach; ?>

    <?php endif; ?>

    <div class="footer">
        &copy; 2026 Ramadan Kareem Photo Booth
    </div>

    <!-- Preview Modal -->
    <div class="modal-overlay" id="preview-modal">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <div class="modal-img-container">
            <img id="modal-img" src="" alt="Preview">
        </div>
        <a id="modal-download" class="modal-download" href="" download>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download
        </a>
    </div>

    <script>
        const modal = document.getElementById('preview-modal');
        const modalImg = document.getElementById('modal-img');
        const modalDownload = document.getElementById('modal-download');

        function openModal(src, name) {
            modalImg.src = src;
            modalDownload.href = src;
            modalDownload.download = name + '.png';
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    </script>
</body>
</html>

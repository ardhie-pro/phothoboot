<?php
/**
 * Auto Compressor Web & CLI Tool for Server
 * Run via CLI: php auto_compress.php
 * Or open in browser: http://your-server-ip/phothoboot/auto_compress.php
 */

require_once __DIR__ . '/compress_helper.php';

$isCli = (php_sapi_name() === 'cli');

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Server Bulk Image Optimizer</title>';
    echo '<style>body{font-family:sans-serif;background:#0F172A;color:#F8FAFC;padding:30px;line-height:1.6;} pre{background:#1E293B;padding:20px;border-radius:12px;border:1px solid #334155;color:#38BDF8;overflow:auto;} .success{color:#4ADE80;font-weight:bold;} a{color:#FBBF24;text-decoration:none;font-weight:bold;}</style>';
    echo '</head><body>';
    echo '<h2>⚡ Photo Booth - Server Image Auto-Compressor</h2>';
    echo '<p>Sedang memproses seluruh gambar template dan branding di server...</p>';
    echo '<pre>';
}

$dirs = [
    __DIR__ . '/uploads/templates',
    __DIR__ . '/uploads/branding',
    __DIR__ . '/gambar'
];

$totalOriginalSize = 0;
$totalNewSize = 0;
$count = 0;
$optimizedCount = 0;

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        $path = $file->getPathname();
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) continue;

        $orig = filesize($path);
        $totalOriginalSize += $orig;
        
        $res = compressImageFile($path, 2480, 88, 6);
        $new = filesize($path);
        $totalNewSize += $new;
        $count++;

        $savedPct = $orig > 0 ? round((($orig - $new) / $orig) * 100, 1) : 0;
        if ($savedPct > 0) $optimizedCount++;

        $rel = substr($path, strlen(__DIR__) + 1);
        echo str_pad($rel, 45) . " | " . round($orig/1024) . " KB -> " . round($new/1024) . " KB (-{$savedPct}%)" . PHP_EOL;
    }
}

echo PHP_EOL . "==========================================" . PHP_EOL;
echo "Status: Selesai!" . PHP_EOL;
echo "Total file diperiksa: $count file" . PHP_EOL;
echo "Total file dioptimalkan: $optimizedCount file" . PHP_EOL;
echo "Ukuran Sebelum: " . round($totalOriginalSize / (1024*1024), 2) . " MB" . PHP_EOL;
echo "Ukuran Sesudah: " . round($totalNewSize / (1024*1024), 2) . " MB" . PHP_EOL;
echo "Total Penghematan: " . round(($totalOriginalSize - $totalNewSize) / (1024*1024), 2) . " MB" . PHP_EOL;
echo "==========================================" . PHP_EOL;

if (!$isCli) {
    echo '</pre>';
    echo '<p class="success">✅ Seluruh template dan aset di server telah berhasil dikompresi tanpa merusak kualitas!</p>';
    echo '<p><a href="admin.php">⬅️ Kembali ke Admin Dashboard</a> | <a href="index.html">📸 Ke Photo Booth</a></p>';
    echo '</body></html>';
}

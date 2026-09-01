<?php
// Endpoint: download_guide.php?type=blueprint or ?type=transparent or ?type=blueprint_6photo or ?type=transparent_6photo or ?layout=6
$type = $_GET['type'] ?? 'blueprint';
$layout = $_GET['layout'] ?? '';

$is6Photo = ($layout === '6' || strpos($type, '6photo') !== false);
$isTransparent = (strpos($type, 'transparent') !== false);

$width = 1748;
$height = 2480;

$image = imagecreatetruecolor($width, $height);
imagealphablending($image, false);
imagesavealpha($image, true);

if ($is6Photo) {
    // 6-Photo Grid Layout (2 Kolom x 3 Baris)
    $paddingX = 94;
    $gapX = 40;
    $gapY = 45;
    $photoWidth = 760;
    $photoHeight = 372;
    $totalGridH = (3 * $photoHeight) + (2 * $gapY);
    $topY = (int)(($height - $totalGridH) / 2); // 637 px

    $slots = [
        ['label' => 'FOTO #1 (KIRI ATAS)', 'x' => $paddingX, 'y' => $topY, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO #2 (KANAN ATAS)', 'x' => $paddingX + $photoWidth + $gapX, 'y' => $topY, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO #3 (KIRI TENGAH)', 'x' => $paddingX, 'y' => $topY + $photoHeight + $gapY, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO #4 (KANAN TENGAH)', 'x' => $paddingX + $photoWidth + $gapX, 'y' => $topY + $photoHeight + $gapY, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO #5 (KIRI BAWAH)', 'x' => $paddingX, 'y' => $topY + ($photoHeight + $gapY) * 2, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO #6 (KANAN BAWAH)', 'x' => $paddingX + $photoWidth + $gapX, 'y' => $topY + ($photoHeight + $gapY) * 2, 'w' => $photoWidth, 'h' => $photoHeight]
    ];

    if ($isTransparent) {
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        
        $frameBg = imagecolorallocatealpha($image, 255, 253, 245, 20);
        imagefilledrectangle($image, 0, 0, $width, $height, $frameBg);
        
        $cutout = imagecolorallocatealpha($image, 0, 0, 0, 127);
        $borderColor = imagecolorallocatealpha($image, 212, 175, 55, 0); // Gold
        
        foreach ($slots as $slot) {
            imagefilledrectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $cutout);
            imagesetthickness($image, 6);
            imagerectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $borderColor);
        }

        $filename = "Template_A5_Transparan_6Foto_1748x2480px.png";
    } else {
        // Blueprint 6 Photos
        $bg = imagecolorallocate($image, 15, 23, 42); // Dark slate
        imagefill($image, 0, 0, $bg);

        $gridColor = imagecolorallocate($image, 30, 41, 59);
        for ($x = 0; $x < $width; $x += 100) {
            imageline($image, $x, 0, $x, $height, $gridColor);
        }
        for ($y = 0; $y < $height; $y += 100) {
            imageline($image, 0, $y, $width, $y, $gridColor);
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $gold = imagecolorallocate($image, 255, 196, 37);
        $cyan = imagecolorallocate($image, 56, 189, 248);
        $slate = imagecolorallocate($image, 148, 163, 184);
        $slotBg = imagecolorallocate($image, 30, 41, 59);

        // Header text
        imagestring($image, 5, $paddingX, 80, "PANDUAN UKURAN DESAIN TEMPLATE A5 (6 FOTO GRID 2x3 - 300 DPI)", $gold);
        imagestring($image, 4, $paddingX, 120, "Ukuran Canvas: 1748 x 2480 px (148 x 210 mm) | Rasio Tiap Foto: 920x450 (2:1)", $white);
        imagestring($image, 3, $paddingX, 155, "Area Header Atas: Tinggi ~600 px (Bisa untuk Judul Event / Logo Perusahaan)", $slate);

        foreach ($slots as $idx => $slot) {
            imagefilledrectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $slotBg);
            imagesetthickness($image, 4);
            imagerectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $cyan);

            $num = $idx + 1;
            imagestring($image, 5, $slot['x'] + 20, $slot['y'] + 20, "{$slot['label']}", $gold);
            imagestring($image, 4, $slot['x'] + 20, $slot['y'] + 60, "Ukuran: {$slot['w']} x {$slot['h']} px", $white);
            imagestring($image, 3, $slot['x'] + 20, $slot['y'] + 95, "X: {$slot['x']} px, Y: {$slot['y']} px", $slate);
            imagestring($image, 3, $slot['x'] + 20, $slot['y'] + 130, "* Foto photobooth masuk ke kotak ini", $cyan);
        }

        // Footer info
        $footerY = $height - 180;
        imagestring($image, 5, $paddingX, $footerY, "AREA FOOTER BAWAH (Tinggi ~600 px)", $gold);
        imagestring($image, 4, $paddingX, $footerY + 35, "Gunakan area bawah untuk Logo Sponsor, Tanggal, Sosmed, atau QR Code.", $white);
        imagestring($image, 3, $paddingX, $footerY + 70, "Tips: Desain frame disimpan sebagai PNG transparan / outer frame.", $slate);

        $filename = "Panduan_Desain_Template_A5_6Foto_1748x2480px.png";
    }
} else {
    // 3-Photo Strip Layout
    $paddingX = 104;
    $photoWidth = 1540;
    $photoHeight = 650;
    $headerHeight = 180;
    $gap = 60;

    $slots = [
        ['label' => 'FOTO 1 (ATAS)', 'x' => $paddingX, 'y' => $headerHeight, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO 2 (TENGAH)', 'x' => $paddingX, 'y' => $headerHeight + $photoHeight + $gap, 'w' => $photoWidth, 'h' => $photoHeight],
        ['label' => 'FOTO 3 (BAWAH)', 'x' => $paddingX, 'y' => $headerHeight + ($photoHeight + $gap) * 2, 'w' => $photoWidth, 'h' => $photoHeight]
    ];

    if ($isTransparent) {
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        
        $frameBg = imagecolorallocatealpha($image, 255, 253, 245, 20);
        imagefilledrectangle($image, 0, 0, $width, $height, $frameBg);
        
        $cutout = imagecolorallocatealpha($image, 0, 0, 0, 127);
        $borderColor = imagecolorallocatealpha($image, 212, 140, 18, 0);
        
        foreach ($slots as $idx => $slot) {
            imagefilledrectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $cutout);
            imagesetthickness($image, 6);
            imagerectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $borderColor);
        }

        $filename = "Template_A5_Transparan_3Foto_1748x2480px.png";
    } else {
        // Blueprint 3 Photos
        $bg = imagecolorallocate($image, 24, 32, 47);
        imagefill($image, 0, 0, $bg);

        $gridColor = imagecolorallocate($image, 38, 50, 72);
        for ($x = 0; $x < $width; $x += 100) {
            imageline($image, $x, 0, $x, $height, $gridColor);
        }
        for ($y = 0; $y < $height; $y += 100) {
            imageline($image, 0, $y, $width, $y, $gridColor);
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $gold = imagecolorallocate($image, 255, 196, 37);
        $cyan = imagecolorallocate($image, 56, 189, 248);
        $slate = imagecolorallocate($image, 148, 163, 184);
        $slotBg = imagecolorallocate($image, 15, 23, 42);

        // Title / Header banner
        imagestring($image, 5, $paddingX, 50, "PANDUAN UKURAN DESAIN TEMPLATE A5 (300 DPI - 3 FOTO)", $gold);
        imagestring($image, 4, $paddingX, 85, "Ukuran Canvas: 1748 x 2480 px (148 x 210 mm) | Format: PNG 24-bit", $white);
        imagestring($image, 3, $paddingX, 115, "Area Header Atas: Tinggi 180 px (Bisa untuk Judul / Ornamen)", $slate);

        // Draw Slots
        foreach ($slots as $idx => $slot) {
            imagefilledrectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $slotBg);
            
            imagesetthickness($image, 4);
            imagerectangle($image, $slot['x'], $slot['y'], $slot['x'] + $slot['w'], $slot['y'] + $slot['h'], $cyan);

            $num = $idx + 1;
            imagestring($image, 5, $slot['x'] + 30, $slot['y'] + 30, "[ SLOT FOTO $num ]", $gold);
            imagestring($image, 5, $slot['x'] + 30, $slot['y'] + 70, "Ukuran Foto: {$slot['w']} x {$slot['h']} px", $white);
            imagestring($image, 4, $slot['x'] + 30, $slot['y'] + 110, "Posisi: X = {$slot['x']} px, Y = {$slot['y']} px", $slate);
            imagestring($image, 3, $slot['x'] + 30, $slot['y'] + 150, "* Foto kamera photobooth akan otomatis masuk ke dalam kotak ini", $cyan);
        }

        // Footer info
        $footerY = $height - 180;
        imagestring($image, 5, $paddingX, $footerY, "AREA FOOTER BAWAH (Tinggi 230 px)", $gold);
        imagestring($image, 4, $paddingX, $footerY + 35, "Gunakan area ini untuk Logo Perusahaan, Tanggal Acara, Sosmed, dsb.", $white);
        imagestring($image, 3, $paddingX, $footerY + 70, "Tips: Simpan hasil desain bingkai sebagai PNG Transparan agar foto di belakangnya terlihat.", $slate);

        $filename = "Panduan_Desain_Template_A5_3Foto_1748x2480px.png";
    }
}

header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="' . $filename . '"');
imagepng($image);
imagedestroy($image);


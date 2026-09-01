<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Access-Control-Allow-Origin: *');

$settingsFile = __DIR__ . '/uploads/booth_settings.json';
$uploadDir = __DIR__ . '/uploads/branding/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$defaultSettings = [
    'title' => 'Berbuka Bersama',
    'subtitle' => 'Mahaghora Group',
    'titleColor' => '#D48C12',
    'subtitleColor' => '#D48C12',
    'bgColor' => '#2D5A27',
    'bgImage' => '',
    'primaryColor' => '#D48C12',
    'secondaryColor' => '#63392E',
    'goldColor' => '#D4AF37',
    'showDeco' => true,
    'decoTopLeft' => './gambar/lampu.webp',
    'decoTopRight' => './gambar/lampu.webp',
    'decoBottomLeft' => './gambar/ketupat.webp',
    'decoBottomRight' => './gambar/ketupat.webp'
];

$action = $_GET['action'] ?? 'get';

if ($action === 'get') {
    if (file_exists($settingsFile)) {
        $saved = json_decode(file_get_contents($settingsFile), true);
        if (is_array($saved)) {
            $merged = array_merge($defaultSettings, $saved);
            echo json_encode(['success' => true, 'settings' => $merged]);
            exit();
        }
    }
    echo json_encode(['success' => true, 'settings' => $defaultSettings]);
    exit();
}

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $defaultSettings;
    if (file_exists($settingsFile)) {
        $saved = json_decode(file_get_contents($settingsFile), true);
        if (is_array($saved)) {
            $current = array_merge($current, $saved);
        }
    }

    $current['title'] = $_POST['title'] ?? $current['title'];
    $current['subtitle'] = $_POST['subtitle'] ?? $current['subtitle'];
    $current['titleColor'] = $_POST['titleColor'] ?? $current['titleColor'];
    $current['subtitleColor'] = $_POST['subtitleColor'] ?? $current['subtitleColor'];
    $current['bgColor'] = $_POST['bgColor'] ?? $current['bgColor'];
    $current['primaryColor'] = $_POST['primaryColor'] ?? $current['primaryColor'];
    $current['secondaryColor'] = $_POST['secondaryColor'] ?? $current['secondaryColor'];
    $current['goldColor'] = $_POST['goldColor'] ?? $current['goldColor'];
    $current['showDeco'] = isset($_POST['showDeco']) ? ($_POST['showDeco'] === 'true' || $_POST['showDeco'] === '1') : $current['showDeco'];

    // Handle remove bg image flag
    if (isset($_POST['removeBgImage']) && $_POST['removeBgImage'] === 'true') {
        if (!empty($current['bgImage']) && file_exists(__DIR__ . '/' . $current['bgImage'])) {
            @unlink(__DIR__ . '/' . $current['bgImage']);
        }
        $current['bgImage'] = '';
    }

    // Handle Background Image Upload
    if (isset($_FILES['bgImage']) && $_FILES['bgImage']['error'] === 0) {
        $ext = pathinfo($_FILES['bgImage']['name'], PATHINFO_EXTENSION);
        $filename = 'bg_' . time() . '.' . $ext;
        $dest = 'uploads/branding/' . $filename;
        if (move_uploaded_file($_FILES['bgImage']['tmp_name'], __DIR__ . '/' . $dest)) {
            if (!empty($current['bgImage']) && file_exists(__DIR__ . '/' . $current['bgImage'])) {
                @unlink(__DIR__ . '/' . $current['bgImage']);
            }
            $current['bgImage'] = $dest;
        }
    }

    // Handle Decorative Images Uploads
    $decoFields = [
        'decoTopLeft' => 'deco_tl',
        'decoTopRight' => 'deco_tr',
        'decoBottomLeft' => 'deco_bl',
        'decoBottomRight' => 'deco_br'
    ];

    foreach ($decoFields as $key => $fileKey) {
        if (isset($_POST['reset_' . $key]) && $_POST['reset_' . $key] === 'true') {
            if (!empty($current[$key]) && strpos($current[$key], 'uploads/branding/') !== false && file_exists(__DIR__ . '/' . $current[$key])) {
                @unlink(__DIR__ . '/' . $current[$key]);
            }
            $current[$key] = $defaultSettings[$key];
        } elseif (isset($_FILES[$key]) && $_FILES[$key]['error'] === 0) {
            $ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
            $filename = $fileKey . '_' . time() . '.' . $ext;
            $dest = 'uploads/branding/' . $filename;
            if (move_uploaded_file($_FILES[$key]['tmp_name'], __DIR__ . '/' . $dest)) {
                if (!empty($current[$key]) && strpos($current[$key], 'uploads/branding/') !== false && file_exists(__DIR__ . '/' . $current[$key])) {
                    @unlink(__DIR__ . '/' . $current[$key]);
                }
                $current[$key] = $dest;
            }
        }
    }

    file_put_contents($settingsFile, json_encode($current, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'settings' => $current]);
    exit();
}

if ($action === 'reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($settingsFile)) {
        @unlink($settingsFile);
    }
    // Delete files in uploads/branding/
    $files = glob($uploadDir . '*');
    foreach ($files as $file) {
        if (is_file($file)) @unlink($file);
    }
    echo json_encode(['success' => true, 'settings' => $defaultSettings]);
    exit();
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
?>

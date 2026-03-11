<?php
// Upload images to a session folder (create new or append to existing)
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['images']) || !is_array($data['images']) || count($data['images']) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No images provided']);
    exit();
}

// Use existing session or create new one
$sessionId = isset($data['session_id']) && !empty($data['session_id'])
    ? preg_replace('/[^a-zA-Z0-9_]/', '', $data['session_id'])
    : date('Ymd_His') . '_' . bin2hex(random_bytes(4));

// Create/ensure session directory exists
$uploadDir = __DIR__ . '/uploads/' . $sessionId . '/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create session directory']);
        exit();
    }
}

// Count existing rounds in this session to determine numbering
$existingFiles = glob($uploadDir . 'round_*');
$roundNumbers = [];
foreach ($existingFiles as $f) {
    if (preg_match('/round_(\d+)/', basename($f), $m)) {
        $roundNumbers[] = (int)$m[1];
    }
}
$nextRound = empty($roundNumbers) ? 1 : max($roundNumbers) + 1;

$savedFiles = [];

foreach ($data['images'] as $index => $imageData) {
    if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
        continue;
    }

    $base64_string = substr($imageData, strpos($imageData, ',') + 1);
    $ext = strtolower($type[1]);

    if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {
        continue;
    }

    $decoded = base64_decode($base64_string);
    if ($decoded === false) {
        continue;
    }

    // Last image is the strip, others are individual photos
    $totalImages = count($data['images']);
    if ($index === $totalImages - 1) {
        $filename = 'round_' . $nextRound . '_strip.' . $ext;
    } else {
        $filename = 'round_' . $nextRound . '_photo_' . ($index + 1) . '.' . $ext;
    }

    $filepath = $uploadDir . $filename;

    if (file_put_contents($filepath, $decoded)) {
        $savedFiles[] = $filename;
    }
}

if (count($savedFiles) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No valid images were saved']);
    exit();
}

// Build the view URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['PHP_SELF']), '/');

$viewUrl = $protocol . "://" . $host . $basePath . '/view.php?s=' . $sessionId;

echo json_encode([
    'success' => true,
    'session_id' => $sessionId,
    'round' => $nextRound,
    'view_url' => $viewUrl,
    'files' => $savedFiles
]);
?>

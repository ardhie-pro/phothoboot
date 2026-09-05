<?php
// Upload images to a session folder (create new or append to existing)
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

@ini_set('upload_max_filesize', '100M');
@ini_set('post_max_size', '120M');
@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', '600');
@ini_set('max_input_time', '600');

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

// Helper to detect primary LAN IP of the laptop (Wi-Fi or active Ethernet)
function getLaptopLanIp() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        @exec('ipconfig', $lines);
        if (is_array($lines)) {
            $adapters = [];
            $currentAdapter = 'unknown';
            foreach ($lines as $line) {
                if (preg_match('/^([^\s].*):$/', $line, $match)) {
                    $currentAdapter = trim($match[1]);
                    $adapters[$currentAdapter] = ['name' => $currentAdapter, 'ip' => '', 'gateway' => ''];
                    continue;
                }
                if (preg_match('/(?:IPv4 Address|Alamat IPv4)[^:]*:\s*([0-9\.]+)/i', $line, $m)) {
                    $adapters[$currentAdapter]['ip'] = trim($m[1]);
                }
                if (preg_match('/(?:Default Gateway|Gateway Default)[^:]*:\s*([0-9\.]+)/i', $line, $m)) {
                    $adapters[$currentAdapter]['gateway'] = trim($m[1]);
                }
            }

            // 1. Adapter with both IPv4 and active Default Gateway (e.g. connected Wi-Fi / Router)
            foreach ($adapters as $name => $data) {
                if (!empty($data['ip']) && !empty($data['gateway']) && $data['ip'] !== '127.0.0.1' && !str_starts_with($data['ip'], '169.254.')) {
                    return $data['ip'];
                }
            }

            // 2. Wi-Fi / Wireless adapter with valid IPv4
            foreach ($adapters as $name => $data) {
                if (stripos($name, 'Wi-Fi') !== false || stripos($name, 'Wireless') !== false) {
                    if (!empty($data['ip']) && $data['ip'] !== '127.0.0.1' && !str_starts_with($data['ip'], '169.254.')) {
                        return $data['ip'];
                    }
                }
            }

            // 3. Physical adapter that is NOT VirtualBox / VMware / vEthernet
            foreach ($adapters as $name => $data) {
                if (stripos($name, 'Virtual') === false && stripos($name, 'VMware') === false && stripos($name, 'vEthernet') === false) {
                    if (!empty($data['ip']) && $data['ip'] !== '127.0.0.1' && !str_starts_with($data['ip'], '169.254.') && !str_starts_with($data['ip'], '192.168.56.')) {
                        return $data['ip'];
                    }
                }
            }

            // 4. Any valid non-loopback IP
            foreach ($adapters as $name => $data) {
                if (!empty($data['ip']) && $data['ip'] !== '127.0.0.1' && !str_starts_with($data['ip'], '169.254.')) {
                    return $data['ip'];
                }
            }
        }
    }

    $hostIp = @gethostbyname(gethostname());
    if ($hostIp && $hostIp !== '127.0.0.1' && strpos($hostIp, '127.') !== 0) {
        return $hostIp;
    }
    return '127.0.0.1';
}

// Build the view URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$hostParts = explode(':', $host);
$hostName = $hostParts[0];
$hostPort = isset($hostParts[1]) ? ':' . $hostParts[1] : '';

// If accessed via localhost or 127.0.0.1, convert host to the laptop's LAN IP so phones scanning the QR can open it
if ($hostName === 'localhost' || $hostName === '127.0.0.1' || $hostName === '::1') {
    $lanIp = getLaptopLanIp();
    if ($lanIp && $lanIp !== '127.0.0.1') {
        $host = $lanIp . $hostPort;
    }
}

$basePath = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['PHP_SELF']), '/');

$stripFilename = '';
foreach ($savedFiles as $sf) {
    if (stripos($sf, 'strip') !== false) {
        $stripFilename = $sf;
        break;
    }
}
if (empty($stripFilename) && !empty($savedFiles)) {
    $stripFilename = end($savedFiles);
}
$stripUrl = 'uploads/' . $sessionId . '/' . $stripFilename;

echo json_encode([
    'success' => true,
    'session_id' => $sessionId,
    'round' => $nextRound,
    'view_url' => $viewUrl,
    'files' => $savedFiles,
    'saved_files' => $savedFiles,
    'strip_filename' => $stripFilename,
    'strip_url' => $stripUrl
]);
?>

<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$queueFile = __DIR__ . '/uploads/print_queue.json';

function getQueue($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function saveQueue($file, $data) {
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

$action = $_GET['action'] ?? 'get_queue';

// 1. Request Print (called by user in view.php)
if ($action === 'request_print' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $sessionId = isset($input['session_id']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $input['session_id']) : '';
    $photoUrl = $input['photo_url'] ?? '';
    $label = $input['label'] ?? 'Photo Strip';
    $copies = max(1, min(5, (int)($input['copies'] ?? 1)));
    
    if (empty($photoUrl) || empty($sessionId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Data tidak lengkap']);
        exit();
    }
    
    $queue = getQueue($queueFile);
    
    // Check if already in queue and pending
    foreach ($queue as $item) {
        if ($item['session_id'] === $sessionId && $item['photo_url'] === $photoUrl && in_array($item['status'], ['pending', 'printing'])) {
            echo json_encode([
                'success' => true,
                'already_queued' => true,
                'item' => $item,
                'message' => 'Foto ini sudah ada dalam antrean cetak!'
            ]);
            exit();
        }
    }
    
    $newItem = [
        'id' => 'pr_' . uniqid(),
        'session_id' => $sessionId,
        'photo_url' => $photoUrl,
        'label' => $label,
        'copies' => $copies,
        'status' => 'pending', // pending | printing | completed | cancelled
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    array_unshift($queue, $newItem);
    saveQueue($queueFile, $queue);
    
    echo json_encode([
        'success' => true,
        'item' => $newItem,
        'message' => 'Permintaan cetak berhasil dikirim ke Admin!'
    ]);
    exit();
}

// 2. Get Queue for Admin
if ($action === 'get_queue') {
    $queue = getQueue($queueFile);
    $statusFilter = $_GET['status'] ?? 'all';
    
    if ($statusFilter !== 'all') {
        $queue = array_values(array_filter($queue, function($item) use ($statusFilter) {
            return ($item['status'] ?? '') === $statusFilter;
        }));
    }
    
    // Calculate pending count
    $allQueue = getQueue($queueFile);
    $pendingCount = 0;
    foreach ($allQueue as $item) {
        if (($item['status'] ?? '') === 'pending') {
            $pendingCount++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'queue' => $queue,
        'pending_count' => $pendingCount
    ]);
    exit();
}

// 3. Get Queue Status for specific session (for view.php user)
if ($action === 'get_session_queue') {
    $sessionId = isset($_GET['session_id']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['session_id']) : '';
    $queue = getQueue($queueFile);
    
    $sessionItems = [];
    foreach ($queue as $item) {
        if (($item['session_id'] ?? '') === $sessionId) {
            $sessionItems[] = $item;
        }
    }
    
    echo json_encode([
        'success' => true,
        'items' => $sessionItems
    ]);
    exit();
}

// 4. Update status (called by admin.php)
if ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    $newStatus = $input['status'] ?? '';
    
    if (empty($id) || !in_array($newStatus, ['pending', 'printing', 'completed', 'cancelled'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Parameter tidak valid']);
        exit();
    }
    
    $queue = getQueue($queueFile);
    $found = false;
    
    foreach ($queue as &$item) {
        if ($item['id'] === $id) {
            $item['status'] = $newStatus;
            $item['updated_at'] = date('Y-m-d H:i:s');
            $found = true;
            break;
        }
    }
    unset($item);
    
    if ($found) {
        saveQueue($queueFile, $queue);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Item tidak ditemukan']);
    }
    exit();
}

// 5. Delete item from queue (called by admin.php)
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    
    $queue = getQueue($queueFile);
    $newQueue = [];
    foreach ($queue as $item) {
        if ($item['id'] !== $id) {
            $newQueue[] = $item;
        }
    }
    
    saveQueue($queueFile, $newQueue);
    echo json_encode(['success' => true]);
    exit();
}

// 6. Clear completed items
if ($action === 'clear_completed' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $queue = getQueue($queueFile);
    $newQueue = [];
    foreach ($queue as $item) {
        if (($item['status'] ?? '') !== 'completed' && ($item['status'] ?? '') !== 'cancelled') {
            $newQueue[] = $item;
        }
    }
    
    saveQueue($queueFile, $newQueue);
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(400);
echo json_encode(['error' => 'Action tidak dikenali']);
?>

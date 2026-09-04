<?php
header('Content-Type: application/json');

@ini_set('upload_max_filesize', '100M');
@ini_set('post_max_size', '120M');
@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', '600');
@ini_set('max_input_time', '600');

$jsonFile = __DIR__ . '/uploads/templates.json';
$targetDir = __DIR__ . '/uploads/templates/';

if (!is_dir($targetDir)) {
    @mkdir($targetDir, 0777, true);
}

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    if (!file_exists($jsonFile)) {
        echo json_encode([]);
        exit();
    }
    $raw = file_get_contents($jsonFile);
    $data = json_decode($raw, true) ?: [];
    
    // If only_active is requested, filter
    if (isset($_GET['only_active']) && $_GET['only_active'] == '1') {
        $data = array_values(array_filter($data, function($t) {
            return !isset($t['active']) || $t['active'] === true || $t['active'] === 1 || $t['active'] === 'true';
        }));
    }
    
    echo json_encode($data);
    exit();
}

if ($action === 'toggle_active' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    $active = isset($input['active']) ? (bool)$input['active'] : true;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'Missing template ID']);
        exit();
    }
    
    $templates = [];
    if (file_exists($jsonFile)) {
        $templates = json_decode(file_get_contents($jsonFile), true) ?: [];
    }
    
    $found = false;
    foreach ($templates as &$t) {
        if ($t['id'] === $id) {
            $t['active'] = $active;
            $found = true;
            break;
        }
    }
    unset($t);
    
    if ($found) {
        file_put_contents($jsonFile, json_encode($templates, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'id' => $id, 'active' => $active]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Template not found']);
    }
    exit();
}

if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? 'Unnamed Template');
    $overlayMode = isset($_POST['overlayMode']) && ($_POST['overlayMode'] === 'true' || $_POST['overlayMode'] === '1' || $_POST['overlayMode'] === 'on');
    $active = !isset($_POST['active']) || $_POST['active'] === 'true' || $_POST['active'] === '1' || $_POST['active'] === 'on';
    
    $editId = trim($_POST['id'] ?? '');
    $isEdit = false;
    $existingIndex = -1;
    $existingTemplate = null;

    $templates = [];
    if (file_exists($jsonFile)) {
        $templates = json_decode(file_get_contents($jsonFile), true) ?: [];
    }

    if (!empty($editId)) {
        foreach ($templates as $idx => $t) {
            if ($t['id'] === $editId) {
                $isEdit = true;
                $existingIndex = $idx;
                $existingTemplate = $t;
                break;
            }
        }
    }

    $id = $isEdit ? $editId : uniqid();
    $templateDir = $targetDir . $id . '/';
    if (!is_dir($templateDir)) {
        @mkdir($templateDir, 0777, true);
    }
    
    $outerPath = $existingTemplate['outer'] ?? '';
    $ketupatPath = $existingTemplate['ketupat'] ?? '';
    $lampuPath = $existingTemplate['lampu'] ?? '';
    $ramaPath = $existingTemplate['rama'] ?? '';
    
    if (isset($_FILES['outerImage']) && $_FILES['outerImage']['name'] !== '') {
        if ($_FILES['outerImage']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['outerImage']['name'], PATHINFO_EXTENSION) ?: 'png';
            $outerPath = 'uploads/templates/' . $id . '/outer.' . $ext;
            if (!move_uploaded_file($_FILES['outerImage']['tmp_name'], __DIR__ . '/' . $outerPath)) {
                echo json_encode(['success' => false, 'error' => 'Gagal menyimpan file background luar/frame. Periksa izin tulis (chmod 777) folder uploads/templates.']);
                exit();
            }
        } else {
            $errCode = $_FILES['outerImage']['error'];
            $errMsg = 'Gagal upload background luar/frame (Error code ' . $errCode . '). ';
            if ($errCode === UPLOAD_ERR_INI_SIZE || $errCode === UPLOAD_ERR_FORM_SIZE) {
                $errMsg .= 'Ukuran file terlalu besar untuk server. Periksa upload_max_filesize di php.ini atau .htaccess.';
            }
            echo json_encode(['success' => false, 'error' => $errMsg]);
            exit();
        }
    }

    // Process Dynamic Items List
    $items = [];
    $rawItemsJson = $_POST['items_json'] ?? '';
    if (!empty($rawItemsJson)) {
        $parsedItems = json_decode($rawItemsJson, true);
        if (is_array($parsedItems)) {
            foreach ($parsedItems as $idx => $itemData) {
                $itemId = !empty($itemData['id']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $itemData['id']) : ('item_' . ($idx + 1) . '_' . uniqid());
                $itemName = trim($itemData['name'] ?? ('Item ' . ($idx + 1)));
                $itemSrc = $itemData['src'] ?? '';
                $itemSlot = (int)($itemData['slot'] ?? 0);
                $itemWidth = (int)($itemData['width'] ?? $itemData['size'] ?? 300);
                $itemHeight = (int)($itemData['height'] ?? $itemData['size'] ?? 300);
                $itemSize = (int)($itemData['size'] ?? max($itemWidth, $itemHeight));
                $itemX = (int)($itemData['x'] ?? 0);
                $itemY = (int)($itemData['y'] ?? 0);

                // Check if new file was uploaded for this item
                $fileKey = 'item_file_' . $idx;
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION) ?: 'png';
                    $newPath = 'uploads/templates/' . $id . '/' . $itemId . '.' . $ext;
                    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], __DIR__ . '/' . $newPath)) {
                        $itemSrc = $newPath;
                    }
                }

                if (!empty($itemSrc)) {
                    $items[] = [
                        'id' => $itemId,
                        'name' => $itemName,
                        'src' => $itemSrc,
                        'slot' => $itemSlot,
                        'width' => $itemWidth,
                        'height' => $itemHeight,
                        'size' => $itemSize,
                        'x' => $itemX,
                        'y' => $itemY
                    ];
                }
            }
        }
    }

    // Fallback if items array was not provided (legacy compatibility)
    if (empty($items)) {
        if (!empty($ketupatPath)) {
            $items[] = [
                'id' => 'ketupat',
                'name' => 'Item 1 / Ketupat',
                'src' => $ketupatPath,
                'slot' => 1,
                'size' => (int)($_POST['ketupat_size'] ?? 350),
                'x' => (int)($_POST['ketupat_x'] ?? 120),
                'y' => (int)($_POST['ketupat_y'] ?? 150)
            ];
        }
        if (!empty($lampuPath)) {
            $items[] = [
                'id' => 'lampu',
                'name' => 'Item 2 / Lampu',
                'src' => $lampuPath,
                'slot' => 2,
                'size' => (int)($_POST['lampu_size'] ?? 300),
                'x' => (int)($_POST['lampu_x'] ?? -100),
                'y' => (int)($_POST['lampu_y'] ?? 140)
            ];
        }
        if (!empty($ramaPath)) {
            $items[] = [
                'id' => 'rama',
                'name' => 'Item 3 / Stiker',
                'src' => $ramaPath,
                'slot' => 5,
                'size' => (int)($_POST['rama_size'] ?? 550),
                'x' => (int)($_POST['rama_x'] ?? 150),
                'y' => (int)($_POST['rama_y'] ?? 300)
            ];
        }
    }
    
    $newTemplate = [
        'id' => $id,
        'name' => $name,
        'sizeType' => $_POST['sizeType'] ?? 'a5_6grid',
        'outer' => $outerPath,
        'items' => $items,
        'ketupat' => $items[0]['src'] ?? $ketupatPath,
        'lampu' => $items[1]['src'] ?? $lampuPath,
        'rama' => $items[2]['src'] ?? $ramaPath,
        'overlayMode' => $overlayMode,
        'active' => $isEdit && isset($existingTemplate['active']) ? (bool)$existingTemplate['active'] : $active,
        'layout' => [
            'ketupat' => [
                'x' => (int)($items[0]['x'] ?? $_POST['ketupat_x'] ?? 120),
                'y' => (int)($items[0]['y'] ?? $_POST['ketupat_y'] ?? 150),
                'size' => (int)($items[0]['size'] ?? $_POST['ketupat_size'] ?? 350)
            ],
            'lampu' => [
                'x' => (int)($items[1]['x'] ?? $_POST['lampu_x'] ?? -100),
                'y' => (int)($items[1]['y'] ?? $_POST['lampu_y'] ?? 140),
                'size' => (int)($items[1]['size'] ?? $_POST['lampu_size'] ?? 300)
            ],
            'rama' => [
                'x' => (int)($items[2]['x'] ?? $_POST['rama_x'] ?? 150),
                'y' => (int)($items[2]['y'] ?? $_POST['rama_y'] ?? 300),
                'size' => (int)($items[2]['size'] ?? $_POST['rama_size'] ?? 550)
            ]
        ]
    ];
    
    if ($isEdit && $existingIndex >= 0) {
        $templates[$existingIndex] = $newTemplate;
    } else {
        $templates[] = $newTemplate;
    }
    
    file_put_contents($jsonFile, json_encode($templates, JSON_PRETTY_PRINT));
    
    echo json_encode(['success' => true, 'is_edit' => $isEdit, 'template' => $newTemplate]);
    exit();
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'Missing ID']);
        exit();
    }
    
    $templates = [];
    if (file_exists($jsonFile)) {
        $templates = json_decode(file_get_contents($jsonFile), true) ?: [];
    }
    
    $newTemplates = [];
    foreach ($templates as $t) {
        if ($t['id'] === $id) {
            // Delete files and folder
            if (!empty($t['outer']) && file_exists(__DIR__ . '/' . $t['outer'])) @unlink(__DIR__ . '/' . $t['outer']);
            if (!empty($t['ketupat']) && file_exists(__DIR__ . '/' . $t['ketupat'])) @unlink(__DIR__ . '/' . $t['ketupat']);
            if (!empty($t['lampu']) && file_exists(__DIR__ . '/' . $t['lampu'])) @unlink(__DIR__ . '/' . $t['lampu']);
            if (!empty($t['rama']) && file_exists(__DIR__ . '/' . $t['rama'])) @unlink(__DIR__ . '/' . $t['rama']);
            $dir = $targetDir . $id;
            if (is_dir($dir)) {
                array_map('unlink', glob("$dir/*.*"));
                @rmdir($dir);
            }
        } else {
            $newTemplates[] = $t;
        }
    }
    
    file_put_contents($jsonFile, json_encode($newTemplates, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
?>

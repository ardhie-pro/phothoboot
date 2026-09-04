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

    if (isset($_FILES['ketupat']) && $_FILES['ketupat']['name'] !== '') {
        if ($_FILES['ketupat']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['ketupat']['name'], PATHINFO_EXTENSION) ?: 'png';
            $ketupatPath = 'uploads/templates/' . $id . '/ketupat.' . $ext;
            move_uploaded_file($_FILES['ketupat']['tmp_name'], __DIR__ . '/' . $ketupatPath);
        }
    }

    if (isset($_FILES['lampu']) && $_FILES['lampu']['name'] !== '') {
        if ($_FILES['lampu']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['lampu']['name'], PATHINFO_EXTENSION) ?: 'png';
            $lampuPath = 'uploads/templates/' . $id . '/lampu.' . $ext;
            move_uploaded_file($_FILES['lampu']['tmp_name'], __DIR__ . '/' . $lampuPath);
        }
    }

    if (isset($_FILES['rama']) && $_FILES['rama']['name'] !== '') {
        if ($_FILES['rama']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['rama']['name'], PATHINFO_EXTENSION) ?: 'png';
            $ramaPath = 'uploads/templates/' . $id . '/rama.' . $ext;
            move_uploaded_file($_FILES['rama']['tmp_name'], __DIR__ . '/' . $ramaPath);
        }
    }
    
    $newTemplate = [
        'id' => $id,
        'name' => $name,
        'sizeType' => $_POST['sizeType'] ?? 'a5_6grid',
        'outer' => $outerPath,
        'ketupat' => $ketupatPath,
        'lampu' => $lampuPath,
        'rama' => $ramaPath,
        'overlayMode' => $overlayMode,
        'active' => $isEdit && isset($existingTemplate['active']) ? (bool)$existingTemplate['active'] : $active,
        'layout' => [
            'ketupat' => [
                'x' => (int)($_POST['ketupat_x'] ?? 120),
                'y' => (int)($_POST['ketupat_y'] ?? 150),
                'size' => (int)($_POST['ketupat_size'] ?? 350)
            ],
            'lampu' => [
                'x' => (int)($_POST['lampu_x'] ?? -100),
                'y' => (int)($_POST['lampu_y'] ?? 140),
                'size' => (int)($_POST['lampu_size'] ?? 300)
            ],
            'rama' => [
                'x' => (int)($_POST['rama_x'] ?? 150),
                'y' => (int)($_POST['rama_y'] ?? 300),
                'size' => (int)($_POST['rama_size'] ?? 550)
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

<?php
header('Content-Type: application/json');

$jsonFile = __DIR__ . '/uploads/templates.json';
$targetDir = __DIR__ . '/uploads/templates/';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    if (!file_exists($jsonFile)) {
        echo json_encode([]);
        exit();
    }
    echo file_get_contents($jsonFile);
    exit();
}

if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'Unnamed Template';
    $overlayMode = isset($_POST['overlayMode']) && $_POST['overlayMode'] === 'true';
    
    $id = uniqid();
    $templateDir = $targetDir . $id . '/';
    mkdir($templateDir, 0755, true);
    
    $outerPath = '';
    $ketupatPath = '';
    $lampuPath = '';
    $ramaPath = '';
    
    if (isset($_FILES['outerImage']) && $_FILES['outerImage']['error'] === 0) {
        $ext = pathinfo($_FILES['outerImage']['name'], PATHINFO_EXTENSION);
        $outerPath = 'uploads/templates/' . $id . '/outer.' . $ext;
        move_uploaded_file($_FILES['outerImage']['tmp_name'], __DIR__ . '/' . $outerPath);
    }

    if (isset($_FILES['ketupat']) && $_FILES['ketupat']['error'] === 0) {
        $ext = pathinfo($_FILES['ketupat']['name'], PATHINFO_EXTENSION);
        $ketupatPath = 'uploads/templates/' . $id . '/ketupat.' . $ext;
        move_uploaded_file($_FILES['ketupat']['tmp_name'], __DIR__ . '/' . $ketupatPath);
    }

    if (isset($_FILES['lampu']) && $_FILES['lampu']['error'] === 0) {
        $ext = pathinfo($_FILES['lampu']['name'], PATHINFO_EXTENSION);
        $lampuPath = 'uploads/templates/' . $id . '/lampu.' . $ext;
        move_uploaded_file($_FILES['lampu']['tmp_name'], __DIR__ . '/' . $lampuPath);
    }

    if (isset($_FILES['rama']) && $_FILES['rama']['error'] === 0) {
        $ext = pathinfo($_FILES['rama']['name'], PATHINFO_EXTENSION);
        $ramaPath = 'uploads/templates/' . $id . '/rama.' . $ext;
        move_uploaded_file($_FILES['rama']['tmp_name'], __DIR__ . '/' . $ramaPath);
    }
    
    $templates = [];
    if (file_exists($jsonFile)) {
        $templates = json_decode(file_get_contents($jsonFile), true);
    }
    
    $newTemplate = [
        'id' => $id,
        'name' => $name,
        'outer' => $outerPath,
        'ketupat' => $ketupatPath,
        'lampu' => $lampuPath,
        'rama' => $ramaPath,
        'overlayMode' => $overlayMode,
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
    
    $templates[] = $newTemplate;
    file_put_contents($jsonFile, json_encode($templates, JSON_PRETTY_PRINT));
    
    echo json_encode(['success' => true, 'template' => $newTemplate]);
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
        $templates = json_decode(file_get_contents($jsonFile), true);
    }
    
    $newTemplates = [];
    foreach ($templates as $t) {
        if ($t['id'] === $id) {
            // Delete files and folder
            if (!empty($t['outer']) && file_exists(__DIR__ . '/' . $t['outer'])) unlink(__DIR__ . '/' . $t['outer']);
            if (!empty($t['ketupat']) && file_exists(__DIR__ . '/' . $t['ketupat'])) unlink(__DIR__ . '/' . $t['ketupat']);
            if (!empty($t['lampu']) && file_exists(__DIR__ . '/' . $t['lampu'])) unlink(__DIR__ . '/' . $t['lampu']);
            if (!empty($t['rama']) && file_exists(__DIR__ . '/' . $t['rama'])) unlink(__DIR__ . '/' . $t['rama']);
            $dir = $targetDir . $id;
            if (is_dir($dir)) {
                array_map('unlink', glob("$dir/*.*"));
                rmdir($dir);
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

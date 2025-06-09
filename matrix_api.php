<?php
// matrix_api.php
// AJAX endpoint for Pixel Art Library - GET pixel data publicly

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
if ($action === 'get') {
    require __DIR__ . '/includes/config.php';
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success'=>false,'msg'=>'Invalid ID']);
        exit;
    }
    $stmt = $pdo->prepare("SELECT pixel_data FROM pixel_art WHERE id = :id");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        http_response_code(404);
        echo json_encode(['success'=>false,'msg'=>'Not found']);
        exit;
    }
    $pixels = json_decode($row['pixel_data'], true);
    if (!is_array($pixels) || count($pixels) !== 256) {
        http_response_code(500);
        echo json_encode(['success'=>false,'msg'=>'Corrupt pixel data']);
        exit;
    }
    echo json_encode(['success'=>true,'pixels'=>$pixels]);
    exit;
}

// For other actions, require auth
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success'=>false,'msg'=>'Unauthorized']);
    exit;
}

// No other actions supported here
http_response_code(400);
echo json_encode(['success'=>false,'msg'=>'Invalid action']);
exit;
?>
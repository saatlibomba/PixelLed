<?php
// assign_device.php
session_start();
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';

header('Content-Type: application/json');
if (!is_logged_in() || !$_SESSION['is_admin']) {
  http_response_code(403);
  echo json_encode(['success'=>false,'msg'=>'Yetkisiz']);
  exit;
}

$did = intval($_POST['device_id'] ?? 0);
$uid = $_POST['user_id'] !== '' ? intval($_POST['user_id']) : null;

if ($did <= 0) {
  http_response_code(400);
  echo json_encode(['success'=>false,'msg'=>'Geçersiz cihaz']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    UPDATE devices
       SET user_id = :uid
     WHERE id = :did
  ");
  $stmt->execute(['uid'=>$uid, 'did'=>$did]);
  echo json_encode(['success'=>true]);
} catch (\PDOException $e) {
  http_response_code(500);
  echo json_encode(['success'=>false,'msg'=>$e->getMessage()]);
}

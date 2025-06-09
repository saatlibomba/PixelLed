<?php
// mirror_command_api.php
session_start();
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';

// Gelen ham JSON’i al
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['success'=>false, 'msg'=>'Geçersiz JSON']);
    exit;
}

// Cihazı sabit MAC’den buluyoruz
$mac = '08:A6:F7:10:9E:DC';
$stmt = $pdo->prepare("SELECT id FROM devices WHERE mac = :mac");
$stmt->execute(['mac' => $mac]);
$deviceId = (int)$stmt->fetchColumn();
if (!$deviceId) {
    http_response_code(404);
    echo json_encode(['success'=>false, 'msg'=>'Cihaz bulunamadı']);
    exit;
}

// Komutu ekle
$ins = $pdo->prepare("
  INSERT INTO mirror_commands (device_id, raw_json)
  VALUES (:did, :raw)
");
$ins->execute([
  'did' => $deviceId,
  'raw' => $raw
]);

echo json_encode(['success'=>true]);

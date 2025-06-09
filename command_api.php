<?php
session_start();
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';

// JSON yanıt vereceğiz
header('Content-Type: application/json');

// 1) Yetki kontrolü
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'msg' => 'Unauthorized']);
    exit;
}

// 2) Parametreleri al ve doğrula
$device_id = filter_input(INPUT_POST, 'device_id', FILTER_VALIDATE_INT);
$frame     = $_POST['frame'] ?? '';

if (!$device_id || $frame === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'msg' => 'Invalid parameters']);
    exit;
}

// 3) JSON yapısını kontrol et (isteğe bağlı ama tavsiye edilir)
try {
    // JSON_THROW_ON_ERROR, PHP7.3+ ile birlikte geliyor
    $payload = json_decode($frame, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'msg' => 'Invalid JSON payload']);
    exit;
}

// (İsteğe bağlı) İçerikte delay ve brightness var mı, tipleri uygun mu?
if (!isset($payload['frames']) || !is_array($payload['frames']) ||
    !isset($payload['delay'])  || !is_int($payload['delay'])  ||
    !isset($payload['brightness']) || !is_int($payload['brightness'])
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'msg' => 'Payload missing frames/delay/brightness']);
    exit;
}

// 4) Veritabanına kaydet
try {
    $stmt = $pdo->prepare("
        INSERT INTO commands (device_id, frame_json)
        VALUES (:did, :frame)
        ON DUPLICATE KEY UPDATE
          frame_json  = VALUES(frame_json),
          updated_at  = CURRENT_TIMESTAMP
    ");
    $stmt->bindValue(':did',   $device_id, PDO::PARAM_INT);
    $stmt->bindValue(':frame', $frame,     PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Hata günlüğüne yaz, kullanıcıya genel bir mesaj dön
    error_log('command_api.php DB error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Database error']);
}

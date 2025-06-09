<?php
/*
 * status_api.php
 * Receives device status and records or updates it.
 * Expects POST parameters:
 *   mac   : device MAC address
 *   color : current LED color (hex string)
 *   on    : current state (0 or 1)
 */
header('Content-Type: application/json');
require 'includes/config.php';

$mac   = isset($_POST['mac'])   ? substr($_POST['mac'],0,17) : '';
$color = isset($_POST['color']) ? preg_replace('/[^A-F0-9]/','', strtoupper($_POST['color'])) : '000000';
$is_on = isset($_POST['on'])    ? intval($_POST['on']) : 0;
if (!$mac) {
  http_response_code(400);
  echo json_encode(['success'=>false,'msg'=>'missing_mac']);
  exit;
}

// Find or insert device
$stmt = $pdo->prepare("SELECT id FROM devices WHERE mac = :mac");
$stmt->execute(['mac'=>$mac]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
  $device_id = $row['id'];
} else {
  $ins = $pdo->prepare("INSERT INTO devices(mac) VALUES(:mac)");
  $ins->execute(['mac'=>$mac]);
  $device_id = $pdo->lastInsertId();
}

// Upsert status
// Ensure 'status' table exists with:
// CREATE TABLE IF NOT EXISTS status (
//   device_id INT PRIMARY KEY,
//   color CHAR(6),
//   is_on TINYINT(1),
//   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//   FOREIGN KEY(device_id) REFERENCES devices(id)
// );
$sql = "INSERT INTO status (device_id, color, is_on) VALUES (:did, :col, :on)
        ON DUPLICATE KEY UPDATE color = :col, is_on = :on, updated_at = CURRENT_TIMESTAMP";
$up = $pdo->prepare($sql);
try {
  $up->execute(['did'=>$device_id,'col'=>$color,'on'=>$is_on]);
  echo json_encode(['success'=>true]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success'=>false,'msg'=>$e->getMessage()]);
}
?>
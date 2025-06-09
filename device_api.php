<?php
// device_api.php
header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0"?>';

// Eğer mac parametresi yoksa boş bırak
if (empty($_GET['mac'])) {
    echo '<commands/>';
    exit;
}

require __DIR__ . '/includes/config.php';

$mac = $_GET['mac'];
// Cihaz ID’sini al
$stmt = $pdo->prepare("SELECT id FROM devices WHERE mac = :mac");
$stmt->execute(['mac' => $mac]);
$deviceId = (int)$stmt->fetchColumn();
if (!$deviceId) {
    echo '<commands/>';
    exit;
}

// Bekleyen komutları çek
$cmds = $pdo->prepare("
  SELECT id, raw_json
  FROM mirror_commands
  WHERE device_id = :did
  ORDER BY id
");
$cmds->execute(['did' => $deviceId]);
$rows = $cmds->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo '<commands/>';
    exit;
}

// XML’i üret
echo '<commands>';
foreach ($rows as $r) {
    // raw_json sütunundaki JSON’u CDATA içine sar
    echo '<command><![CDATA['
       . $r['raw_json']
       . ']]></command>';
}
echo '</commands>';

// Gönderilenleri sil
$ids = array_column($rows, 'id');
$placeholders = implode(',', array_fill(0, count($ids), '?'));
// Gönderilenleri sil
/*
$del = $pdo->prepare("DELETE FROM mirror_commands WHERE id IN ($placeholders)");
$del->execute($ids);
*/

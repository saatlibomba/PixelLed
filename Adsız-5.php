<?php
require __DIR__ . '/includes/config.php';
header('Content-Type: application/xml; charset=UTF-8');
$mac = $_GET['mac'] ?? '';
if (!$mac) {
  echo '<?xml version="1.0"?><error>Missing mac</error>';
  exit;
}
$stmt = $pdo->prepare("SELECT id FROM devices WHERE mac = :mac");
$stmt->execute(['mac'=>$mac]);
if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo '<?xml version="1.0"?><error>Unknown device</error>';
  exit;
}
$did = $row['id'];
$stmt = $pdo->prepare("SELECT frame_json, UNIX_TIMESTAMP(updated_at) AS ts FROM commands WHERE device_id = :did");
$stmt->execute(['did'=>$did]);
$cmd = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<?xml version="1.0"?>';
echo '<commands>';
if ($cmd) {
  echo '<command><frame ts="' . $cmd['ts'] . '"><![CDATA[' . $cmd['frame_json'] . ']]></frame></command>';
}
echo '</commands>';

$stmt2 = $pdo->prepare("
  SELECT raw_json FROM mirror_commands
  WHERE device_id=:did AND created_at > :since
  ORDER BY created_at ASC
");
$stmt2->execute(['did'=>$did, 'since'=>$last_ts]);
while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
  echo "<command raw_json='".htmlspecialchars($row['raw_json'],ENT_QUOTES)."'/>";
}

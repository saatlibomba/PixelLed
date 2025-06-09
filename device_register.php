<?php
// device_register.php
header('Content-Type: application/json; charset=UTF-8');
require __DIR__ . '/includes/config.php';

$mac  = trim($_POST['mac']  ?? '');
$user = trim($_POST['user'] ?? '');
$pass = trim($_POST['pass'] ?? '');

if (!$mac || !$user || !$pass) {
    http_response_code(400);
    echo json_encode(['success'=>false, 'msg'=>'Eksik parametre']);
    exit;
}

try {
    // 1) Kullanıcı var mı? aktif mi?
    $stmt = $pdo->prepare("
        SELECT id, password, is_active
          FROM users 
         WHERE username = :username
         LIMIT 1
    ");
    $stmt->execute(['username'=>$user]);
    $usr = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usr) {
        http_response_code(401);
        echo json_encode(['success'=>false,'msg'=>'Geçersiz kullanıcı']);
        exit;
    }
    if (!$usr['is_active']) {
        http_response_code(403);
        echo json_encode(['success'=>false,'msg'=>'Kullanıcı pasif']);
        exit;
    }
    // 2) Parola doğrula
    if (!password_verify($pass, $usr['password'])) {
        http_response_code(401);
        echo json_encode(['success'=>false,'msg'=>'Geçersiz parola']);
        exit;
    }
    $user_id = $usr['id'];

    // 3) Cihaz kaydı (INSERT veya UPDATE)
    //   devices.mac UNIQUE KEY olduğu için önce SELECT yapıp ona göre hareket edelim:
    $stmt = $pdo->prepare("SELECT id FROM devices WHERE mac = :mac");
    $stmt->execute(['mac'=>$mac]);
    if ($dev = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // zaten varsa user_id güncelle
        $stmt = $pdo->prepare("
            UPDATE devices
               SET user_id = :uid
             WHERE mac = :mac
        ");
        $stmt->execute(['uid'=>$user_id,'mac'=>$mac]);
        $device_id = $dev['id'];
    } else {
        // yoksa yeni satır
        $stmt = $pdo->prepare("
            INSERT INTO devices (mac, user_id)
            VALUES (:mac, :uid)
        ");
        $stmt->execute(['mac'=>$mac,'uid'=>$user_id]);
        $device_id = $pdo->lastInsertId();
    }

    echo json_encode([
        'success'   => true,
        'device_id' => (int)$device_id
    ]);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success'=>false,
        'msg'=>'Veritabanı hatası: '.$e->getMessage()
    ]);
    exit;
}

<?php
// pixel_admin.php
session_start();
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$isAdmin  = $_SESSION['user']['is_admin'];
$userId   = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];

// 1) Cihazları çek
if ($isAdmin) {
    // Admin: tüm cihazlar + atanan kullanıcı
    $devices = $pdo->query("
        SELECT d.id,
               COALESCE(d.name, CONCAT('#',d.id)) AS name,
               d.user_id,
               u.username AS assigned_user
        FROM devices d
        LEFT JOIN users u ON d.user_id = u.id
        ORDER BY d.id
    ")->fetchAll(PDO::FETCH_ASSOC);
    // Kullanıcı listesi (cihaz atama için)
    $users = $pdo->query("SELECT id, username FROM users ORDER BY username")
                 ->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Normal kullanıcı: sadece kendi cihazları
    $stmt = $pdo->prepare("
        SELECT id, COALESCE(name, CONCAT('#',id)) AS name
        FROM devices
        WHERE user_id = :uid
        ORDER BY id
    ");
    $stmt->execute(['uid' => $userId]);
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Pixel/GIF kütüphanesi
$art = $pdo->query("
    SELECT id, name, type, frame_delay, pixel_data
    FROM pixel_art
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/header.php';
?>

<style>
/* Galeri grid */
#artGrid {
  display: grid;
  grid-template-columns: repeat(8,1fr);
  gap: 0;
  margin: 0;
  padding: 0;
  width: 100%;
  max-height: calc((100vw/8)*3);
  overflow-y: auto;
}
.art-item {
  position: relative;
  width: 100%;
  aspect-ratio: 1;
  cursor: pointer;
  border: 1px solid transparent;
}
.art-item.selected {
  border-color: #0d6efd;
}
.art-thumb {
  position: absolute;
  top:0; left:0;
  width:100%; height:100%;
}

/* Yatay kayan kontrol satırı */
.controls-row {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  padding-bottom: 1rem;
}
.controls-row .form-group {
  flex: 0 0 auto;
  width: 10rem;
}
</style>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Hoşgeldiniz, <?= htmlspecialchars($username) ?></h2>
    <?php if($isAdmin): ?>
      <div>
        <a href="matrix_admin.php" class="btn btn-outline-primary">Matrix Admin</a>
        <span class="badge bg-info text-dark">Admin Modu</span>
      </div>
    <?php endif; ?>
  </div>

  <?php if($isAdmin): ?>
  <!-- Admin için cihaz–kullanıcı atama -->
  <h4>Cihaz Atamaları</h4>
  <table class="table table-sm mb-4">
    <thead>
      <tr>
        <th>Cihaz</th>
        <th>Atanmış Kullanıcı</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($devices as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['name']) ?> (ID <?= $d['id'] ?>)</td>
        <td>
          <select class="form-select form-select-sm assign-user" data-device-id="<?= $d['id'] ?>">
            <option value="">— Hiçbiri —</option>
            <?php foreach($users as $u): ?>
              <option value="<?= $u['id'] ?>"
                <?= $u['id']==$d['user_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['username']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </td>
        <td>
          <button class="btn btn-sm btn-primary save-assign" data-device-id="<?= $d['id'] ?>">
            Kaydet
          </button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <!-- Galeri: Resim Seçimi -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h4 class="card-title">Resim Seçimi</h4>
      <div id="artGrid">
        <?php foreach($art as $a): ?>
          <div class="art-item"
               data-type="<?= htmlspecialchars($a['type'],ENT_QUOTES) ?>"
               data-delay="<?= (int)$a['frame_delay'] ?>"
               data-pixels='<?= htmlspecialchars($a['pixel_data'],ENT_QUOTES) ?>'>
            <canvas class="art-thumb"></canvas>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Kontroller -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title">Panel Ayarları</h4>
      <div class="mb-3">
  <label for="deviceSelect" class="form-label">Cihaz</label>
  <select id="deviceSelect" class="form-select">
    <option value="">— Seçin —</option>
    <?php foreach($devices as $d): ?>
      <option value="<?= $d['id'] ?>">
        <?= htmlspecialchars($d['name']) ?>
        <?php if($isAdmin && !empty($d['assigned_user'])): ?>
          — <?= htmlspecialchars($d['assigned_user']) ?>
        <?php endif; ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>
      <div class="mb-3">
        <label for="colorPicker" class="form-label">Düz Renk</label>
        <input type="color" id="colorPicker"
               class="form-control form-control-color"
               value="#FFFFFF">
      </div>

      <!-- Rotate / Hız / Parlaklık satırı -->
      <div class="controls-row">
        <div class="form-group">
          <label for="rotateSelect" class="form-label">Rotate</label>
          <select id="rotateSelect" class="form-select">
            <option value="0">0°</option>
            <option value="90">90°</option>
            <option value="180">180°</option>
            <option value="270">270°</option>
          </select>
        </div>
        <div class="form-group">
          <label for="delayInput" class="form-label">Hız (ms/frame)</label>
          <input type="number" id="delayInput"
                 class="form-control"
                 value="250" min="30" max="1000" step="10">
        </div>
        <div class="form-group">
          <label for="brightnessInput" class="form-label">Parlaklık</label>
          <input type="number" id="brightnessInput"
                 class="form-control"
                 value="80" min="0" max="255" step="1">
        </div>
      </div>

      <button id="sendBtn" class="btn btn-primary mt-3">Gönder</button>
    </div>
  </div>
</div>

<script>
// 16×16 kareyi angle kadar döndüren yardımcı
function rotateFrame(arr, angle) {
  const N = 16, out = new Array(N*N);
  for (let i=0; i<N; i++) for (let j=0; j<N; j++) {
    const src = i*N + j;
    let dst;
    switch(angle) {
      case 90:  dst = j*N + (N-1-i); break;
      case 180: dst = (N-1-i)*N + (N-1-j); break;
      case 270: dst = (N-1-j)*N + i; break;
      default:  dst = src;
    }
    out[dst] = arr[src];
  }
  return out;
}

// Galeri önizleme ve seçim
document.querySelectorAll('#artGrid .art-item').forEach(item=>{
  const raw    = JSON.parse(item.dataset.pixels),
        frames = Array.isArray(raw[0])? raw : [raw],
        delay  = parseInt(item.dataset.delay,10)||250,
        type   = item.dataset.type,
        canvas = item.querySelector('canvas'),
        size   = canvas.parentElement.clientWidth,
        ctx    = canvas.getContext('2d'),
        cell   = size/16;
  let idx = 0;
  canvas.width = canvas.height = size;

  function draw() {
    ctx.clearRect(0,0,size,size);
    frames[idx].forEach((hex,i)=>{
      const x = i%16, y = (i/16)|0;
      ctx.fillStyle = '#' + hex;
      ctx.fillRect(x*cell,y*cell,cell,cell);
    });
  }
  draw();
  if(type==='anim'){
    setInterval(()=>{ idx=(idx+1)%frames.length; draw(); }, delay);
  }
  item.addEventListener('click', ()=>{
    document.querySelectorAll('.art-item.selected')
            .forEach(el=>el.classList.remove('selected'));
    item.classList.add('selected');
  });
});

// Admin: cihaz atama
<?php if($isAdmin): ?>
document.querySelectorAll('.save-assign').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    const did = btn.dataset.deviceId;
    const uid = document.querySelector(`.assign-user[data-device-id='${did}']`).value;
    fetch('assign_device.php',{
      method:'POST',
      credentials:'same-origin',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:new URLSearchParams({ device_id:did, user_id:uid })
    })
    .then(r=>r.json())
    .then(j=> j.success
      ? alert('Atama kaydedildi')
      : alert('Hata: '+j.msg)
    )
    .catch(()=>alert('Sunucu hatası'));
  });
});
<?php endif; ?>

// Gönder butonu
document.getElementById('sendBtn').addEventListener('click', ()=>{
  const did  = document.getElementById('deviceSelect').value;
  if (!did) return alert('Lütfen bir cihaz seçin.');

  const sel  = document.querySelector('.art-item.selected'),
        hex  = document.getElementById('colorPicker').value.slice(1).toUpperCase(),
        rot  = parseInt(document.getElementById('rotateSelect').value,10)   || 0,
        spd  = parseInt(document.getElementById('delayInput').value,10)     || 250,
        brt  = parseInt(document.getElementById('brightnessInput').value,10)|| 80;

  // Frame dizilerini oluştur
  let frames;
  if (sel) {
    const raw  = JSON.parse(sel.dataset.pixels),
          arrs = Array.isArray(raw[0])? raw : [raw];
    frames = arrs.map(f => rot? rotateFrame(f,rot) : f);
  } else {
    frames = [ Array(256).fill(hex) ];
  }

  // rotate parametresiyle birlikte payload
  const payload = {
    frames:     frames,
    delay:      spd,
    brightness: brt,
    rotate:     rot
  };

  fetch('command_api.php',{
    method:'POST',
    credentials:'same-origin',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:new URLSearchParams({
      device_id: did,
      frame:     JSON.stringify(payload)
    })
  })
  .then(r=>r.json())
  .then(j=>{ if(!j.success) alert('Hata: '+(j.msg||'Gönderilemedi')); })
  .catch(()=>alert('Sunucu hatası'));
});
</script>

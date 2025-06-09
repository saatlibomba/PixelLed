<?php
// pixel_admin.php (matrix_admin.php)
session_start();
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$user_id  = $_SESSION['user_id'];
$is_admin = (bool)$_SESSION['is_admin'];

// helper: ImagickPixel → HEX
function imagickPixelToHex(ImagickPixel $px): string {
    $c = $px->getColor(true);
    $r = min(255,max(0,round($c['r']*255)));
    $g = min(255,max(0,round($c['g']*255)));
    $b = min(255,max(0,round($c['b']*255)));
    return sprintf('%02X%02X%02X',$r,$g,$b);
}

// === AJAX upload / rename / delete ===
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];
    try {
        // --- 1) UPLOAD ---
        if ($action==='upload') {
            if (empty($_FILES['image'])||$_FILES['image']['error']!==UPLOAD_ERR_OK)
                throw new Exception('Upload error');
            $file     = $_FILES['image']['tmp_name'];
            $origName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            $pixelsData = [];
            $delay      = 0;

            if ($ext==='gif' && extension_loaded('imagick')) {
                $img = new Imagick($file);
                $img = $img->coalesceImages();
                $total = $img->getNumberImages();
                // 10 kareye oranla
                $keep = [];
                for ($i=0;$i<10;$i++){
                  $keep[] = (int)round($i*($total-1)/9);
                }
                $delay = 250; // sabit 250ms
                foreach ($img as $i=>$frame) {
                    if (!in_array($i,$keep,true)) continue;
                    $frame->transformImageColorspace(Imagick::COLORSPACE_SRGB);
                    $frame->setImageDepth(8);
                    $frame->thumbnailImage(16,16,true);
                    $it = $frame->getPixelIterator();
                    $rowPixels = [];
                    foreach ($it as $row)
                      foreach ($row as $px)
                        $rowPixels[] = imagickPixelToHex($px);
                    $pixelsData[] = $rowPixels;
                }
                $type = 'anim';
            } else {
                // static GD fallback
                $src = imagecreatefromstring(file_get_contents($file));
                $dst = imagecreatetruecolor(16,16);
                imagecopyresampled($dst,$src,0,0,0,0,16,16,imagesx($src),imagesy($src));
                $rowPixels=[];
                for($y=0;$y<16;$y++){
                  for($x=0;$x<16;$x++){
                    $rgb = imagecolorat($dst,$x,$y);
                    $r=($rgb>>16)&0xFF; $g=($rgb>>8)&0xFF; $b=$rgb&0xFF;
                    $rowPixels[] = sprintf('%02X%02X%02X',$r,$g,$b);
                  }
                }
                imagedestroy($src);
                imagedestroy($dst);
                $pixelsData[] = $rowPixels;
                $type='static';
                $delay=0;
            }

            // INSERT with user_id
            $stmt = $pdo->prepare("
                INSERT INTO pixel_art
                  (name,type,pixel_data,frame_delay,user_id)
                VALUES
                  (:name,:type,:pdata,:delay,:uid)
            ");
            $stmt->execute([
              'name'  => substr($origName,0,64),
              'type'  => $type,
              'pdata' => json_encode($pixelsData),
              'delay' => $delay,
              'uid'   => $user_id
            ]);

            echo json_encode([
              'success'=>true,
              'id'=>$pdo->lastInsertId(),
              'name'=>$origName
            ]);
            exit;
        }

        // --- 2) RENAME ---
        elseif ($action==='rename') {
            $id = intval($_POST['id']);
            $name = substr($_POST['name'],0,64);
            // izin kontrolü
            $check = $pdo->prepare("
              SELECT p.user_id, u.is_admin AS uploader_is_admin
              FROM pixel_art p
              JOIN users u ON p.user_id=u.id
              WHERE p.id=:id
            ");
            $check->execute(['id'=>$id]);
            $row = $check->fetch(PDO::FETCH_ASSOC);
            if (!$row) throw new Exception('Not found');
            $can = ($row['user_id']==$user_id)
                   || ($is_admin && !$row['uploader_is_admin']);
            if (!$can) throw new Exception('Permission denied');
            // update
            $pdo->prepare("UPDATE pixel_art SET name=:name WHERE id=:id")
                ->execute(['name'=>$name,'id'=>$id]);
            echo json_encode(['success'=>true]);
            exit;
        }

        // --- 3) DELETE ---
        elseif ($action==='delete') {
            $id = intval($_POST['id']);
            // izin kontrolü
            $check = $pdo->prepare("
              SELECT p.user_id, u.is_admin AS uploader_is_admin
              FROM pixel_art p
              JOIN users u ON p.user_id=u.id
              WHERE p.id=:id
            ");
            $check->execute(['id'=>$id]);
            $row = $check->fetch(PDO::FETCH_ASSOC);
            if (!$row) throw new Exception('Not found');
            $can = ($row['user_id']==$user_id)
                   || ($is_admin && !$row['uploader_is_admin']);
            if (!$can) throw new Exception('Permission denied');
            // delete
            $pdo->prepare("DELETE FROM pixel_art WHERE id=:id")
                ->execute(['id'=>$id]);
            echo json_encode(['success'=>true]);
            exit;
        }

        else {
            throw new Exception('Invalid action');
        }

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success'=>false,'msg'=>$e->getMessage()]);
        exit;
    }
}

// === GALLERY LISTING ===
if ($is_admin) {
    $itemsStmt = $pdo->prepare("
      SELECT p.id,p.name,p.type,p.frame_delay,p.pixel_data,
             p.user_id,u.is_admin AS uploader_is_admin,
             JSON_LENGTH(p.pixel_data) AS frames
      FROM pixel_art p
      JOIN users u ON p.user_id=u.id
      ORDER BY p.created_at DESC
    ");
    $itemsStmt->execute();
} else {
    $itemsStmt = $pdo->prepare("
      SELECT p.id,p.name,p.type,p.frame_delay,p.pixel_data,
             p.user_id,u.is_admin AS uploader_is_admin,
             JSON_LENGTH(p.pixel_data) AS frames
      FROM pixel_art p
      JOIN users u ON p.user_id=u.id
      WHERE p.user_id=:uid OR u.is_admin=1
      ORDER BY p.created_at DESC
    ");
    $itemsStmt->execute(['uid'=>$user_id]);
}
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/header.php';
?>
<div class="card p-4 shadow-sm">
    <h2>Pixel Art & GIF Library</h2>

    <!-- Upload Form (everyone can upload) -->
    <form id="uploadForm" enctype="multipart/form-data" class="mb-3">
        <input type="file" name="image" accept="image/*" class="form-control mb-2">
        <button class="btn btn-success">Upload & Convert</button>
    </form>

    <hr>

    <div id="gallery">
    <?php foreach ($items as $it): 
        // permission to edit/delete this item?
        $can_edit = ($it['user_id']==$user_id)
                    || ($is_admin && !$it['uploader_is_admin']);
    ?>
        <div class="d-inline-block m-2 text-center"
             data-id="<?= $it['id'] ?>"
             data-type="<?= $it['type'] ?>"
             data-delay="<?= $it['frame_delay'] ?>"
             data-pixels='<?= htmlspecialchars($it['pixel_data'],ENT_QUOTES) ?>'>
            <canvas width="80" height="80" class="preview"></canvas><br>
            <strong><?= htmlspecialchars($it['name']) ?></strong><br>
            <small>
                <?= $it['type']==='anim'
                  ? "{$it['frames']} frames @ {$it['frame_delay']} ms"
                  : 'static' ?>
            </small><br>

            <?php if($can_edit): ?>
                <input type="text" class="form-control form-control-sm mt-1 name-input"
                       value="<?= htmlspecialchars($it['name']) ?>">
                <button class="btn btn-sm btn-primary rename-btn mt-1">Rename</button>
                <button class="btn btn-sm btn-danger delete-btn mt-1">Delete</button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<script>
// render each preview
document.querySelectorAll('div[data-pixels]').forEach(div => {
    let frames = JSON.parse(div.dataset.pixels);
    if (!Array.isArray(frames[0])) frames = [frames];
    const type = div.dataset.type;
    const delay = parseInt(div.dataset.delay,10) || 500;
    const canvas = div.querySelector('canvas.preview');
    const ctx = canvas.getContext('2d');
    const N = 16, size = 5;
    let idx = 0;
    function draw(){
        ctx.clearRect(0,0,canvas.width,canvas.height);
        frames[idx].forEach((hex,i)=>{
            const x=i%N, y=Math.floor(i/N);
            ctx.fillStyle='#'+hex;
            ctx.fillRect(x*size,y*size,size,size);
        });
        if(type==='anim'){
            idx=(idx+1)%frames.length;
            setTimeout(draw,delay);
        }
    }
    draw();
});

// upload handler
document.getElementById('uploadForm').addEventListener('submit', e=>{
    e.preventDefault();
    const fd = new FormData(e.target);
    fetch('?action=upload', {
      method:'POST', credentials:'same-origin', body:fd
    })
    .then(r=>r.json())
    .then(j=>{ if(!j.success) alert(j.msg); else location.reload(); });
});

// rename & delete
document.getElementById('gallery').addEventListener('click', e=>{
    const div = e.target.closest('[data-id]');
    if(!div) return;
    const id = div.dataset.id;
    if(e.target.classList.contains('rename-btn')){
        const name = div.querySelector('.name-input').value;
        fetch(`?action=rename`, {
          method:'POST', credentials:'same-origin',
          headers:{'Content-Type':'application/x-www-form-urlencoded'},
          body:new URLSearchParams({id,name})
        }).then(r=>r.json()).then(j=>{
          if(!j.success) alert(j.msg);
        });
    }
    if(e.target.classList.contains('delete-btn')){
        if(!confirm('Delete this item?')) return;
        fetch(`?action=delete`, {
          method:'POST', credentials:'same-origin',
          headers:{'Content-Type':'application/x-www-form-urlencoded'},
          body:new URLSearchParams({id})
        }).then(r=>r.json()).then(j=>{
          if(j.success) div.remove(); else alert(j.msg);
        });
    }
});
</script>

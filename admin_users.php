<?php
session_start();
require 'includes/config.php';
require 'includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = $_POST['username'];
  $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $r = $_POST['redirect_to'];
  $a = isset($_POST['is_admin'])?1:0;
  $pdo->prepare("
    INSERT INTO users(username,password,redirect_to,is_admin)
    VALUES(:u,:p,:r,:a)
  ")->execute(['u'=>$u,'p'=>$p,'r'=>$r,'a'=>$a]);
}

$users = $pdo->query("SELECT id,username,redirect_to,is_admin,is_active FROM users")->fetchAll(PDO::FETCH_ASSOC);
$pages = ['pixel_admin'=>'16x16 Ekran','fan_admin'=>'Pervane','xyz_admin'=>'XYZ'];
require 'includes/header.php';
?>
<div class="card p-4 shadow-sm mb-4">
  <h2>Kullanıcılar</h2>
  <div class="table-responsive">
  <table class="table">
    <thead><tr><th>ID</th><th>Kullanıcı Adı</th><th>Yönlendir</th><th>Admin?</th><th>Aktif?</th></tr></thead>
    <tbody>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?=$u['id']?></td>
        <td><?=$u['username']?></td>
        <td><?=$pages[$u['redirect_to']]?></td>
        <td><?=$u['is_admin']? 'Evet':'Hayır'?></td>
        <td><?=$u['is_active']? 'Evet':'Kapalı'?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
<div class="card p-4 shadow-sm">
  <h2>Yeni Kullanıcı Ekle</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Kullanıcı Adı</label>
      <input name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Şifre</label>
      <input name="password" type="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Yönlendirilecek Sayfa</label>
      <select name="redirect_to" class="form-select">
        <?php foreach($pages as $key=>$label): ?>
          <option value="<?=$key?>"><?=$label?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
      <label class="form-check-label" for="is_admin">Yönetici</label>
    </div>
    <button type="submit" class="btn btn-success">Ekle</button>
  </form>
</div>
<?php require 'includes/footer.php'; ?>

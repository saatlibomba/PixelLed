<?php
session_start();
require 'includes/config.php';
require 'includes/auth.php';

$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  if (login($u, $p)) {
    header('Location: index.php');
    exit;
  } else {
    $err = 'Geçersiz kullanıcı veya şifre.';
  }
}
require 'includes/header.php';
?>
<div class="row justify-content-center">
 <div class="col-md-6">
  <div class="card p-4 shadow-sm">
    <h2 class="text-center mb-3">Giriş</h2>
    <?php if($err):?><div class="alert alert-danger"><?=$err?></div><?php endif;?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Kullanıcı Adı</label>
        <input name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Şifre</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
    </form>
    <div class="text-center mt-3">
      <a href="forgot.php">Şifreni mi unuttun?</a>
    </div>
  </div>
 </div>
</div>
<?php require 'includes/footer.php'; ?>

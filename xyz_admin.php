<?php
session_start();
require 'includes/config.php';
require 'includes/auth.php';
if (!is_logged_in()) { header('Location: login.php'); exit; }
if ($_SESSION['user']['redirect_to']!=='xyz_admin' && !$_SESSION['user']['is_admin']) {
  echo "Bu sayfaya erişim yok."; exit;
}
require 'includes/header.php';
?>
<div class="card p-4 shadow-sm">
  <h2>XYZ Admin Paneli</h2>
  <!-- AJAX ile komut -->
</div>
<?php require 'includes/footer.php'; ?>

<?php
session_start();
require 'includes/config.php';
require 'includes/auth.php';
require_admin();
require 'includes/header.php';
?>
<div class="card p-4 shadow-sm">
  <h2>Admin Paneli</h2>
  <div class="list-group">
    <a href="admin_users.php" class="list-group-item list-group-item-action">Kullanıcı Yönetimi</a>
    <a href="pixel_admin.php" class="list-group-item list-group-item-action">16x16 Pixel Admin</a>
    <a href="fan_admin.php" class="list-group-item list-group-item-action">Pervane Kontrol Admin</a>
    <a href="xyz_admin.php" class="list-group-item list-group-item-action">XYZ Admin</a>
  </div>
</div>
<?php require 'includes/footer.php'; ?>

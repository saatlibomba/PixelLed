<?php
if(session_status()!=PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LED Kontrol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="includes/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">LED Kontrol</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if(!empty($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="admin_users.php">Kullanıcı Yönetimi</a></li>
          <li class="nav-item"><a class="nav-link" href="mirror_admin.php">Ayna Çerçevesi</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Çıkış</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Giriş</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">

<?php
// includes/auth.php

// includes/auth.php
function login($username, $password) {
  global $pdo;
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:u AND is_active=1");
  $stmt->execute(['u'=>$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
      'id'          => $user['id'],
      'username'    => $user['username'],
      'redirect_to' => $user['redirect_to'],
      'is_admin'    => (bool)$user['is_admin']
    ];
    // Kolay erişim için düz anahtarlar da ekleyelim:
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['is_admin'] = (bool)$user['is_admin'];
    return true;
  }
  return false;
}

function is_logged_in() {
  return !empty($_SESSION['user_id']);
}

function require_admin() {
  if (!is_logged_in() || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
  }
}
?>
<?php
session_start();
require 'includes/auth.php';

if (is_logged_in()) {
  $page = $_SESSION['user']['redirect_to'];
  header("Location: {$page}.php");
  exit;
}
header("Location: login.php");

<?php
// Database configuration
$host = 'localhost';
$db   = 'u2252740_backend';
$user = 'u2252740_backend';
$pass = 'SaatliBombam01!';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
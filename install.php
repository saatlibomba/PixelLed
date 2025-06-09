<?php
/**
 * install.php
 * Database migration and setup script with admin seeding.
 */

require 'includes/config.php';

try {
    // Create migrations table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) UNIQUE,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Apply migrations
    $files = glob(__DIR__ . '/migrations/*.sql');
    sort($files);
    foreach ($files as $file) {
        $name = basename($file);
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM migrations WHERE migration = ?');
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() == 0) {
            $sql = file_get_contents($file);
            $pdo->exec($sql);
            $stmtInsert = $pdo->prepare('INSERT INTO migrations (migration) VALUES (?)');
            $stmtInsert->execute([$name]);
            echo "Applied migration: $name<br>\n";
        } else {
            echo "Migration already applied: $name<br>\n";
        }
    }

    // Seed default admin if none exists
    $count = $pdo->query("SELECT COUNT(*) FROM users WHERE is_admin = 1")->fetchColumn();
    if ($count == 0) {
        $defaultPassword = 'admin123';  // Default admin password
        $hash = password_hash($defaultPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, redirect_to, is_active, is_admin)
                              VALUES ('admin', :hash, 'admin', 1, 1)");
        $stmt->execute(['hash' => $hash]);
        echo "<br>Default admin created: username='admin', password='{$defaultPassword}'<br>\n";
    }

    echo "<br>All migrations applied successfully.";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
?>
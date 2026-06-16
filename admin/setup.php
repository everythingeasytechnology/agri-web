<?php
// ONE-TIME SETUP SCRIPT — delete this file after use
require_once __DIR__ . '/db.php';

$pdo = db_connect();
$results = [];

// 1. Create admins table
$pdo->exec("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'admin',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$results[] = '✅ admins table ready';

// 2. Create/update admin user with password: Admin@1234
$new_password = 'Admin@1234';
$hash = password_hash($new_password, PASSWORD_BCRYPT);
$existing = db_fetch('SELECT id FROM admins WHERE username = ?', ['admin']);
if ($existing) {
    $pdo->prepare('UPDATE admins SET password_hash = ? WHERE username = ?')->execute([$hash, 'admin']);
    $results[] = '✅ admin password reset to: <strong>Admin@1234</strong>';
} else {
    $pdo->prepare('INSERT INTO admins (username, password_hash, role) VALUES (?, ?, ?)')->execute(['admin', $hash, 'superadmin']);
    $results[] = '✅ admin user created — username: <strong>admin</strong> / password: <strong>Admin@1234</strong>';
}

// 3. Create blogs table
$pdo->exec("CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(300) NULL,
    author VARCHAR(150) NOT NULL,
    category VARCHAR(150) NOT NULL,
    excerpt TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    image_url VARCHAR(500),
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
// Add slug column if missing
try {
    $pdo->exec("ALTER TABLE blogs ADD COLUMN slug VARCHAR(300) NULL AFTER title");
    $results[] = '✅ slug column added to blogs';
} catch (Exception $e) {
    $results[] = '✅ blogs table ready (slug column already exists)';
}

// 4. Create products table
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(500),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$results[] = '✅ products table ready';

// 5. Create contact_queries table
$pdo->exec("CREATE TABLE IF NOT EXISTS contact_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(100),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new','read','replied') NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$results[] = '✅ contact_queries table ready';

// 6. Create instagram_reels table
$pdo->exec("CREATE TABLE IF NOT EXISTS instagram_reels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(150) NOT NULL,
    reel_url VARCHAR(500) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$results[] = '✅ instagram_reels table ready';

// 7. Session test
session_start();
$_SESSION['setup_test'] = 'ok';
$session_ok = ($_SESSION['setup_test'] === 'ok');
$results[] = $session_ok ? '✅ Sessions working' : '❌ Sessions NOT working — contact your host';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Setup</title>
<style>
body{font-family:sans-serif;max-width:600px;margin:60px auto;padding:20px;background:#f8f8f8;}
.card{background:#fff;border-radius:10px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);}
h2{color:#1a1a1a;margin-bottom:20px;}
ul{list-style:none;padding:0;margin:0;}
li{padding:10px 0;border-bottom:1px solid #f0f0f0;font-size:15px;color:#333;}
li:last-child{border:none;}
.login-btn{display:inline-block;margin-top:22px;background:#88b04b;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;}
.warn{background:#fef3c7;border:1px solid #f59e0b;border-radius:8px;padding:12px 16px;font-size:13px;color:#92400e;margin-top:20px;}
</style>
</head>
<body>
<div class="card">
    <h2>🌿 Ficus Admin Setup</h2>
    <ul>
        <?php foreach ($results as $r): ?>
        <li><?= $r ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php" class="login-btn">Go to Admin Login →</a>
    <div class="warn">
        ⚠️ <strong>Delete this file after use:</strong> <code>admin/setup.php</code>
    </div>
</div>
</body>
</html>

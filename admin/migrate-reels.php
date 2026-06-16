<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

$pdo = db_connect();
$pdo->exec("CREATE TABLE IF NOT EXISTS instagram_reels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(150) NOT NULL,
  reel_url VARCHAR(500) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo '<p style="font-family:sans-serif;color:green;padding:20px;font-size:18px;">
  ✅ Table <strong>instagram_reels</strong> created successfully!<br>
  <a href="reels.php" style="color:#88b04b;">→ Go to Reels Admin</a>
</p>';

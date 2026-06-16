<?php
require_once __DIR__ . '/auth.php';
if (admin_is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password && admin_login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login | Ficus International</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="admin.css" />
</head>
<body>

<div class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <div class="logo-icon"><i class="fas fa-leaf"></i></div>
            <h1>Ficus International</h1>
            <p>Admin Control Panel</p>
        </div>
        <div class="login-divider"></div>
        <?php if ($error): ?>
        <div style="background:#fff1f1;border:1px solid #fca5a5;color:#b91c1c;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;display:flex;align-items:center;gap:8px">
            <i class="fas fa-exclamation-circle"></i> <?= html_escape($error) ?>
        </div>
        <?php endif; ?>
        <form method="post" action="index.php">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Enter username" value="<?= html_escape($_POST['username'] ?? '') ?>" required autofocus />
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter password" required />
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right:6px"></i> Sign In to Dashboard
            </button>
        </form>
    </div>
</div>

</body>
</html>

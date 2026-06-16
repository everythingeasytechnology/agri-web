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
        <form id="loginForm">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Enter username" />
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Enter password" />
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right:6px"></i> Sign In to Dashboard
            </button>
        </form>
        <!-- <div class="login-footer">
            &copy; 2024 Ficus International. All rights reserved.<br>
            <span style="margin-top:4px;display:block">Powered by <a href="https://www.rccsglobal.com" style="color:var(--secondary)">Royal Crown Consultancy Services</a></span>
        </div> -->
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    window.location.href = 'dashboard.php';
});
</script>
</body>
</html>

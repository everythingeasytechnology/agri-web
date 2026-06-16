<?php
require_once __DIR__ . '/db.php';

function admin_is_logged_in()
{
    return !empty($_SESSION['admin_id']);
}

function require_admin_login()
{
    if (!admin_is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function admin_username()
{
    return $_SESSION['admin_username'] ?? 'Admin';
}

function admin_login($username, $password)
{
    $user = db_fetch('SELECT * FROM admins WHERE username = ?', [$username]);
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        return true;
    }
    return false;
}

function admin_logout()
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

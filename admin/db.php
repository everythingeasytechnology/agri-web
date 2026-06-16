<?php
if (!session_id()) {
    session_start();
}

if (!defined('DB_HOST')) {
    define('DB_HOST', '68.178.236.80');
    define('DB_NAME', 'agriculture');
    define('DB_USER', 'agriculture');
    define('DB_PASS', 'mv)NiQT{*swc;3Ob');
}

function db_connect()
{
    static $pdo;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]);
    }
    return $pdo;
}

function db_query($sql, $params = [])
{
    $stmt = db_connect()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function db_fetch($sql, $params = [])
{
    return db_query($sql, $params)->fetch();
}

function db_fetch_all($sql, $params = [])
{
    return db_query($sql, $params)->fetchAll();
}

function html_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function flash_set($message, $type = 'success')
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function flash_get()
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

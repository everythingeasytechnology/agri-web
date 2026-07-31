<?php
require_once __DIR__ . '/auth.php';
require_admin_login();

header('Content-Type: application/json');

$upload_dir = __DIR__ . '/../uploads/blog/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

function respond_error($message) {
    http_response_code(400);
    echo json_encode(['error' => ['message' => $message]]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['upload'])) {
    respond_error('No file uploaded.');
}

$file = $_FILES['upload'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    respond_error('Upload failed.');
}

$ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
if (!in_array($ext, $allowed, true)) {
    respond_error('Unsupported image format. Use JPG, PNG, WebP or GIF.');
}

if ($file['size'] > 5 * 1024 * 1024) {
    respond_error('Image too large. Max allowed is 5 MB.');
}

$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$mime     = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
$mime_map = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
if (!isset($mime_map[$ext]) || $mime !== $mime_map[$ext]) {
    respond_error('File content does not match a valid image format.');
}

$filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
if (!move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
    respond_error('Could not save uploaded file.');
}

$site_root = dirname($_SERVER['SCRIPT_NAME'], 2);
$site_root = ($site_root === '/' || $site_root === '\\' || $site_root === '.') ? '' : $site_root;
echo json_encode(['url' => $site_root . '/uploads/blog/' . $filename]);

<?php
require_once __DIR__ . '/admin/db.php';

// Allowed return destinations (whitelist to prevent open redirect)
$allowed_returns = ['contact', 'index'];
$raw_return  = trim($_POST['return_to'] ?? 'contact');
$base        = in_array($raw_return, $allowed_returns, true) ? $raw_return : 'contact';
$return_page = $base . '.php';
$anchor      = $base === 'index' ? '#contact' : '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $return_page);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '') ?: 'General Enquiry';
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    header('Location: ' . $return_page . '?status=error' . $anchor);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $return_page . '?status=error' . $anchor);
    exit;
}

db_query(
    'INSERT INTO contact_queries (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, ?)',
    [$name, $email, $phone ?: null, $subject, $message, 'new']
);

header('Location: ' . $return_page . '?status=success' . $anchor);
exit;

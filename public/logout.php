<?php
session_start();

require_once __DIR__ . '/../src/lib/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit();
}

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'セキュリティトークンが無効です。';
    header('Location: /index.php');
    exit();
}

$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

session_destroy();

header('Location: /login.php');
exit();
?> 
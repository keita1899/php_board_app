<?php
session_start();

require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/lib/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit();
}

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'セキュリティトークンが無効です。';
    header('Location: /index.php');
    exit();
}

logout();

header('Location: /login.php');
exit();
?> 
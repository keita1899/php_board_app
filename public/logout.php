<?php
session_start();

require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit();
}

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_errors('logout.php', 'logout', ['form' => MESSAGES['error']['security']['invalid_csrf']], $_POST);
}

logout();

header('Location: /login.php');
exit();
?> 
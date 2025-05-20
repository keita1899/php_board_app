<?php
session_start();
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/admin.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';
require_once __DIR__ . '/../src/lib/flash_message.php';

$errors = get_form_errors('admin_login');
$old = get_form_old('admin_login');
clear_form_errors('admin_login');
clear_form_old('admin_login');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('admin_login.php');
  }

  $form_data = [
    'username' => $_POST['username'] ?? '',
    'password' => $_POST['password'] ?? '',
  ];

  $pdo = getPDO();

  admin_login($pdo, $form_data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理ログイン</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>
  <h1>管理ログイン</h1>

  <form action="admin_login.php" method="post" class="register-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    <?php $name = 'form'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    <div class="form-group">
      <label for="username">ユーザー名</label>
      <input type="text" id="username" name="username"  value="<?= htmlspecialchars($old['username'] ?? '') ?>">
      <?php $name = 'username'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <div class="form-group">
      <label for="password">パスワード</label>
      <input type="password" id="password" name="password">
      <?php $name = 'password'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <input type="submit" value="ログイン" class="submit-btn">
  </form>
</body>
</html>
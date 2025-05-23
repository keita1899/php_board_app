<?php
session_start();
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/login.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';
require_once __DIR__ . '/../src/lib/flash_message.php';
require_once __DIR__ . '/../src/config/database.php';

$errors = get_form_errors('login');
$old = get_form_old('login');
clear_form_errors('login');
clear_form_old('login');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('login.php');
  }

  $form_data = [
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
  ];

  $pdo = getPDO();

  login($pdo, $form_data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>
  <h1>ログイン</h1>

  <form action="login.php" method="post" class="register-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    <?php $name = 'form'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    <div class="form-group">
      <label for="email">メールアドレス</label>
      <input type="email" id="email" name="email" placeholder="メールアドレス" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
      <?php $name = 'email'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <div class="form-group">
      <label for="password">パスワード</label>
      <input type="password" id="password" name="password" placeholder="パスワード">
      <?php $name = 'password'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <input type="submit" value="ログイン" class="submit-btn">
  </form>
</body>
</html>
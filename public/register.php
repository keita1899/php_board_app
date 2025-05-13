<?php
session_start();
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/register.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';

$errors = get_form_errors('register');
$old = get_form_old('register');
clear_form_errors('register');
clear_form_old('register');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('register.php');
  }
  register($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <h1>新規登録</h1>

  <form action="register.php" method="post" class="register-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    <div class="form-group">
      氏名
      <label for="last_name">姓</label>
      <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
      <label for="first_name">名</label>
      <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
      <?php $name = 'last_name'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      <?php $name = 'first_name'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <div class="form-group">
      性別
      <input type="radio" id="male" name="gender" value="male" <?= ($old['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
      <label for="male">男性</label>
      <input type="radio" id="female" name="gender" value="female" <?= ($old['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
      <label for="female">女性</label>
      <?php $name = 'gender'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <div class="form-group">
      住所
      <?php include __DIR__ . '/../src/partials/prefecture_select.php'; ?>
      <div>
        <label for="address">それ以降の住所</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($old['address'] ?? '') ?>">
        <?php $name = 'address'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
    </div>
    <div class="form-group">
      <label for="password">パスワード</label>
      <input type="password" id="password" name="password" value="<?= htmlspecialchars($old['password'] ?? '') ?>">
      <?php $name = 'password'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <div class="form-group">
      <label for="password_confirm">パスワード確認</label>
      <input type="password" id="password_confirm" name="password_confirm" value="<?= htmlspecialchars($old['password_confirm'] ?? '') ?>">
      <?php $name = 'password_confirm'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <div class="form-group">
      <label for="email">メールアドレス</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
      <?php $name = 'email'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <input type="submit" value="確認画面へ" class="submit-btn">
  </form>
</body>
</html>
<?php
session_start();
$errors = $_SESSION['signup_errors'] ?? [];
$old = $_SESSION['signup_old'] ?? [];
unset($_SESSION['signup_errors'], $_SESSION['signup_old']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/../src/app/signup.php';
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
  <h1>新規登録</h1>

  <form action="signup.php" method="post" class="signup-form">
    <div class="form-group">
      <label for="username">ユーザー名</label>
      <input type="text" id="username" name="username" placeholder="ユーザー名" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
      <?php $name = 'username'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
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
    <div class="form-group">
      <label for="password_confirm">パスワード確認</label>
      <input type="password" id="password_confirm" name="password_confirm" placeholder="パスワード確認">
      <?php $name = 'password_confirm'; include __DIR__ . '/../src/partials/error_message.php'; ?>
    </div>
    <input type="submit" value="新規登録" class="submit-btn">
  </form>
</body>
</html>
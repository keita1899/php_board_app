<?php
session_start();

require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/user.php';
require_once __DIR__ . '/../src/validations/user.php';

require_login();

$errors = get_form_errors('withdraw');
clear_form_errors('withdraw');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('withdraw.php');
  }

  $password = $_POST['password'] ?? '';

  $errors = [];

  if ($error = validate_password($password)) {
    $errors['password'] = $error;
  }
  
  if ($errors) {
    redirect_with_errors('/withdraw.php', 'withdraw', $errors, []);
  }

  $pdo = getPDO();

  $password_hash = fetch_password_by_id($pdo, $_SESSION['user_id']);

  if (!$password_hash || !password_verify($password, $password_hash)) {
    $errors['password'] = 'パスワードが正しくありません';
  }
  
  if (empty($errors)) {
    delete_user($pdo, $_SESSION['user_id']);
    logout();
    redirect('/login.php');
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>退会</title>
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <h1>退会</h1>
  <p>退会すると、アカウントとデータが削除されます。</p>
  <form method="POST" action="/withdraw.php">
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token(); ?>">
    
    <div class="mb-3">
      <label for="password" class="form-label">パスワード</label>
      <input type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              >
      <?php $name = 'password'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      <div class="form-text">退会を完了するには、現在のパスワードを入力してください</div>
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-danger">退会する</button>
      <a href="/index.php" class="btn btn-secondary">キャンセル</a>
    </div>
  </form>
</body>
</html>


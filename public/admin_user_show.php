<?php
session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/app/user.php';
require_once __DIR__ . '/../src/lib/helper.php';
require_once __DIR__ . '/../src/models/user.php';

require_admin_login();

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
  if (!$user_id) {
      set_flash_message('error', 'user', 'invalid_id');
      redirect('admin_user_index.php');
  }
  
  $user = fetch_user_by_id($pdo, $user_id);
  if (!$user) {
      set_flash_message('error', 'user', 'not_found');
      redirect('admin_user_index.php');
  }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員詳細</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <div>
    <h1>会員詳細</h1>
    <p>ID: <?= htmlspecialchars($user['id']); ?></p>
    <p>氏名: <?= htmlspecialchars($user['first_name']); ?> <?= htmlspecialchars($user['last_name']); ?></p>
    <p>性別: <?= htmlspecialchars(gender_label($user['gender'])); ?></p>
    <p>住所: <?= htmlspecialchars($user['prefecture']); ?> <?= htmlspecialchars($user['address']); ?></p>
    <p>メールアドレス: <?= htmlspecialchars($user['email']); ?></p>
    <p>作成日時: <?= htmlspecialchars($user['created_at']); ?></p>
    <p>更新日時: <?= htmlspecialchars($user['updated_at']); ?></p>
    <p><a href="admin_user_index.php" class="button">会員一覧に戻る</a></p>
  </div>

</body>
</html>

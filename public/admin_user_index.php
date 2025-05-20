<?php
session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/app/user.php';

require_admin_login();

$order = $_GET['order'] ?? 'desc';
$pdo = getPDO();
$users = fetch_users($pdo, $order);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員一覧</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <div>
    <h1>会員一覧</h1>
    <form method="get" action="">
      <label for="order">並び順:</label>
      <select name="order" id="order" onchange="this.form.submit()">
        <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>新しい順</option>
        <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>古い順</option>
      </select>
    </form>
  </div>

  <table class="user-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>氏名</th>
        <th>作成日時</th>
        <th>更新日時</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (
        $users as $user) : ?>
        <tr>
          <td><?= htmlspecialchars($user['id']); ?></td>
          <td>
            <a href="admin_user_show.php?id=<?= htmlspecialchars($user['id']); ?>">
              <?= htmlspecialchars($user['first_name']); ?> <?= htmlspecialchars($user['last_name']); ?>
            </a>
          </td>
          <td><?= htmlspecialchars($user['created_at']); ?></td>
          <td><?= htmlspecialchars($user['updated_at']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>

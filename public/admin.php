<?php
session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';

require_admin_login();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理者ダッシュボード</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <div>
    <h1>管理者ダッシュボード</h1>
    <p>管理者専用の操作を行えます。</p>
  </div>
</body>
</html>

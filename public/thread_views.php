<?php

session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/thread_view.php';

require_login();

$pdo = getPDO();
$views = fetch_thread_views($pdo, $_SESSION['user_id'], 20);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>スレッド閲覧履歴</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>
  <?php include __DIR__ . '/../src/partials/sidebar.php'; ?>

  <h1>最近見たスレッド</h1>

  <div class="threads">
    <?php if (empty($views)): ?>
      <p>スレッドはありません</p>
    <?php else: ?>
      <?php foreach ($views as $view): ?>
        <a href="/thread.php?id=<?= $view['id'] ?>" class="thread-link">
          <div class="thread">
            <div class="thread-header">
              <span class="thread-date"><?= htmlspecialchars((new DateTime($view['viewed_at']))->format('Y/m/d H:i')) ?></span>
            </div>
            <div class="thread-title"><?= htmlspecialchars($view['title']) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>


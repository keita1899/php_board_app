<?php
session_start();

require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/thread.php';
require_once __DIR__ . '/../src/validations/thread.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';
require_once __DIR__ . '/../src/lib/flash_message.php';

$errors = get_form_errors('thread');
$old = get_form_old('thread');
clear_form_errors('thread');
clear_form_old('thread');

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $threads = get_threads($pdo);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('index.php');
  }

  $old = [
    'title' => $_POST['title'] ?? '',
  ];

  $errors = validate_thread($old);
  if ($errors) {
    redirect_with_errors('index.php', 'thread', $errors, $old);
  }

  if (create_thread($pdo, $_SESSION['user_id'], $_POST['title'])) {
    set_flash_message('success', 'thread', 'created');
    redirect('index.php');
  } else {
    set_flash_message('error', 'thread', 'create_failed');
    redirect_with_errors('index.php', 'thread', $errors, $old);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>
  <?php include __DIR__ . '/../src/partials/sidebar.php'; ?>
  <?php if (isset($_SESSION['user_id'])): ?>
    <form action="index.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
      
      <div class="form-group">
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
        <?php $name = 'title'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
  
      <div class="form-group">
        <button type="submit">スレッドを作成する</button>
      </div>
    </form>
  <?php endif; ?>

  <div class="threads">
    <?php if (empty($threads)): ?>
      <p>スレッドはありません</p>
    <?php else: ?>
      <?php foreach ($threads as $thread): ?>
        <a href="/thread.php?id=<?= $thread['id'] ?>" class="thread-link">
          <div class="thread">
            <div class="thread-header">
              <span class="thread-date"><?= htmlspecialchars((new DateTime($thread['created_at']))->format('Y/m/d H:i')) ?></span>
            </div>
            <div class="thread-title"><?= htmlspecialchars($thread['title']) ?></div>
            <?php if ($thread['updated_at'] !== $thread['created_at']): ?>
              <div class="thread-meta">
                編集日時: <?= htmlspecialchars((new DateTime($thread['updated_at']))->format('Y/m/d H:i')) ?>
              </div>
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>


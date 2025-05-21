<?php
session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/validations/thread.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';
require_once __DIR__ . '/../src/lib/flash_message.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/thread.php';

require_login();

$pdo = getPDO();

$errors = get_form_errors('thread');
$old = get_form_old('thread');
clear_form_errors('thread');
clear_form_old('thread');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $thread_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
  if (!$thread_id) {
    set_flash_message('error', 'thread', 'not_found');
    redirect('index.php');
  }

  $thread = fetch_thread($pdo, $thread_id);
  if (!$thread) {
    set_flash_message('error', 'thread', 'not_found');
    redirect('index.php');
  }

  if (!is_thread_owner($thread['user_id'], $_SESSION['user_id'])) {
    set_flash_message('error', 'thread', 'not_owner');
    redirect('index.php');
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_login();

  $thread_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('edit.php?id=' . $thread_id);
  }

  if (!$thread_id) {
    redirect('index.php');
  }

  $old = [
      'title' => $_POST['title'] ?? '',
  ];

  $errors = validate_thread($old);
  if ($errors) {
    redirect_with_errors('edit.php?id=' . $thread_id, 'thread', $errors, $old);
  }

  if (update_thread($pdo, $thread_id, $_SESSION['user_id'], $_POST['title'])) {
    set_flash_message('success', 'thread', 'updated');
    redirect('thread.php?id=' . $thread_id);
  } else {
    set_flash_message('error', 'thread', 'update_failed');
    redirect_with_errors('edit.php?id=' . $thread_id, 'thread', $errors, $old);
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

    <form action="edit.php" method="post">
      <input type="hidden" name="id" value="<?= htmlspecialchars($thread['id']) ?>">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
      
      <div class="form-group">
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? $thread['title']) ?>">
        <?php $name = 'title'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
  
      <div class="form-group">
        <button type="submit">更新する</button>
        <a href="thread.php?id=<?= $thread['id'] ?>" class="edit-link">キャンセル</a>
      </div>
    </form>
</body>
</html>


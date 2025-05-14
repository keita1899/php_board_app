<?php
session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/post.php';
require_once __DIR__ . '/../src/validations/post.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';
require_once __DIR__ . '/../src/lib/flash_message.php';

require_login();

$pdo = getPDO();

$errors = get_form_errors('post');
$old = get_form_old('post');
clear_form_errors('post');
clear_form_old('post');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
  if (!$post_id) {
    set_flash_message('error', 'post', 'not_found');
    redirect('index.php');
  }

  $post = get_post($pdo, $post_id);
  if (!$post) {
    set_flash_message('error', 'post', 'not_found');
    redirect('index.php');
  }

  if (!is_post_owner($post['user_id'], $_SESSION['user_id'])) {
    set_flash_message('error', 'post', 'not_owner');
    redirect('index.php');
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_login();

  $post_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('edit.php?id=' . $post_id);
  }

  if (!$post_id) {
    redirect('index.php');
  }

  $old = [
      'title' => $_POST['title'] ?? '',
      'content' => $_POST['content'] ?? '',
  ];

  $errors = validate_post($old);
  if ($errors) {
    redirect_with_errors('edit.php?id=' . $post_id, 'post', $errors, $old);
  }

  if (update_post($pdo, $post_id, $_SESSION['user_id'], $_POST['title'], $_POST['content'])) {
    set_flash_message('success', 'post', 'updated');
    redirect('post.php?id=' . $post_id);
  } else {
    set_flash_message('error', 'post', 'update_failed');
    redirect_with_errors('edit.php?id=' . $post_id, 'post', $errors, $old);
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
      <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
      
      <div class="form-group">
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? $post['title']) ?>">
        <?php $name = 'title'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
  
      <div class="form-group">
        <label for="content">内容:</label>
        <textarea id="content" name="content"><?= htmlspecialchars($old['content'] ?? $post['content']) ?></textarea>
        <?php $name = 'content'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
  
      <div class="form-group">
        <button type="submit">更新する</button>
        <a href="post.php?id=<?= $post['id'] ?>" class="edit-link">キャンセル</a>
      </div>
    </form>
</body>
</html>


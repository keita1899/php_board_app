<?php
session_start();

require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/get_post.php';


require_login();

$errors = $_SESSION['post_errors'] ?? [];
$old = $_SESSION['post_old'] ?? [];
unset($_SESSION['post_errors'], $_SESSION['post_old']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['post_errors']['form'] = 'セキュリティトークンが無効です。ページを再読み込みしてください。';
    header('Location: index.php');
    exit;
  }
  require_once __DIR__ . '/../src/app/update_post.php';
}

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$post_id) {
    header('Location: /index.php');
    exit;
}

$post = get_post($post_id);
if (!$post) {
    header('Location: /index.php');
    exit;
}

if ($post['user_id'] !== $_SESSION['user_id']) {
    header('Location: /index.php');
    exit;
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


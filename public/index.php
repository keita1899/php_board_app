<?php
session_start();

require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/post.php';
require_once __DIR__ . '/../src/lib/validation.php';
require_once __DIR__ . '/../src/lib/util.php';

$errors = get_form_errors('post');
$old = get_form_old('post');
clear_form_errors('post');
clear_form_old('post');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_errors('index.php', 'post', ['form' => 'セキュリティトークンが無効です。ページを再読み込みしてください。'], $_POST);
  }

  $old = [
    'title' => $_POST['title'] ?? '',
    'content' => $_POST['content'] ?? '',
  ];

  $errors = validate_post($old);
  if ($errors) {
    redirect_with_errors('index.php', 'post', $errors, $old);
  }

  $pdo = getPDO();
  if (create_post($pdo, $_SESSION['user_id'], $_POST['title'], $_POST['content'])) {
    header('Location: /index.php');
    exit;
  } else {
    redirect_with_errors('index.php', 'post', ['form' => '投稿の作成に失敗しました。'], $old);
  }
}

$pdo = getPDO();
$posts = get_posts($pdo);

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
  <?php if (isset($_SESSION['user_id'])): ?>
    <form action="index.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
      
      <div class="form-group">
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
        <?php $name = 'title'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
  
      <div class="form-group">
        <label for="content">内容:</label>
        <textarea id="content" name="content"><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
        <?php $name = 'content'; include __DIR__ . '/../src/partials/error_message.php'; ?>
      </div>
  
      <div class="form-group">
        <button type="submit">投稿する</button>
      </div>
    </form>
  <?php endif; ?>

  <div class="posts">
    <?php if (empty($posts)): ?>
      <p>投稿はありません</p>
    <?php else: ?>
      <?php foreach ($posts as $post): ?>
        <a href="/post.php?id=<?= $post['id'] ?>" class="post-link">
          <div class="post">
            <div class="post-header">
              <span class="post-author"><?= htmlspecialchars($post['username']) ?></span>
              <span class="post-date"><?= htmlspecialchars((new DateTime($post['created_at']))->format('Y/m/d H:i')) ?></span>
            </div>
            <div class="post-title"><?= htmlspecialchars($post['title']) ?></div>
            <div class="post-content"><?= htmlspecialchars($post['content']) ?></div>
            <?php if ($post['updated_at'] !== $post['created_at']): ?>
              <div class="post-meta">
                編集日時: <?= htmlspecialchars((new DateTime($post['updated_at']))->format('Y/m/d H:i')) ?>
              </div>
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>


<?php
session_start();
require_once __DIR__ . '/../src/app/get_post.php';
require_once __DIR__ . '/../src/lib/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: /index.php');
        exit;
    }
    require_once __DIR__ . '/../src/app/delete_post.php';
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="/index.php" class="back-link">← 戻る</a>
        
        <div class="post">
            <div class="post-header">
                <span class="post-author">投稿者: <?= htmlspecialchars($post['username']) ?></span>
                <span class="post-date">投稿日時: <?= htmlspecialchars((new DateTime($post['created_at']))->format('Y/m/d H:i')) ?></span>
                <?php if ($_SESSION['user_id'] === $post['user_id']): ?>
                    <a href="edit.php?id=<?= $post['id'] ?>" class="edit-link">編集</a>
                    <form action="post.php?id=<?= $post['id'] ?>" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                <?php endif; ?>
            </div>
            
            <div class="post-title"><?= htmlspecialchars($post['title']) ?></div>
            <div class="post-content"><?= htmlspecialchars($post['content']) ?></div>
            
            <?php if ($post['updated_at'] !== $post['created_at']): ?>
                <div class="post-meta">
                    最終更新: <?= htmlspecialchars((new DateTime($post['updated_at']))->format('Y/m/d H:i')) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

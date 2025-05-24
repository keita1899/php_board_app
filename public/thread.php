<?php
session_start();
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/lib/util.php';
require_once __DIR__ . '/../src/config/message.php';
require_once __DIR__ . '/../src/lib/flash_message.php';
require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/thread.php';
require_once __DIR__ . '/../src/models/comment.php';
require_once __DIR__ . '/../src/validations/comment.php';

$pdo = getPDO();
$errors = get_form_errors('comment');
$old = get_form_old('comment');

clear_form_errors('comment');
clear_form_old('comment');

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
    $comments = fetch_comments_by_thread_id($pdo, $thread_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login();

    $thread_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;

    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash_message('error', 'security', 'invalid_csrf');
        redirect('thread.php?id=' . $thread_id);
    }

    if (!$thread_id) {
        set_flash_message('error', 'thread', 'not_found');
        redirect('index.php');
    }

    $thread = fetch_thread($pdo, $thread_id);
    if (!$thread) {
        set_flash_message('error', 'thread', 'not_found');
        redirect('index.php');
    }

    if (isset($_POST['comment'])) {
        $content = trim($_POST['content']);
        $errors['content'] = validate_comment($content);

        if (array_filter($errors)) {
            redirect_with_errors('thread.php?id=' . $thread_id, 'comment', $errors, $_POST);
        }

        if (create_comment($pdo, $thread_id, (int)$_SESSION['user_id'], $content)) {
            set_flash_message('success', 'comment', 'created');
            redirect('thread.php?id=' . $thread_id);
        } else {
            set_flash_message('error', 'comment', 'create_failed');
            redirect_with_errors('thread.php?id=' . $thread_id, 'comment', $errors, $_POST);
        }
    }

    if (isset($_POST['delete_comment']) && isset($_POST['delete_comment_id'])) {
        $comment_id = (int)$_POST['delete_comment_id'];

        if (delete_comment($pdo, $comment_id, (int)$_SESSION['user_id'])) {
            set_flash_message('success', 'comment', 'deleted');
        } else {
            set_flash_message('error', 'comment', 'delete_failed');
        }
        redirect('thread.php?id=' . $thread_id);
    }

    if (!is_thread_owner($thread['user_id'], (int)$_SESSION['user_id'])) {
        set_flash_message('error', 'thread', 'not_owner');
        redirect('index.php');
    }

    if (delete_thread($pdo, $thread_id, (int)$_SESSION['user_id'])) {
        set_flash_message('success', 'thread', 'deleted');
        redirect('index.php');
    } else {
        set_flash_message('error', 'thread', 'delete_failed');
        redirect_with_errors('thread.php?id=' . $thread_id, 'thread', $errors, $_POST);
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($thread['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../src/partials/header.php'; ?>
    <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

    <div class="container">
        <a href="/index.php" class="back-link">← 戻る</a>
        
        <div class="thread">
            <div class="thread-header">
                <span class="thread-date">投稿日時: <?= htmlspecialchars((new DateTime($thread['created_at']))->format('Y/m/d H:i')) ?></span>
                <?php if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $thread['user_id']): ?>
                    <a href="edit.php?id=<?= $thread['id'] ?>" class="edit-link">編集</a>
                    <form action="thread.php?id=<?= $thread['id'] ?>" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($thread['id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                <?php endif; ?>
            </div>
            
            <div class="thread-title"><?= htmlspecialchars($thread['title']) ?></div>
            
            <?php if ($thread['updated_at'] !== $thread['created_at']): ?>
                <div class="thread-meta">
                    最終更新: <?= htmlspecialchars((new DateTime($thread['updated_at']))->format('Y/m/d H:i')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="comments">
            <h2>コメント一覧</h2>
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <span class="comment-author">
                            <?= htmlspecialchars($comment['last_name'] . ' ' . $comment['first_name']) ?>
                        </span>
                        <span class="comment-date">
                            <?= htmlspecialchars((new DateTime($comment['created_at']))->format('Y/m/d H:i')) ?>
                        </span>
                        <div class="comment-content">
                            <?= nl2br(htmlspecialchars($comment['content'])) ?>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$comment['user_id']): ?>
                            <form action="thread.php?id=<?= $thread['id'] ?>" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($thread['id']) ?>">
                                <input type="hidden" name="delete_comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
                                <button type="submit" name="delete_comment" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>コメントはまだありません。</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="comment-form">
                <h2>コメントを投稿する</h2>
                <form action="thread.php?id=<?= $thread['id'] ?>" method="post">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($thread['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
                    <textarea name="content" rows="3" cols="50" ><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                    <?php $name = 'content'; include __DIR__ . '/../src/partials/error_message.php'; ?>
                    <br>
                    <button type="submit" name="comment">投稿</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/validation.php';

function redirect_with_errors($location, $errors, $old_params) {
  $_SESSION['post_errors'] = $errors;
  $_SESSION['post_old'] = $old_params;
  header("Location: $location");
  exit;
}

$post_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$post_id) {
  header('Location: /index.php');
  exit;
}

$old_params = [
  'title' => $_POST['title'] ?? '',
  'content' => $_POST['content'] ?? '',
];

$errors = validate_post($_POST);

if ($errors) {
  redirect_with_errors('/edit.php?id=' . $post_id, $errors, $old_params);
}

try {
  $pdo = getPDO();
  
  $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?');
  $stmt->execute([
    $_POST['title'],
    $_POST['content'],
    $post_id,
    $_SESSION['user_id']
  ]);

} catch (PDOException $e) {
  error_log('Post creation error: ' . $e->getMessage());
  $errors['form'] = '投稿の更新に失敗しました。';
  redirect_with_errors('/edit.php?id=' . $post_id, $errors, $old_params);
}

header('Location: /post.php?id=' . $post_id);
exit;

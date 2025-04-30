<?php
require_once __DIR__ . '/../config/database.php';

function redirect_with_errors($location, $errors, $old_params) {
  $_SESSION['post_errors'] = $errors;
  $_SESSION['post_old'] = $old_params;
  header("Location: $location");
  exit;
}

$old_params = [
  'title' => $_POST['title'] ?? '',
  'content' => $_POST['content'] ?? '',
];

$errors = [];

if (empty($_POST['title'])) {
  $errors['title'] = 'タイトルを入力してください。';
} elseif (mb_strlen($_POST['title']) > 255) {
  $errors['title'] = 'タイトルは255文字以内で入力してください。';
}

if (empty($_POST['content'])) {
  $errors['content'] = '内容を入力してください。';
}

if ($errors) {
  redirect_with_errors('/index.php', $errors, $old_params);
}

try {
  $pdo = getPDO();
  
  $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES (?, ?, ?, NOW() , NOW())');
  $stmt->execute([
    $_SESSION['user_id'],
    $_POST['title'],
    $_POST['content']
  ]);

} catch (PDOException $e) {
  error_log('Post creation error: ' . $e->getMessage());
  $errors['form'] = '投稿の作成に失敗しました。';
  redirect_with_errors('/index.php', $errors, $old_params);
}

header('Location: /index.php');
exit;

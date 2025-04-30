<?php

require_once __DIR__ . '/../config/database.php';

$post_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$post_id) {
  header('Location: /index.php');
  exit;
}

try {
  $pdo = getPDO();
  
  $pdo->beginTransaction();
  
  $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE id = ?');
  $stmt->execute([$post_id]);
  $post = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
      $pdo->rollBack();
      header('Location: /index.php');
      exit;
  }
  
  $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
  $stmt->execute([$post_id, $_SESSION['user_id']]);
  
  $pdo->commit();
  
  header('Location: /index.php');
  exit;

} catch (PDOException $e) {
  $pdo->rollBack();
  error_log('Post deletion error: ' . $e->getMessage());
  header('Location: /index.php');
  exit;
}

<?php

require_once __DIR__ . '/common.php';

function validate_comment($content) {
  if ($error = validate_min_length($content, 'コメント', 1)) {
    return $error;
  }

  if ($error = validate_max_length($content, 'コメント', 500)) {
    return $error;
  }

  return null;
}

function validate_comment_exists($pdo, $thread_id, $comment_id) {
  $sql = "SELECT COUNT(*) FROM comments WHERE id = ? AND thread_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$comment_id, $thread_id]);
  return $stmt->fetchColumn() > 0;
}
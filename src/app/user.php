<?php

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../config/database.php';

function fetch_password_by_id($pdo, $user_id) {
  $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
  $stmt->execute(['id' => $user_id]);
  $row = $stmt->fetch();
  return $row ? $row['password'] : null;
}

function delete_user($pdo, $user_id) {
  require_login();

  try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('DELETE FROM posts WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);

    $pdo->commit();

  } catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
  }
}
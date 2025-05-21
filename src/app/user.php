<?php

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../config/database.php';

function fetch_password_by_id($pdo, $user_id) {
  $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
  $stmt->execute(['id' => $user_id]);
  $row = $stmt->fetch();
  return $row ? $row['password'] : null;
}

function fetch_users($pdo, $order = 'desc') {
  $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';
  $sql = <<<SQL
    SELECT 
      users.*
    FROM 
      users
    ORDER BY
      users.created_at $order
  SQL;

  $stmt = $pdo->query($sql);
  return $stmt->fetchAll();
}

function fetch_user_by_id($pdo, $user_id) {
  $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
  $stmt->execute(['id' => $user_id]);
  return $stmt->fetch();
}

function delete_user($pdo, $user_id) {
  require_login();

  if ($_SESSION['user_id'] != $user_id) {
    throw new Exception(MESSAGES['error']['auth']['unauthorized']);
  }

  try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('DELETE FROM threads WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);

    $pdo->commit();

  } catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
  }
}
<?php

function is_email_taken($pdo, $email) {
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetchColumn() > 0;
}

function fetch_user_by_email($pdo, $email) {
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetch();
}

function fetch_password_by_id($pdo, $user_id) {
  $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
  $stmt->execute(['id' => $user_id]);
  $row = $stmt->fetch();
  return $row ? $row['password'] : null;
}

function fetch_user_by_id($pdo, $user_id) {
  $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
  $stmt->execute(['id' => $user_id]);
  return $stmt->fetch();
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


function create_user($pdo, $email, $password, $last_name, $first_name, $gender, $prefecture, $address) {
  try {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
      'INSERT INTO users (email, password, last_name, first_name, gender, prefecture, address) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$email, $hash, $last_name, $first_name, $gender, $prefecture, $address]);
    return $pdo->lastInsertId();
  } catch (PDOException $e) {
    error_log('User insert error: ' . $e->getMessage());
    return null;
  }
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
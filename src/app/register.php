<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../validations/user.php';
require_once __DIR__ . '/../lib/util.php';
require_once __DIR__ . '/../config/message.php';
require_once __DIR__ . '/../lib/flash_message.php';
require_once __DIR__ . '/../lib/auth.php';

function is_email_taken($pdo, $email) {
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetchColumn() > 0;
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

function register($data) {
  $old = [
    'last_name' => $data['last_name'] ?? '',
    'first_name' => $data['first_name'] ?? '',
    'gender' => $data['gender'] ?? '',
    'prefecture' => $data['prefecture'] ?? '',
    'address' => $data['address'] ?? '',
    'email' => $data['email'] ?? '',
  ];

  $pdo = getPDO();

  $errors = validate_register($pdo, $data);
  if ($errors) {
    redirect_with_errors('/register.php', 'register', $errors, $old);
  }

  $user_id = create_user($pdo, $data['email'], $data['password'], $data['last_name'], $data['first_name'], $data['gender'], $data['prefecture'], $data['address']);

  if ($user_id) {
    set_login_session($user_id);
    set_flash_message('success', 'auth', 'register');
    redirect('index.php');
  } else {
    set_flash_message('error', 'auth', 'register_failed');
    redirect_with_errors('/register.php', 'register', $errors, $old);
  }
}

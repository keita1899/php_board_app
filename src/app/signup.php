<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/validation.php';
require_once __DIR__ . '/../lib/util.php';
require_once __DIR__ . '/../config/message.php';
require_once __DIR__ . '/../lib/flash_message.php';
require_once __DIR__ . '/../lib/auth.php';

function is_email_taken($pdo, $email) {
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetchColumn() > 0;
}

function validate_signup($pdo, $data) {
  $errors = [];
  
  if ($error = validate_email($data['email'])) {
    $errors['email'] = $error;
  }
  if ($error = validate_password($data['password'])) {
    $errors['password'] = $error;
  }
  if ($error = validate_password_confirmation($data['password'], $data['password_confirm'])) {
    $errors['password_confirm'] = $error;
  }

  if (empty($errors['email'])) {
    if (is_email_taken($pdo, $data['email'])) {
      $errors['email'] = MESSAGES['error']['user']['email_taken'];
    }
  }

  return $errors;
}

function create_user($pdo, $email, $password) {
  try {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
      'INSERT INTO users (email, password) VALUES (?, ?)'
    );
    $stmt->execute([$email, $hash]);
    return $pdo->lastInsertId();
  } catch (PDOException $e) {
    error_log('User insert error: ' . $e->getMessage());
    return null;
  }
}

function signup($data) {
  $old = [
    'email' => $data['email'] ?? '',
  ];

  $pdo = getPDO();

  $errors = validate_signup($pdo, $data);
  if ($errors) {
    redirect_with_errors('/signup.php', 'signup', $errors, $old);
  }

  $user_id = create_user($pdo, $data['email'], $data['password']);

  if ($user_id) {
    set_login_session($user_id);
    set_flash_message('success', 'auth', 'signup');
    redirect('index.php');
  } else {
    set_flash_message('error', 'auth', 'signup_failed');
    redirect_with_errors('/signup.php', 'signup', $errors, $old);
  }
}

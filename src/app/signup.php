<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/validation.php';
require_once __DIR__ . '/../lib/util.php';
require_once __DIR__ . '/../config/message.php';
require_once __DIR__ . '/../lib/flash_message.php';

function is_username_taken($pdo, $username) {
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
  $stmt->execute([$username]);
  return $stmt->fetchColumn() > 0;
}

function is_email_taken($pdo, $email) {
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetchColumn() > 0;
}

function validate_signup($pdo, $data) {
  $errors = [];
  
  if ($error = validate_username($data['username'])) {
    $errors['username'] = $error;
  }
  if ($error = validate_email($data['email'])) {
    $errors['email'] = $error;
  }
  if ($error = validate_password($data['password'])) {
    $errors['password'] = $error;
  }
  if ($error = validate_password_confirmation($data['password'], $data['password_confirm'])) {
    $errors['password_confirm'] = $error;
  }

  if (empty($errors['username'])) {
    if (is_username_taken($pdo, $data['username'])) {
      $errors['username'] = MESSAGES['error']['user']['username_taken'];
    }
  }
  
  if (empty($errors['email'])) {
    if (is_email_taken($pdo, $data['email'])) {
      $errors['email'] = MESSAGES['error']['user']['email_taken'];
    }
  }

  return $errors;
}

function create_user($pdo, $username, $email, $password) {
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
  return $stmt->execute([$username, $email, $hash]);
}

function signup($data) {
  $old = [
    'username' => $data['username'] ?? '',
    'email' => $data['email'] ?? '',
  ];

  $pdo = getPDO();

  $errors = validate_signup($pdo, $data);
  if ($errors) {
    redirect_with_errors('/signup.php', 'signup', $errors, $old);
  }

  if (create_user($pdo, $data['username'], $data['email'], $data['password'])) {
    set_flash_message('success', 'signup');
    header('Location: /index.php');
    exit;
  } else {
    $errors['form'] = MESSAGES['error']['auth']['signup_failed'];
    redirect_with_errors('/signup.php', 'signup', $errors, $old);
  }
}

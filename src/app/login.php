<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/message.php';
require_once __DIR__ . '/../validations/user.php';
require_once __DIR__ . '/../lib/util.php';
require_once __DIR__ . '/../lib/flash_message.php';
require_once __DIR__ . '/../lib/auth.php';

function fetch_user_by_email($pdo, $email) {
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetch();
}

function login($form_data) {

  $old = [
    'email' => $form_data['email'] ?? '',
  ];
  
  $errors = validate_login($form_data);
  if ($errors) {
    redirect_with_errors('/login.php', 'login', $errors, $old);
  }

  $pdo = getPDO();

  $user = fetch_user_by_email($pdo, $form_data['email']);
  if (!$user) {
    set_flash_message('error', 'auth', 'login_failed');
    redirect_with_errors('/login.php', 'login', $errors, $old);
  }

  if (!password_verify($form_data['password'], $user['password'])) {
    set_flash_message('error', 'auth', 'login_failed');
    redirect_with_errors('/login.php', 'login', $errors, $old);
  }

  set_login_session($user['id']);

  set_flash_message('success', 'auth', 'login');

  redirect('index.php');
}

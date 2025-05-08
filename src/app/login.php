<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/validation.php';

function redirect_with_errors($location, $errors, $old_params) {
  $_SESSION['login_errors'] = $errors;
  $_SESSION['login_old'] = $old_params;
  header("Location: $location");
  exit;
}

function validate_login($data) {
  $errors = [];
  
  if ($error = validate_email($data['email'])) {
    $errors['email'] = $error;
  }
  if ($error = validate_password($data['password'])) {
    $errors['password'] = $error;
  }
  
  return $errors;
}

function fetch_user_by_email($pdo, $email) {
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([$email]);
  return $stmt->fetch();
}

function set_user_session($user) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
}

function login($data) {

  $old_params = [
    'email' => $_POST['email'] ?? '',
  ];
  
  $errors = validate_login($data);
  if ($errors) {
    redirect_with_errors('/login.php', $errors, $old_params);
  }

  $pdo = getPDO();

  $user = fetch_user_by_email($pdo, $data['email']);
  if (!$user) {
    $errors['form'] = 'メールアドレスまたはパスワードが間違っています。';
    redirect_with_errors('/login.php', $errors, $old_params);
  }

  if (!password_verify($data['password'], $user['password'])) {
    $errors['form'] = 'メールアドレスまたはパスワードが間違っています。';
    redirect_with_errors('/login.php', $errors, $old_params);
  }

  session_regenerate_id(true);
  set_user_session($user);

  header('Location: /index.php');
  exit;
}

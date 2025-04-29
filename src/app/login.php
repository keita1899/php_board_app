<?php
require_once __DIR__ . '/../config/database.php';

function redirect_with_errors($location, $errors, $old_params) {
  $_SESSION['login_errors'] = $errors;
  $_SESSION['login_old'] = $old_params;
  header("Location: $location");
  exit;
}

$old_params = [
  'email' => $_POST['email'] ?? '',
];

$errors = [];

if (empty($_POST['email'])) {
  $errors['email'] = 'メールアドレスを入力してください。';
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  $errors['email'] = '正しいメールアドレスを入力してください。';
}
if (empty($_POST['password'])) {
  $errors['password'] = 'パスワードを入力してください。';
} elseif (strlen($_POST['password']) < 8) {
  $errors['password'] = 'パスワードは8文字以上で入力してください。';
}

if ($errors) {
  redirect_with_errors('/login.php', $errors, $old_params);
}

$pdo = getPDO();

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$_POST['email']]);
$user = $stmt->fetch();

if (!$user) {
  $errors['form'] = 'メールアドレスまたはパスワードが間違っています。';
  redirect_with_errors('/login.php', $errors, $old_params);
}

if (!password_verify($_POST['password'], $user['password'])) {
  $errors['form'] = 'メールアドレスまたはパスワードが間違っています。';
  redirect_with_errors('/login.php', $errors, $old_params);
}

session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

header('Location: /index.php');
exit;
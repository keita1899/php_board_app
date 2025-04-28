<?php
require_once __DIR__ . '/../config/database.php';

function redirect_with_errors($location, $errors, $old_params) {
  $_SESSION['signup_errors'] = $errors;
  $_SESSION['signup_old'] = $old_params;
  header("Location: $location");
  exit;
}

$old_params = [
  'username' => $_POST['username'] ?? '',
  'email' => $_POST['email'] ?? '',
];

$errors = [];

if (empty($_POST['username'])) {
  $errors['username'] = 'ユーザー名を入力してください。';
}
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
if ($_POST['password'] !== ($_POST['password_confirm'] ?? '')) {
  $errors['password_confirm'] = 'パスワードが一致しません。';
}

if ($errors) {
  redirect_with_errors('/signup.php', $errors, $old_params);
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
$stmt->execute([$_POST['username']]);
if ($stmt->fetchColumn() > 0) {
  $errors['username'] = 'このユーザー名は既に使われています。';
  redirect_with_errors('/signup.php', $errors, $old_params);
}

$stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
$stmt->execute([$_POST['email']]);
if ($stmt->fetchColumn() > 0) {
  $errors['email'] = 'このメールアドレスは既に使われています。';
  redirect_with_errors('/signup.php', $errors, $old_params);
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
$stmt->execute([$_POST['username'], $_POST['email'], $hash]);

header('Location: /login.php');
exit;

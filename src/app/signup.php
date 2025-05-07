<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/validation.php';

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

if ($error = validate_username($_POST['username'])) {
  $errors['username'] = $error;
}
if ($error = validate_email($_POST['email'])) {
  $errors['email'] = $error;
}
if ($error = validate_password($_POST['password'])) {
  $errors['password'] = $error;
}
if ($error = validate_password_confirmation($_POST['password'], $_POST['password_confirm'])) {
  $errors['password_confirm'] = $error;
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

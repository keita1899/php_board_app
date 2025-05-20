<?php

require_once __DIR__ . '/../validations/admin.php';
require_once __DIR__ . '/../lib/util.php';
require_once __DIR__ . '/../lib/flash_message.php';
require_once __DIR__ . '/../lib/auth.php';

function fetch_admin_by_username($pdo, $username) {
  $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
  $stmt->execute([$username]);
  return $stmt->fetch();
}

function admin_login($pdo, $form_data) {
  $old = [
    'username' => $form_data['username'] ?? '',
  ];

  $errors = validate_login($form_data);
  if ($errors) {
    redirect_with_errors('/admin_login.php', 'admin_login', $errors, $old);
  }

  $admin = fetch_admin_by_username($pdo, $form_data['username']);
  if (!$admin) {
    set_flash_message('error', 'auth', 'login_failed');
    redirect_with_errors('/admin_login.php', 'admin_login', $errors, $old);
  }

  if (!password_verify($form_data['password'], $admin['password'])) {
    set_flash_message('error', 'auth', 'login_failed');
    redirect_with_errors('/admin_login.php', 'admin_login', $errors, $old);
  }

  set_admin_session($admin['id']);
  redirect('/admin.php');
}
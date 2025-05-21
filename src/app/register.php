<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../validations/user.php';
require_once __DIR__ . '/../lib/util.php';
require_once __DIR__ . '/../config/message.php';
require_once __DIR__ . '/../lib/flash_message.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../models/user.php';

function register_input($pdo, $form_data) {
  $old = [
    'last_name' => $form_data['last_name'] ?? '',
    'first_name' => $form_data['first_name'] ?? '',
    'gender' => $form_data['gender'] ?? '',
    'prefecture' => $form_data['prefecture'] ?? '',
    'address' => $form_data['address'] ?? '',
    'email' => $form_data['email'] ?? '',
  ];

  $errors = validate_register($pdo, $form_data);
  
  if ($errors) {
    redirect_with_errors('/register.php', 'register', $errors, $old);
  }

  $_SESSION['register_data'] = [
    'last_name'        => $form_data['last_name'],
    'first_name'       => $form_data['first_name'],
    'gender'           => $form_data['gender'],
    'prefecture'       => $form_data['prefecture'],
    'address'          => $form_data['address'],
    'email'            => $form_data['email'],
    'password'         => $form_data['password'],
  ];
  redirect('register_confirm.php');
}

function register_confirm() {
  if (!isset($_SESSION['register_data'])) {
    redirect('register.php');
  }

  return $_SESSION['register_data'];
}

function register_complete($pdo) {
  if (!isset($_SESSION['register_data'])) {
    redirect('register.php');
  }

  $form_data = $_SESSION['register_data'];

  $user_id = create_user(
    $pdo,
    $form_data['email'],
    $form_data['password'],
    $form_data['last_name'],
    $form_data['first_name'],
    $form_data['gender'],
    $form_data['prefecture'],
    $form_data['address']
  );

  if ($user_id) {
    set_login_session($user_id);
    unset($_SESSION['register_data']);
  } else {
    set_flash_message('error', 'auth', 'register_failed');
    redirect('register.php');
  }
}
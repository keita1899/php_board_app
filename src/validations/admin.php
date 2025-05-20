<?php

require_once __DIR__ . '/common.php';

function validate_username($username) {
  if ($error = validate_required($username, 'ユーザー名')) {
    return $error;
  }

  if ($error = validate_max_length($username, 'ユーザー名', 255)) {
    return $error;
  }

  return null;
}

function validate_password($password) {
    if ($error = validate_required($password, 'パスワード')) {
        return $error;
    }

    if (strlen($password) < 8) {
        return MESSAGES['error']['password']['too_short'];
    }
    return null;
}

function validate_login($form_data) {
  $errors = [];

  if ($error = validate_username($form_data['username'])) {
    $errors['username'] = $error;
  }

  if ($error = validate_password($form_data['password'])) {
    $errors['password'] = $error;
  }

  return $errors;
}

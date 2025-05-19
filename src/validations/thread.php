<?php

require_once __DIR__ . '/common.php';

function validate_title($title) {
  if ($error = validate_required($title, 'タイトル')) {
      return $error;
  }

  if (mb_strlen($title) > 255) {
      return MESSAGES['error']['thread']['title_max_length'];
  }

  return null;
}

function validate_thread($thread) {
  $errors = [];

  if ($error = validate_title($thread['title'])) {
      $errors['title'] = $error;
  }

  return $errors;
}

function validate_keyword($keyword) {
  if ($error = validate_max_length($keyword, 'キーワード', 255)) {
      return $error;
  }

  return null;
}

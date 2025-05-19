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

function validate_content($content) {
  if ($error = validate_required($content, '内容')) {
      return $error;
  }

  if (mb_strlen($content) > 1000) {
      return MESSAGES['error']['thread']['content_max_length'];
  }

  return null;
}

function validate_thread($thread) {
  $errors = [];

  if ($error = validate_title($thread['title'])) {
      $errors['title'] = $error;
  }

  if ($error = validate_content($thread['content'])) {
      $errors['content'] = $error;
  }

  return $errors;
}
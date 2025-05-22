<?php

require_once __DIR__ . '/common.php';

function validate_comment($content) {
  if ($error = validate_min_length($content, 'コメント', 1)) {
    return $error;
  }

  if ($error = validate_max_length($content, 'コメント', 500)) {
    return $error;
  }

  return null;
}
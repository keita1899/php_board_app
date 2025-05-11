<?php

require_once __DIR__ . '/../config/message.php';

function set_flash_message($type, $key, $message = null) {
  if ($message === null) {
    $message = MESSAGES[$type][$key] ?? '';
  }
  $_SESSION['flash_messages'][$type][] = $message;
}

function get_flash_messages() {
  $messages = $_SESSION['flash_messages'] ?? [];
  unset($_SESSION['flash_messages']);
  return $messages;
}

function has_flash_messages() {
  return !empty($_SESSION['flash_messages']);
}
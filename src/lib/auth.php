<?php

require_once __DIR__ . '/util.php';

function is_logged_in() {
  return isset($_SESSION['user_id']);
}

function require_login() {
  if (!is_logged_in()) {
    set_flash_message('error', 'auth', 'require_login');
    redirect('login.php');
  }
}

function logout() {
  $_SESSION = array();

  if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
  }

  session_destroy();
}
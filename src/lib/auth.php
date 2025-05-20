<?php

require_once __DIR__ . '/util.php';
require_once __DIR__ . '/flash_message.php';

function is_logged_in() {
  return isset($_SESSION['user_id']);
}

function require_login() {
  if (!is_logged_in()) {
    set_flash_message('error', 'auth', 'require_login');
    redirect('login.php');
  }
}

function set_login_session($user_id) {
  session_regenerate_id(true);
  $_SESSION['user_id'] = $user_id;
}

function logout() {
  $_SESSION = array();

  if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
  }

  session_destroy();
}

function is_admin_logged_in() {
  return isset($_SESSION['admin']);
}

function require_admin_login() {
  if (!is_admin_logged_in()) {
    set_flash_message('error', 'auth', 'require_admin_login');
    redirect('admin_login.php');
  }
}

function set_admin_session($admin_id) {
  session_regenerate_id(true);
  $_SESSION['admin'] = $admin_id;
}

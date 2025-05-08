<?php

function require_login() {
  if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
  }
}

function logout() {
  $_SESSION = array();

  if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
  }

  session_destroy();
}
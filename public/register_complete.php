<?php
session_start();
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/register.php';

if (!isset($_SESSION['register_data'])) {
  redirect('register.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'security', 'invalid_csrf');
    redirect('register.php');
  }

  if (isset($_POST['back'])) {
    redirect('register.php');
  }

  if (isset($_POST['submit'])) {
    register_complete();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録完了</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <h1>会員登録完了</h1>
  <p>会員登録が完了しました。</p>
</body>
</html>

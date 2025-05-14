<?php
session_start();
require_once __DIR__ . '/../src/lib/csrf.php';
require_once __DIR__ . '/../src/app/register.php';

$form_data = register_confirm();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員情報登録確認画面</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../src/partials/header.php'; ?>
  <?php include __DIR__ . '/../src/partials/flash_message.php'; ?>

  <h1>会員情報登録確認画面</h1>

  <div class="">
    <div class="">
      <label>氏名</label>
      <p><?= htmlspecialchars($form_data['last_name'] . ' ' . $form_data['first_name']) ?></p>
    </div>
  
    <div class="">
      <label>性別</label>
      <p><?= $form_data['gender'] === 'male' ? '男性' : '女性' ?></p>
    </div>
  
    <div class="">
      <label>住所</label>
      <p><?= htmlspecialchars($form_data['prefecture'] . $form_data['address']) ?></p>
    </div>
  
    <div class="">
      <label>パスワード</label>
      <p>セキュリティのため非表示</p>
    </div>
  
    <div class="">
      <label>メールアドレス</label>
      <p><?= htmlspecialchars($form_data['email']) ?></p>
    </div>
  </div>

  <form action="register_complete.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
    <button type="submit" name="submit">登録完了</button>
    <button type="submit" name="back">前に戻る</button>
  </form>
</body>
</html>
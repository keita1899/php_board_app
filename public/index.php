<?php
session_start();

echo "Hello, World!";

if (!isset($_SESSION['user_id'])) {
  echo "ログインしてください";
} else {
  echo "ログインしました";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php if (isset($_SESSION['user_id'])): ?>
    <form action="/logout.php" method="post">
      <input type="submit" value="ログアウト">
    </form>
  <?php endif; ?>
</body>
</html>


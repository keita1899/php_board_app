<?php
require_once __DIR__ . '/../lib/auth.php';
?>

<header>
  <div class="header-content">
    <h1>掲示板アプリ</h1>
    <nav class="header-nav">
      <?php if (is_logged_in()): ?>
        <form action="/logout.php" method="post" class="nav-form">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
          <input type="submit" value="ログアウト" class="nav-button">
        </form>
      <?php else: ?>
        <a href="/register.php" class="nav-link">新規登録</a>
        <a href="/login.php" class="nav-link">ログイン</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
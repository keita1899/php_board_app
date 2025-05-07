<header>
  <h1>掲示板アプリ</h1>
  <?php if (isset($_SESSION['user_id'])): ?>
    <form action="/logout.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
      <input type="submit" value="ログアウト">
    </form>
  <?php endif; ?>
</header>
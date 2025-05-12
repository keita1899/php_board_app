<?php
require_once __DIR__ . '/../lib/flash_message.php';

if (has_flash_messages()):
  foreach (get_flash_messages() as $type => $messages): ?>
    <div class="alert alert-<?= $type ?>">
      <?php foreach ($messages as $message): ?>
        <p><?= htmlspecialchars($message) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
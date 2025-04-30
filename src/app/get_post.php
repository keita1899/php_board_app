<?php
require_once __DIR__ . '/../config/database.php';

function get_post($post_id) {
  try {
    $pdo = getPDO();
    
    $sql = <<<SQL
      SELECT 
        posts.*,
        users.username
      FROM 
        posts
        JOIN users ON posts.user_id = users.id
      WHERE
        posts.id = ?
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$post_id]);
    return $stmt->fetch();

  } catch (PDOException $e) {
    error_log('Post fetch error: ' . $e->getMessage());
    return null;
  }
}

<?php
require_once __DIR__ . '/../config/database.php';

function get_posts($pdo) {
  try {
    $sql = <<<SQL
      SELECT 
        posts.*,
        users.username
      FROM 
        posts
        JOIN users ON posts.user_id = users.id
      ORDER BY 
        posts.created_at DESC
    SQL;

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();

  } catch (PDOException $e) {
    error_log('Post fetch error: ' . $e->getMessage());
    return [];
  }
}

function get_post($pdo, $post_id) {
  try {
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


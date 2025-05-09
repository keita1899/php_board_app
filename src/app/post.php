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

function create_post($pdo, $user_id, $title, $content) {
  try {
    $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
    return $stmt->execute([$user_id, $title, $content]);
  } catch (PDOException $e) {
    error_log('Post creation error: ' . $e->getMessage());
    return false;
  }
}

function update_post($pdo, $post_id, $user_id, $title, $content) {
  try {
    $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?');
    return $stmt->execute([$title, $content, $post_id, $user_id]);
  } catch (PDOException $e) {
    error_log('Post update error: ' . $e->getMessage());
    return false;
  }
}

function delete_post($pdo, $post_id, $user_id) {
  try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE id = ?');
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post || $post['user_id'] !== $user_id) {
        $pdo->rollBack();
        return false;
    }
    
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    $stmt->execute([$post_id, $user_id]);
    
    $pdo->commit();
    return true;

  } catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Post deletion error: ' . $e->getMessage());
    return false;
  }
}
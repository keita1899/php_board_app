<?php
require_once __DIR__ . '/../config/database.php';

function get_threads($pdo) {
  try {
    $sql = <<<SQL
      SELECT 
        threads.*
      FROM 
        threads
      ORDER BY 
        threads.created_at DESC
    SQL;

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();

  } catch (PDOException $e) {
    error_log('Post fetch error: ' . $e->getMessage());
    return [];
  }
}

function get_thread($pdo, $thread_id) {
  try {
    $sql = <<<SQL
      SELECT 
        threads.*
      FROM 
        threads
      WHERE
        threads.id = ?
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$thread_id]);
    return $stmt->fetch();

  } catch (PDOException $e) {
    error_log('Post fetch error: ' . $e->getMessage());
    return null;
  }
}

function create_thread($pdo, $user_id, $title) {
  try {
    $stmt = $pdo->prepare('INSERT INTO threads (user_id, title, created_at, updated_at) VALUES (?, ?, NOW(), NOW())');
    return $stmt->execute([$user_id, $title]);
  } catch (PDOException $e) {
    error_log('Post creation error: ' . $e->getMessage());
    return false;
  }
}

function update_thread($pdo, $thread_id, $user_id, $title) {
  try {
    $stmt = $pdo->prepare('UPDATE threads SET title = ?, updated_at = NOW() WHERE id = ? AND user_id = ?');
    return $stmt->execute([$title, $thread_id, $user_id]);
  } catch (PDOException $e) {
    error_log('Post update error: ' . $e->getMessage());
    return false;
  }
}

function delete_thread($pdo, $thread_id, $user_id) {
  try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare('SELECT user_id FROM threads WHERE id = ?');
    $stmt->execute([$thread_id]);
    $thread = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$thread || $thread['user_id'] !== $user_id) {
        $pdo->rollBack();
        return false;
    }
    
    $stmt = $pdo->prepare('DELETE FROM threads WHERE id = ? AND user_id = ?');
    $stmt->execute([$thread_id, $user_id]);
    
    $pdo->commit();
    return true;

  } catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Post deletion error: ' . $e->getMessage());
    return false;
  }
}

function is_thread_owner($owner_id, $current_user_id) {
  return $owner_id === $current_user_id;
}

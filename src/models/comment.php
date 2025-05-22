<?php


function create_comment($pdo, $thread_id, $user_id, $content) {
    $sql = "INSERT INTO comments (thread_id, user_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$thread_id, $user_id, $content]);
}

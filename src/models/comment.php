<?php

function fetch_comments_by_thread_id($pdo, $thread_id) {
    $sql = <<<SQL
        SELECT comments.*, users.first_name, users.last_name
        FROM comments
        JOIN users ON comments.user_id = users.id
        WHERE comments.thread_id = ?
        ORDER BY comments.created_at ASC
    SQL;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$thread_id]);
    return $stmt->fetchAll();
}

function create_comment($pdo, $thread_id, $user_id, $content) {
    $sql = "INSERT INTO comments (thread_id, user_id, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$thread_id, $user_id, $content]);
}

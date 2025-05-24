<?php

function was_recently_viewed($pdo, $user_id, $thread_id, $interval_seconds = 300) {
    $stmt = $pdo->prepare('SELECT viewed_at FROM thread_views WHERE user_id = ? AND thread_id = ?');
    $stmt->execute([$user_id, $thread_id]);
    $last_view = $stmt->fetchColumn();
    if ($last_view) {
        $last_time = strtotime($last_view);
        $now = time();
        if ($now - $last_time < $interval_seconds) {
            return true;
        }
    }
    return false;
}

function save_thread_view($pdo, $user_id, $thread_id) {
    if (was_recently_viewed($pdo, $user_id, $thread_id, 300)) {
        return;
    }
    $now_str = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare('INSERT INTO thread_views (user_id, thread_id, viewed_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE viewed_at = VALUES(viewed_at)');
    $stmt->execute([$user_id, $thread_id, $now_str]);
}

function fetch_thread_views($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT t.id, t.title, v.viewed_at FROM thread_views v JOIN threads t ON v.thread_id = t.id WHERE v.user_id = ? ORDER BY v.viewed_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
} 
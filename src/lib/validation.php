<?php

function validate_post($post) {
    $errors = [];

    if (empty($post['title'])) {
        $errors['title'] = 'タイトルを入力してください。';
    } elseif (mb_strlen($post['title']) > 255) {
        $errors['title'] = 'タイトルは255文字以内で入力してください。';
    }

    if (empty($post['content'])) {
        $errors['content'] = '内容を入力してください。';
    }

    return $errors;
}

function validate_email($email) {
    if (empty($email)) {
        return 'メールアドレスを入力してください。';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '正しいメールアドレスを入力してください。';
    }
    return null;
}

function validate_password($password) {
    if (empty($password)) {
        return 'パスワードを入力してください。';
    }
    if (strlen($password) < 8) {
        return 'パスワードは8文字以上で入力してください。';
    }
    return null;
}

function validate_username($username) {
    if (empty($username)) {
        return 'ユーザー名を入力してください。';
    }
    return null;
}

function validate_password_confirmation($password, $password_confirm) {
    if ($password !== $password_confirm) {
        return 'パスワードが一致しません。';
    }
    return null;
}
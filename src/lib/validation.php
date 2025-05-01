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

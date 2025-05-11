<?php

function validate_post($post) {
    $errors = [];

    if (empty($post['title'])) {
        $errors['title'] = MESSAGES['error']['post']['title_required'];
    } elseif (mb_strlen($post['title']) > 255) {
        $errors['title'] = MESSAGES['error']['post']['title_max_length'];
    }

    if (empty($post['content'])) {
        $errors['content'] = MESSAGES['error']['post']['content_required'];
    } elseif (mb_strlen($post['content']) > 1000) {
        $errors['content'] = MESSAGES['error']['post']['content_max_length'];
    }

    return $errors;
}

function validate_email($email) {
    if (empty($email)) {
        return MESSAGES['error']['user']['email_required'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return MESSAGES['error']['user']['email_invalid'];
    }
    return null;
}

function validate_password($password) {
    if (empty($password)) {
        return MESSAGES['error']['password']['required'];
    }
    if (strlen($password) < 8) {
        return MESSAGES['error']['password']['too_short'];
    }
    return null;
}

function validate_username($username) {
    if (empty($username)) {
        return MESSAGES['error']['user']['username_required'];
    }
    return null;
}

function validate_password_confirmation($password, $password_confirm) {
    if ($password !== $password_confirm) {
        return MESSAGES['error']['password']['mismatch'];
    }
    return null;
}
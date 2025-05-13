<?php

require_once __DIR__ . '/common.php';

function validate_email($email) {
    if ($error = validate_required($email, 'メールアドレス')) {
        return $error;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return MESSAGES['error']['user']['email_invalid'];
    }
    return null;
}

function validate_password($password) {
    if ($error = validate_required($password, 'パスワード')) {
        return $error;
    }

    if (strlen($password) < 8) {
        return MESSAGES['error']['password']['too_short'];
    }
    return null;
}

function validate_password_confirmation($password, $password_confirm) {
    if ($error = validate_required($password_confirm, 'パスワード確認')) {
        return $error;
    }

    if ($password !== $password_confirm) {
        return MESSAGES['error']['password']['mismatch'];
    }
    return null;
}

function validate_last_name($last_name) {
    if ($error = validate_required($last_name, '姓')) {
        return $error;
    }

    if (mb_strlen($last_name) > 255) {
        return MESSAGES['error']['user']['last_name_max_length'];
    }
    
    return null;
}

function validate_first_name($first_name) {
    if ($error = validate_required($first_name, '名')) {
        return $error;
    }

    if (mb_strlen($first_name) > 255) {
        return MESSAGES['error']['user']['first_name_max_length'];
    }

    return null;
}

function validate_gender($gender) {
    if ($error = validate_select($gender ?? '', '性別')) {
        return $error;
    }
    return null;
}

function validate_prefecture($prefecture) {
    if ($error = validate_select($prefecture ?? '', '都道府県')) {
        return $error;
    }
    return null;
}

function validate_address($address) {
    if ($error = validate_required($address, '住所')) {
        return $error;
    }

    if (mb_strlen($address) > 255) {
        return MESSAGES['error']['user']['address_max_length'];
    }

    return null;
}

function validate_register($pdo, $data) {
    $errors = [];

    if ($error = validate_last_name($data['last_name'])) {
        $errors['last_name'] = $error;
    }

    if ($error = validate_first_name($data['first_name'])) {
        $errors['first_name'] = $error;
    }

    if ($error = validate_gender($data['gender'] ?? '')) {
        $errors['gender'] = $error;
    }

    if ($error = validate_prefecture($data['prefecture'])) {
        $errors['prefecture'] = $error;
    }

    if ($error = validate_address($data['address'])) {
        $errors['address'] = $error;
    }
    
    if ($error = validate_password($data['password'])) {
        $errors['password'] = $error;
    }
    
    if ($error = validate_password_confirmation($data['password'], $data['password_confirm'])) {
        $errors['password_confirm'] = $error;
    }
    
    if ($error = validate_email($data['email'])) {
        $errors['email'] = $error;
    }
    
    if (empty($errors['email'])) {
        if (is_email_taken($pdo, $data['email'])) {
        $errors['email'] = MESSAGES['error']['user']['email_taken'];
        }
    }

    return $errors;
}

function validate_login($data) {
    $errors = [];
    
    if ($error = validate_email($data['email'])) {
        $errors['email'] = $error;
    }
    if ($error = validate_password($data['password'])) {
        $errors['password'] = $error;
    }
    
    return $errors;
}
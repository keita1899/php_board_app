<?php

function validate_required($value, $field_name) {
    if ($value === null || $value === '') {
        return $field_name . MESSAGES['error']['common']['required'];
    }
    return null;
}

function validate_select($value, $field_name) {
    if ($value === null || $value === '') {
        return $field_name . MESSAGES['error']['common']['select'];
    }
    return null;
}

function validate_min_length($value, $field_name, $min_length) {
    if (mb_strlen($value) < $min_length) {
        return $field_name . str_replace('{min_length}', $min_length, MESSAGES['error']['common']['min_length']);
    }
    return null;
}

function validate_max_length($value, $field_name, $max_length) {
    if (mb_strlen($value) > $max_length) {
        return $field_name . str_replace('{max_length}', $max_length, MESSAGES['error']['common']['max_length']);
    }
    return null;
}

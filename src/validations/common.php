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

function validate_max_length($value, $field_name, $max_length) {
    if (mb_strlen($value) > $max_length) {
        return $field_name . str_replace('{max_length}', $max_length, MESSAGES['error']['common']['max_length']);
    }
    return null;
}

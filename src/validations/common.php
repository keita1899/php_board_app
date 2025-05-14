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

<?php

function set_form_old($form_name, $old) {
    $_SESSION['forms'][$form_name]['old'] = $old;
}

function set_form_errors($form_name, $errors) {
    $_SESSION['forms'][$form_name]['errors'] = $errors;
}

function get_form_old($form_name) {
    return $_SESSION['forms'][$form_name]['old'] ?? [];
}

function get_form_errors($form_name) {
    return $_SESSION['forms'][$form_name]['errors'] ?? [];
}

function clear_form_old($form_name) {
    unset($_SESSION['forms'][$form_name]['old']);
}

function clear_form_errors($form_name) {
    unset($_SESSION['forms'][$form_name]['errors']);
}

function redirect($location) {
    header("Location: $location");
    exit;
}

function redirect_with_errors($location, $form_name, $errors, $old) {
    set_form_old($form_name, $old);
    set_form_errors($form_name, $errors);
    redirect($location);
}
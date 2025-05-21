<?php

function gender_label($gender) {
    if ($gender === 'male') return '男';
    if ($gender === 'female') return '女';
    return '';
}

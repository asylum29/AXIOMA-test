<?php

defined('APP_INTERNAL') || die;

function required_param($name) {
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
    if (isset($_GET[$name])) {
        return $_GET[$name];
    }
    die("The argument with name \"$name\" is required.");
}

function optional_param($name, $default = null) {
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
    if (isset($_GET[$name])) {
        return $_GET[$name];
    }
    return $default;
}

function post_data_submitted() {
    return !empty($_POST);
}

function get_file($name, $optional = false) {
    if (isset($_FILES[$name])) {
        if ($_FILES[$name]['size'] == 0) {
            if ($optional) {
                return false;
            } else {
                die("The file with name \"$name\" is required.");
            }
        } else {
            return $_FILES[$name];
        }
    } else {
        if ($optional) {
            return false;
        } else {
            die("The file with name \"$name\" is required.");
        }
    }
}

function clean_param($param, $type)
{
    switch ($type)
    {
        case PARAM_RAW:
            return trim($param);

        case PARAM_NOTAGS:
            return trim(strip_tags($param));

        case PARAM_INT:
            return is_numeric($param) ? (int)$param : '';

        case PARAM_FLOAT:
            return is_numeric($param) ? (float)$param : '';

        case PARAM_DATE:
            $date = DateTime::createFromFormat('Y-m-d', $param);
            $errors = DateTime::getLastErrors();
            return $date && empty($errors['warning_count']) ? $date->format('Y-m-d') : '';

        default:
            die('unknownparamtype');
    }
}

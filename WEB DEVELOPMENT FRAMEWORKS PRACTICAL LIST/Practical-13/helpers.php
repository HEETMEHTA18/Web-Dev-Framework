<?php

function clean_str($s) {
    return trim($s);
}

function sanitize_output($s) {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function is_valid_username($u) {
    return preg_match('/^[A-Za-z0-9_]{3,30}$/', $u);
}

function is_strong_password($p) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $p);
}
?>

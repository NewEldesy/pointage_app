<?php
function isAdmin() {
    return $_SESSION['role'] === 'admin';
}

function isManager() {
    return $_SESSION['role'] === 'manager';
}

function isEmployee() {
    return $_SESSION['role'] === 'employee';
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>

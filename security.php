<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
function security_forbidden() { http_response_code(403); exit('Forbidden'); }
function security_csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}
function security_require_post() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('POST required'); }
    $sent = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'], $sent)) security_forbidden();
}
// Add the token to rendered POST forms, including forms printed by legacy templates.
ob_start(function ($html) {
    return preg_replace_callback('/<form\b[^>]*\bmethod\s*=\s*[\'"]?post[\'"]?[^>]*>/i', function ($m) {
        return $m[0] . security_csrf_field();
    }, $html);
});
function security_require_login() {
    if (empty($_SESSION['k_username'])) security_forbidden();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') security_require_post();
}
function security_confirm_delete() {
    security_require_login();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!doctype html><title>Confirmar eliminación</title><h1>Confirmar eliminación</h1><form method="post">';
        foreach ($_GET as $key => $value) {
            if (!is_string($value) || $key === 'csrf_token') continue;
            echo '<input type="hidden" name="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">';
        }
        echo '<button type="submit">Eliminar</button></form>';
        exit;
    }
    security_require_post();
    $_GET = $_POST; // The legacy procedure arguments use GET; only verified POST reaches them.
}

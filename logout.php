<?php
require __DIR__ . '/auth.php';

// Clear session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
}
session_destroy();

// Clear remember cookie
setcookie('pt_remember', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', true);

header('Location: login.html?logout=1');
exit;



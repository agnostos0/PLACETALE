<?php
// Session bootstrap and Remember Me auto-login
require_once __DIR__ . '/db.php';

// Secure session settings
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
if (!headers_sent()) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
session_start();

function is_logged_in(): bool {
    return !empty($_SESSION['user_email']);
}

// Attempt auto-login with Remember Me cookie if not logged in
if (!is_logged_in() && isset($_COOKIE['pt_remember'])) {
    $token = $_COOKIE['pt_remember'];
    $parts = explode('.', $token, 2);
    if (count($parts) === 2) {
        [$payload, $sig] = $parts;
        $secret = 'changeme-dev-secret';
        $calc = hash_hmac('sha256', $payload, $secret);
        if (hash_equals($calc, $sig)) {
            $claims = json_decode(base64_decode($payload), true);
            if (is_array($claims) && ($claims['x'] ?? 0) > time()) {
                // Load user from file store
                try {
                    $pdo = get_pdo();
                    $stmt = $pdo->prepare('SELECT name, email FROM users WHERE email = :e LIMIT 1');
                    $stmt->execute([':e' => strtolower($claims['e'] ?? '')]);
                    if ($row = $stmt->fetch()) {
                        $_SESSION['user_email'] = $row['email'];
                        $_SESSION['user_name'] = $row['name'];
                    }
                } catch (PDOException $e) {
                    // ignore on auto-login
                }
            }
        }
    }
}



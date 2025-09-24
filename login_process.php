<?php
require __DIR__ . '/db.php';
session_start();

function redirect_with_error(string $reason): void {
    header('Location: login.html?error=' . urlencode($reason));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error('invalid_method');
}

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$remember = isset($_POST['remember']);

if (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) || $password === '') {
    redirect_with_error('invalid_input');
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM users WHERE email = :e LIMIT 1');
    $stmt->execute([':e' => strtolower($email)]);
    $found = $stmt->fetch();
} catch (PDOException $e) {
    redirect_with_error('db_error');
}

if (!$found || !password_verify($password, $found['password_hash'])) {
    redirect_with_error('auth_failed');
}

// Auth success: set session
$_SESSION['user_email'] = $found['email'];
$_SESSION['user_name'] = $found['name'];

// Remember Me cookie (signed, expires in 7 days)
if ($remember) {
    $secret = 'changeme-dev-secret'; // set env var in production
    $payload = base64_encode(json_encode([
        'e' => $found['email'],
        't' => time(),
        'x' => time() + 7 * 24 * 60 * 60
    ]));
    $sig = hash_hmac('sha256', $payload, $secret);
    $token = $payload . '.' . $sig;
    setcookie('pt_remember', $token, [
        'expires' => time() + 7 * 24 * 60 * 60,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

header('Location: dashboard.php');
exit;



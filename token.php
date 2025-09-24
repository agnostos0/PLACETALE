<?php
// Simple signed token endpoint (demo). Do not use in production without HTTPS and a real secret manager.

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'method_not_allowed']);
    exit;
}

$email = trim((string)($_POST['email'] ?? ''));
if (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_email']);
    exit;
}

// Issue a short-lived token (5 minutes)
$secret = 'changeme-dev-secret';
$claims = [
    'sub' => strtolower($email),
    'iat' => time(),
    'exp' => time() + 300,
    'iss' => 'PlaceTale'
];

$payload = base64_encode(json_encode($claims));
$sig = hash_hmac('sha256', $payload, $secret);
$token = $payload . '.' . $sig;

echo json_encode(['token' => $token]);
exit;



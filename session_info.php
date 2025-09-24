<?php
require __DIR__ . '/auth.php';
header('Content-Type: application/json');

$resp = [
    'loggedIn' => is_logged_in(),
    'name' => $_SESSION['user_name'] ?? null,
    'email' => $_SESSION['user_email'] ?? null
];

echo json_encode($resp);
exit;



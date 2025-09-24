<?php
// Registration handler: sanitize, validate, store, and redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch raw inputs
    $rawName = $_POST['name'] ?? '';
    $rawEmail = $_POST['email'] ?? '';
    $rawPassword = $_POST['password'] ?? '';

    // Normalize whitespace
    $name = trim(preg_replace('/\s+/', ' ', (string)$rawName));
    $email = trim((string)$rawEmail);
    $password = (string)$rawPassword;

    // Sanitize for storage/display
    $name = filter_var($name, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate
    $nameValid = (strlen($name) >= 3 && str_word_count($name) >= 1);
    $emailValid = filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) !== false;
    $passwordValid = (strlen($password) >= 8);

    if (!$nameValid || !$emailValid || !$passwordValid) {
        header('Location: error.html?reason=invalid');
        exit;
    }

    // Prepare record
    $record = [
        'name' => $name,
        'email' => strtolower($emailSanitized),
        // Store hashed password only
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('c'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'ua' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];

    // Append to CSV for easy viewing
    $csvPath = __DIR__ . '/registrations.csv';
    $csvOk = false;
    if ($fp = @fopen($csvPath, 'a')) {
        @fputcsv($fp, [$record['name'], $record['email'], $record['password_hash'], $record['created_at'], $record['ip']]);
        @fclose($fp);
        $csvOk = true;
    }

    // Append to JSON-lines file for structured storage
    $jsonPath = __DIR__ . '/registrations.jsonl';
    $jsonOk = (bool)@file_put_contents($jsonPath, json_encode($record, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX);

    if ($csvOk || $jsonOk) {
        header('Location: success.html');
    } else {
        header('Location: error.html?reason=write_failed');
    }
    exit;
}
?>


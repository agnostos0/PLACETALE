<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawName = $_POST['name'] ?? '';
    $rawEmail = $_POST['email'] ?? '';
    $rawPassword = $_POST['password'] ?? '';

    $name = trim(preg_replace('/\s+/', ' ', (string)$rawName));
    $email = trim((string)$rawEmail);
    $password = (string)$rawPassword;

    $name = filter_var($name, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

    $nameValid = (strlen($name) >= 3 && str_word_count($name) >= 1);
    $emailValid = filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) !== false;
    $passwordValid = (strlen($password) >= 8);

    if (!$nameValid || !$emailValid || !$passwordValid) {
        header('Location: error.html?reason=invalid');
        exit;
    }

    $emailLower = strtolower($emailSanitized);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = get_pdo();
        $pdo->exec('CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(160) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:n, :e, :p)');
        $stmt->execute([':n' => $name, ':e' => $emailLower, ':p' => $hash]);
        header('Location: success.html');
    } catch (PDOException $ex) {
        if ((int)$ex->errorInfo[1] === 1062) {
            header('Location: error.html?reason=duplicate_email');
        } else {
            header('Location: error.html?reason=db_error');
        }
    }
    exit;
}
?>


<?php
function get_config(): array {
    $path = __DIR__ . '/config.php';
    if (!file_exists($path)) {
        $path = __DIR__ . '/config.example.php';
    }
    return require $path;
}

function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $cfg = get_config();
    $dsn = $cfg['db']['dsn'];
    $user = $cfg['db']['user'];
    $pass = $cfg['db']['pass'];
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}



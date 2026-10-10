<?php

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Pomijamy komentarze w .env
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

return [
    'host'             => $_ENV['DB_HOST']          ?? 'localhost',
    'user'             => $_ENV['DB_USER']          ?? 'root',
    'password'         => $_ENV['DB_PASS']          ?? '',
    'database'         => $_ENV['DB_NAME']          ?? 'homyfinance',
    'recaptcha_secret' => $_ENV['RECAPTCHA_SECRET'] ?? ''
];
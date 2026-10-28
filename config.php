<?php

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Pomijamy komentarze w .env
        }
        if (strpos($line, '=') !== false) {
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        }
    }
}

return [
    'host'             => $_ENV['DB_HOST']          ?? 'ppp.mysql.dhosting.pl',
    'user'             => $_ENV['DB_USER']          ?? 'eehe4d_budgetju',
    'password'         => $_ENV['DB_PASS']          ?? 'chaiV8oothee',
    'database'         => $_ENV['DB_NAME']          ?? 'pha7cu_budgetju',
    'recaptcha_secret' => $_ENV['RECAPTCHA_SECRET'] ?? ''
];
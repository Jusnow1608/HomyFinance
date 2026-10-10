<?php

function getDatabaseConnection(array $config): PDO
{
    try {
        return new PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC 
            ]
        );
    } catch (PDOException $e) {
        error_log('Database Connection Failure: ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
        exit('Database connection error. Please try again later.');
    }
}
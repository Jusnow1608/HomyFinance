<?php

declare(strict_types=1);

/* Funkcja walidująca dane logowania */

function validateLoginInput($email, string $password): ?string
{
    if (!$email) {
        return "Please enter a valid email address.";
    }

    if (empty($password)) {
        return "Please enter your password.";
    }

    return null; // Brak błędów
}

/* Funkcja odpowiedzialna za weryfikację użytkownika w bazie danych */

function authenticateUser(PDO $db, string $email, string $password): ?array
{
    $stmt = $db->prepare('SELECT id, username, email, password FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch();

    // Weryfikacja hasła za pomocą bezpiecznej funkcji PHP
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Zwraca tablicę z danymi użytkownika w przypadku sukcesu
    }

    return null; // Błędne dane lub brak użytkownika
}
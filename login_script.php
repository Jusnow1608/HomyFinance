<?php

$config = require 'config.php';
session_start();

// Guard: Zabezpieczenie przed bezpośrednim wejściem pod URL
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: login.php');
    exit();
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

// 1. Walidacja danych wejściowych za pomocą funkcji
$validationError = validateLoginInput($email, $password);
if ($validationError !== null) {
    $_SESSION['e_login'] = $validationError;
    header('Location: login.php');
    exit();
}

require_once 'database.php';

try {
    $db = getDatabaseConnection($config);

    // 2. Uwierzytelnianie użytkownika za pomocą funkcji
    $user = authenticateUser($db, $email, $password);

    // Weryfikacja konta i hasła
    if ($user) {
        // Regeneracja ID sesji dla bezpieczeństwa (zabezpieczenie przed Session Fixation)
        session_regenerate_id(true);

        $_SESSION['logged_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];

        unset($_SESSION['e_login']);
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['e_login'] = "Invalid email or password!";
        header('Location: login.php');
        exit();
    }

} catch (PDOException $e) {
    error_log('Login Error (SQL/PDO): ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
    $_SESSION['e_login'] = "Server error. Please try again later.";
    header('Location: login.php');
    exit();
}


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
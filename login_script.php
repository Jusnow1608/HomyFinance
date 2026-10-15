<?php

declare(strict_types=1);

session_start();

$config = require 'config.php';
require_once 'functions.php';
require_once 'login_functions.php';

// Guard: Zabezpieczenie przed bezpośrednim wejściem pod URL
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: login.php');
    exit();
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

// Zapamiętanie wprowadzonego e-maila w sesji w razie błędu logowania
$_SESSION['given_login_email'] = $_POST['email'];

// 1. Walidacja danych wejściowych za pomocą funkcji
$validationError = validateLoginInput($email, $password);
if ($validationError !== null) {
    redirectWithLoginError($validationError);   
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
        unset($_SESSION['given_login_email']);
        
        header('Location: index.php');
        exit();
    } else {
        redirectWithLoginError("Invalid email or password!");
    }

} catch (PDOException $e) {
    error_log('Login Error (SQL/PDO): ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
    redirectWithLoginError("Server error. Please try again later.");
}

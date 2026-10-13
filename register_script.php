<?php

$config = require 'config.php';

session_start();

require_once 'functions.php';

if (!isset($_POST['email'])) {
    header('Location: register.php');
    exit();
}

$name = trim($_POST['name']);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

$_SESSION['given_name'] = $name;
$_SESSION['given_email'] = $_POST['email'];

$validationError = validateRegistrationInput($name, $email, $password, $recaptchaResponse);
if ($validationError !== null) {
    redirectWithError($validationError, 'register.php');
}

if (!verifyRecaptcha($config['recaptcha_secret'], $recaptchaResponse)) {
   redirectWithError("reCAPTCHA verification failed. Please try again.", 'register.php');
}


require_once 'database.php';

try {

    $db = getDatabaseConnection($config);
    
    if (!registerUser($db, $name, $email, $password)) {
        redirectWithError("An account with this email already exists!", 'register.php');
    }
   
    unset($_SESSION['given_name']);
    unset($_SESSION['given_email']);

    redirectWithSuccess("Account created successfully! You can now log in.", 'login.php');

    } catch (PDOException $e) {
    error_log('Registration Error (SQL/PDO): ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
    redirectWithError("Server error. Please try again later.", 'register.php');
}


function validateRegistrationInput(string $name, $email, string $password, string $recaptchaResponse): ?string
{

    if (empty($name) || strlen($name) < 3) {
       return "Please enter a valid name (at least 3 characters).";
    }

    if (!$email) {
       return "Please enter a valid email address.";
    }

    if ((strlen($password) < 8) || (strlen($password)>20)){
       return "Password must be between 8 and 20 characters long.";
    }

    if (empty($recaptchaResponse)) {
       return "Please confirm that you are not a robot.";
    }

    return null;
}


function verifyRecaptcha(string $secretKey, string $recaptchaResponse): bool
{
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    
    $postData = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    ];

    $ch = curl_init();
    
    // Ustawienie adresu URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // Włączenie metody POST
    curl_setopt($ch, CURLOPT_POST, true);
    // Przekazanie parametrów wyformatowanych jako multipart/form-data lub application/x-www-form-urlencoded
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    // Zwrócenie odpowiedzi jako ciąg znaków zamiast bezpośredniego wypisania na ekranie
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Limit czasu oczekiwania na odpowiedź od Google (np. 5 sekund)
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return false;
    }

    $responseData = json_decode($response);

    return isset($responseData->success) && $responseData->success === true;
}

function registerUser(PDO $db, string $name, string $email, string $password): bool
{
    $checkQuery = $db->prepare('SELECT id FROM users WHERE email = :email');
    $checkQuery->bindValue(':email', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
       return false;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // 1. Rozpoczynamy transakcję bazodanową
    $db->beginTransaction();

    try {
        // 2. Dodanie nowego użytkownika
        $insertQuery = $db->prepare('INSERT INTO users (username, password, email) VALUES (:name, :password, :email)');
        $insertQuery->bindValue(':name', $name, PDO::PARAM_STR);
        $insertQuery->bindValue(':email', $email, PDO::PARAM_STR);
        $insertQuery->bindValue(':password', $passwordHash, PDO::PARAM_STR);
        $insertQuery->execute();

        // 3. Pobranie ID nowo utworzonego użytkownika
        $userId = $db->lastInsertId();

        // 4. Kopiowanie domyślnych kategorii wydatków
        $db->query("INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT $userId, name FROM expenses_category_default");

        // 5. Kopiowanie domyślnych kategorii przychodów
        $db->query("INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT $userId, name FROM incomes_category_default");

        // 6. Kopiowanie domyślnych metod płatności
        $db->query("INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT $userId, name FROM payment_methods_default");

        // 7. Zatwierdzenie transakcji, jeśli wszystko poszło pomyślnie
        $db->commit();
        return true;

    } catch (Exception $e) {
        // W razie jakiegokolwiek błędu cofamy zmiany w bazie
        $db->rollBack();
        throw $e;
    }
}
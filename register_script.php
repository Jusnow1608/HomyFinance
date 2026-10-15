<?php

declare(strict_types=1);

session_start();

$config = require 'config.php';
require_once 'functions.php';
require_once 'register_functions.php';

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

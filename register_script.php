<?php

$config = require 'config.php';

session_start();

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

if (empty($name) || strlen($name) < 3) {
    $_SESSION['e_register'] = "Please enter a valid name (at least 3 characters).";
    header('Location: register.php');
    exit();
}

if (!$email) {
    $_SESSION['e_register'] = "Please enter a valid email address.";
    header('Location: register.php');
    exit();
}

if ((strlen($password) < 8) || (strlen($password)>20)){
    $_SESSION['e_register'] = "Password must be between 8 and 20 characters long.";
    header('Location: register.php');
    exit();
}

if (empty($recaptchaResponse)) {
    $_SESSION['e_register'] = "Please confirm that you are not a robot.";
    header('Location: register.php');
    exit();
}

$secretKey = $config['recaptcha_secret'];
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

$verifyResponse = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
$responseData = json_decode($verifyResponse);

if (!$responseData || !$responseData->success) {
    $_SESSION['e_register'] = "reCAPTCHA verification failed. Please try again.";
    header('Location: register.php');
    exit();
}

require_once 'database.php';

$db = getDatabaseConnection($config);

try {

    $checkQuery = $db->prepare('SELECT id FROM users WHERE email = :email');
    $checkQuery->bindValue(':email', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        $_SESSION['e_register'] = "An account with this email already exists!";
        header('Location: register.php');
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $insertQuery = $db->prepare('INSERT INTO users (username, password, email) VALUES (:name, :password, :email)');
    $insertQuery->bindValue(':name', $name, PDO::PARAM_STR);
    $insertQuery->bindValue(':email', $email, PDO::PARAM_STR);
    $insertQuery->bindValue(':password', $passwordHash, PDO::PARAM_STR);
    $insertQuery->execute();

    unset($_SESSION['given_name']);
    unset($_SESSION['given_email']);

    $_SESSION['register_success'] = "Account created successfully! You can now log in.";
    header('Location: login.php');
    exit();

    } catch (PDOException $e) {
    $_SESSION['e_register'] = "Server error. Please try again later.";
    header('Location: register.php');
    exit();
}
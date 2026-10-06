<?php
session_start();

if (!isset($_POST['email'])) {
    header('Location: register.php');
    exit();
}

$name = trim($_POST['name']);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];

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
    $_SESSION['e_register'] = "Password must be at least 8 characters long and have no more than 20 characters.";
    header('Location: register.php');
    exit();
}

require_once 'database.php';

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

    $insertQuery = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
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
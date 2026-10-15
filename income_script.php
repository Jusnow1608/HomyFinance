<?php

declare(strict_types=1);

session_start();

$config = require 'config.php';
require_once 'functions.php';
require_once 'income_functions.php';

// Guard: Sprawdzenie czy żądanie pochodzi z formularza metodą POST
if (!isset($_POST['amount'])) {
    header('Location: income.php');
    exit();
}

// Guard: Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['logged_id'];
$amount = trim($_POST['amount']);
$incomeDate = $_POST['incomeDate'] ?? '';
$categoryId = $_POST['category'] ?? '';
$comment = trim($_POST['comment'] ?? '');

// Zapamiętanie wprowadzonych danych w sesji na wypadek błędu walidacji
$_SESSION['given_income_amount'] = $amount;
$_SESSION['given_income_date'] = $incomeDate;
$_SESSION['given_income_category'] = $categoryId;
$_SESSION['given_income_comment'] = $comment;

require_once 'database.php';

try {
    $db = getDatabaseConnection($config);

    // 1. Walidacja danych wejściowych i uprawnień do ID kategorii przychodu
    $validationError = validateAndGetIncomeIds($db, $userId, $amount, $incomeDate, (int)$categoryId, $comment);
    if ($validationError !== null) {
        redirectWithIncomeError($validationError);
    }

    // 2. Zapis przychodu w bazie danych za pomocą prepared statement
    if (saveIncome($db, $userId, $amount, $incomeDate, (int)$categoryId, $comment)) {
        
        // Czyszczenie danych zapasowych z sesji po sukcesie
        unset($_SESSION['given_income_amount']);
        unset($_SESSION['given_income_date']);
        unset($_SESSION['given_income_category']);
        unset($_SESSION['given_income_comment']);

        $_SESSION['income_success'] = "Income added successfully!";
        header('Location: income.php');
        exit();
    } else {
        redirectWithIncomeError("Failed to add income. Please try again.");
    }

} catch (PDOException $e) {
    error_log('Income Error (SQL/PDO): ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
    redirectWithIncomeError("Server error. Please try again later.");
}

<?php
session_start();

$config = require 'config.php';
require_once 'functions.php';
require_once 'database.php';

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

try {
    $db = getDatabaseConnection($config);

    // 1. Walidacja danych wejściowych i uprawnień do ID kategorii przychodu
    $validationError = validateAndGetIncomeIds($db, $userId, $amount, $incomeDate, $categoryId, $comment);
    if ($validationError !== null) {
        redirectWithIncomeError($validationError);
    }

    // 2. Zapis przychodu w bazie danych za pomocą prepared statement
    if (saveIncome($db, $userId, $amount, $incomeDate, $categoryId, $comment)) {
        
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


/**
 * Walidacja danych oraz weryfikacja czy kategoria przychodu należy do użytkownika
 */
function validateAndGetIncomeIds(PDO $db, int $userId, $amount, string $date, $categoryId, $comment): ?string
{
    // Zamiana przecinka na kropkę (gdyby użytkownik wpisał np. 12,50)
    $amount = str_replace(',', '.', $amount);

    if (!is_numeric($amount) || floatval($amount) <= 0) {
        return "Please enter a valid amount greater than 0.";
    }

    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return "Please select a valid date.";
    }

    // Sprawdzenie, czy liczba ma maksymalnie dwie cyfry po przecinku
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
        return "Amount can have a maximum of 2 decimal places.";
    }

    list($year, $month, $day) = explode('-', $date);

    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        return "Please select a valid calendar date.";
    }

    // Weryfikacja czy kategoria przychodu należy do zalogowanego użytkownika
    $catStmt = $db->prepare('SELECT id FROM incomes_category_assigned_to_users WHERE id = :catId AND user_id = :userId');
    $catStmt->execute([':catId' => $categoryId, ':userId' => $userId]);
    if (!$catStmt->fetch()) {
        return "Invalid category selected.";
    }
    
    if (mb_strlen($comment) > 100) {
        return "Comment cannot exceed 100 characters.";
    }

    return null; // Brak błędów
}


/**
 * Zapis przychodu w bazie danych przy użyciu PDO (Prepared Statements)
 */
function saveIncome(PDO $db, int $userId, string $amount, string $date, int $categoryId, string $comment): bool
{
    $query = $db->prepare('
        INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) 
        VALUES (:user_id, :cat_id, :amount, :date, :comment)
    ');

    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
    $query->bindValue(':amount', $amount, PDO::PARAM_STR);
    $query->bindValue(':date', $date, PDO::PARAM_STR);
    $query->bindValue(':comment', $comment, PDO::PARAM_STR);

    return $query->execute();
}
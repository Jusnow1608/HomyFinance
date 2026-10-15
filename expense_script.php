<?php

declare(strict_types=1);

session_start();

$config = require 'config.php';
require_once 'functions.php';
require_once 'expense_functions.php';

// Guard: Sprawdzenie czy żądanie pochodzi z formularza metodą POST
if (!isset($_POST['amount'])) {
    header('Location: expense.php');
    exit();
}

// Guard: Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['logged_id'];
$amount = trim($_POST['amount']);
$expenseDate = $_POST['expenseDate'] ?? '';
$paymentMethodId = $_POST['paymentMethod'] ?? '';
$categoryId = $_POST['category'] ?? '';
$comment = trim($_POST['comment'] ?? '');

// Zapamiętanie wprowadzonych danych w sesji na wypadek błędu walidacji
$_SESSION['given_expense_amount'] = $amount;
$_SESSION['given_expense_date'] = $expenseDate;
$_SESSION['given_expense_payment'] = $paymentMethodId;
$_SESSION['given_expense_category'] = $categoryId;
$_SESSION['given_expense_comment'] = $comment;

require_once 'database.php';

try {
    $db = getDatabaseConnection($config);

    // 1. Walidacja danych wejściowych i uprawnień do ID kategorii / płatności
    $validationError = validateAndGetExpenseIds($db, $userId, $amount, $expenseDate, (int)$paymentMethodId, (int)$categoryId, $comment);
    if ($validationError !== null) {
        redirectWithExpenseError($validationError);
    }

    // 2. Zapis wydatku w bazie danych za pomocą prepared statement
    if (saveExpense($db, $userId, $amount, $expenseDate, (int)$paymentMethodId, (int)$categoryId, $comment)) {
        
        // Czyszczenie danych zapasowych z sesji po sukcesie
        unset($_SESSION['given_expense_amount']);
        unset($_SESSION['given_expense_date']);
        unset($_SESSION['given_expense_payment']);
        unset($_SESSION['given_expense_category']);
        unset($_SESSION['given_expense_comment']);

        $_SESSION['expense_success'] = "Expense added successfully!";
        header('Location: expense.php');
        exit();
    } else {
        redirectWithExpenseError("Failed to add expense. Please try again.");
    }

} catch (PDOException $e) {
    error_log('Expense Error (SQL/PDO): ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
    redirectWithExpenseError("Server error. Please try again later.");
}

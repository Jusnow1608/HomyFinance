<?php
session_start();

$config = require 'config.php';
require_once 'functions.php';
require_once 'database.php';

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
$paymentMethod = $_POST['paymentMethod'] ?? '';
$category = $_POST['category'] ?? '';
$comment = trim($_POST['comment'] ?? '');

// Zapamiętanie wprowadzonych danych w sesji na wypadek błędu walidacji
$_SESSION['given_expense_amount'] = $amount;
$_SESSION['given_expense_date'] = $expenseDate;
$_SESSION['given_expense_payment'] = $paymentMethod;
$_SESSION['given_expense_category'] = $category;
$_SESSION['given_expense_comment'] = $comment;

// 1. Walidacja danych wejściowych
// Sprawdzamy dane wejściowe oraz uprawnienia do kategorii i metod płatności
$validationError = validateAndGetExpenseIds($db, $userId, $amount, $expenseDate, $paymentMethod, $category);
if ($validationError !== null) {
    redirectWithExpenseError($validationError);
}

try {
    $db = getDatabaseConnection($config);

    // 2. Zapis wydatku w bazie danych za pomocą prepared statement
    if (saveExpense($db, $userId, $amount, $expenseDate, $paymentMethod, $category, $comment)) {
        
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


/**
 * Walidacja danych formularza wydatku
 */
function validateAndGetExpenseIds(PDO $db, int $userId, $amount, string $date, $paymentMethodId, $categoryId): ?array
{
    if (!is_numeric($amount) || floatval($amount) <= 0) {
        return ["error" => "Please enter a valid amount greater than 0."];
    }

    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return ["error" => "Please select a valid date."];
    }

    // Weryfikacja czy metoda płatności należy do użytkownika
    $payStmt = $db->prepare('SELECT id FROM payment_methods_assigned_to_users WHERE id = :payId AND user_id = :userId');
    $payStmt->execute([':payId' => $paymentMethodId, ':userId' => $userId]);
    if (!$payStmt->fetch()) {
        return ["error" => "Invalid payment method selected."];
    }

    // Weryfikacja czy kategoria należy do użytkownika
    $catStmt = $db->prepare('SELECT id FROM expenses_category_assigned_to_users WHERE id = :catId AND user_id = :userId');
    $catStmt->execute([':catId' => $categoryId, ':userId' => $userId]);
    if (!$catStmt->fetch()) {
        return ["error" => "Invalid category selected."];
    }

    return null; // Brak błędów
}

/**
 * Zapis w bazie danych przy użyciu PDO (Prepared Statements)
 */
function saveExpense(PDO $db, int $userId, string $amount, string $date, string $paymentMethod, string $category, string $comment): bool
{
    // Uwaga: Dostosuj nazwy tabeli i kolumn (user_id, amount, expense_date, payment_method, category, comment) do swojej struktury bazy danych
    $query = $db->prepare('
        INSERT INTO expenses (user_id, amount, date_of_expense, payment_method_assigned_to_user_id, expense_category_assigned_to_user_id, expense_comment) 
        VALUES (:user_id, :amount, :expense_date, :payment_method, :category, :comment)
    ');

    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindValue(':amount', $amount, PDO::PARAM_STR);
    $query->bindValue(':expense_date', $date, PDO::PARAM_STR);
    $query->bindValue(':payment_method', $paymentMethod, PDO::PARAM_STR);
    $query->bindValue(':category', $category, PDO::PARAM_STR);
    $query->bindValue(':comment', $comment, PDO::PARAM_STR);

    return $query->execute();
}
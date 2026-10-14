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
$paymentMethodId = $_POST['paymentMethod'] ?? '';
$categoryId = $_POST['category'] ?? '';
$comment = trim($_POST['comment'] ?? '');

// Zapamiętanie wprowadzonych danych w sesji na wypadek błędu walidacji
$_SESSION['given_expense_amount'] = $amount;
$_SESSION['given_expense_date'] = $expenseDate;
$_SESSION['given_expense_payment'] = $paymentMethodId;
$_SESSION['given_expense_category'] = $categoryId;
$_SESSION['given_expense_comment'] = $comment;

try {
    $db = getDatabaseConnection($config);

    // 1. Walidacja danych wejściowych i uprawnień do ID kategorii / płatności
    $validationError = validateAndGetExpenseIds($db, $userId, $amount, $expenseDate, $paymentMethodId, $categoryId, $comment);
    if ($validationError !== null) {
        redirectWithExpenseError($validationError);
    }

    // 2. Zapis wydatku w bazie danych za pomocą prepared statement
    if (saveExpense($db, $userId, $amount, $expenseDate, $paymentMethodId, $categoryId, $comment)) {
        
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
 * Walidacja danych oraz weryfikacja czy kategoria i metoda płatności należą do użytkownika
 */
function validateAndGetExpenseIds(PDO $db, int $userId, $amount, string $date, $paymentMethodId, $categoryId, $comment): ?string
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
    // (np. pasuje 10, 10.5, 10.55, ale odrzuci 10.555)
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
        return "Amount can have a maximum of 2 decimal places.";
    }

    list($year, $month, $day) = explode('-', $date);

    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        return "Please select a valid calendar date.";
}

    // Weryfikacja czy metoda płatności należy do zalogowanego użytkownika
    $payStmt = $db->prepare('SELECT id FROM payment_methods_assigned_to_users WHERE id = :payId AND user_id = :userId');
    $payStmt->execute([':payId' => $paymentMethodId, ':userId' => $userId]);
    if (!$payStmt->fetch()) {
        return "Invalid payment method selected.";
    }

    // Weryfikacja czy kategoria należy do zalogowanego użytkownika
    $catStmt = $db->prepare('SELECT id FROM expenses_category_assigned_to_users WHERE id = :catId AND user_id = :userId');
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
 * Zapis w bazie danych przy użyciu PDO (Prepared Statements) */
function saveExpense(PDO $db, int $userId, string $amount, string $date, int $paymentMethodId, int $categoryId, string $comment): bool
{
    $query = $db->prepare('
        INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) 
        VALUES (:user_id, :cat_id, :pay_id, :amount, :date, :comment)
    ');

    $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $query->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
    $query->bindValue(':pay_id', $paymentMethodId, PDO::PARAM_INT);
    $query->bindValue(':amount', $amount, PDO::PARAM_STR);
    $query->bindValue(':date', $date, PDO::PARAM_STR);
    $query->bindValue(':comment', $comment, PDO::PARAM_STR);

    return $query->execute();
}
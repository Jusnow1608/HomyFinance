<?php

declare(strict_types=1);

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

    // Dodatkowe zabezpieczenie przed przepełnieniem typu DECIMAL(8,2) w bazie
    if (floatval($amount) > 999999.99) {
        return "Amount is too large (maximum is 999,999.99).";
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
    $payStmt->execute([':payId' => (int)$paymentMethodId, ':userId' => $userId]);
    if (!$payStmt->fetch()) {
        return "Invalid payment method selected.";
    }

    // Weryfikacja czy kategoria należy do zalogowanego użytkownika
    $catStmt = $db->prepare('SELECT id FROM expenses_category_assigned_to_users WHERE id = :catId AND user_id = :userId');
    $catStmt->execute([':catId' => (int)$categoryId, ':userId' => $userId]);
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
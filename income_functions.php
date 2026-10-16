<?php

declare(strict_types=1);

/**
 * Walidacja danych oraz weryfikacja czy kategoria przychodu należy do użytkownika
 */
function validateAndGetIncomeIds(PDO $db, int $userId, $amount, string $date, $categoryId, $comment): ?string
{
    $error = validateTransactionData($amount, $date, $comment);
    if ($error !== null) {
        return $error;
    }

    // Weryfikacja czy kategoria przychodu należy do zalogowanego użytkownika
    $catStmt = $db->prepare('SELECT id FROM incomes_category_assigned_to_users WHERE id = :catId AND user_id = :userId');
    $catStmt->execute([':catId' => (int)$categoryId, ':userId' => $userId]);
    if (!$catStmt->fetch()) {
        return "Invalid category selected.";
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
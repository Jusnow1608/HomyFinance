<?php
declare(strict_types=1);

/**
 * Zwraca tablicę z datą początkową i końcową na podstawie wybranego okresu
 */
function getDateRange(string $period, ?string $customStart = null, ?string $customEnd = null): array
{
    $currentYear = date('Y');
    $currentMonth = date('m');

    switch ($period) {
        case 'previous_month':
            // Poprzedni miesiąc
            $start = date('Y-m-01', strtotime('first day of previous month'));
            $end = date('Y-m-t', strtotime('last day of previous month'));
            break;

        case 'current_year':
            // Bieżący rok (od 1 stycznia do 31 grudnia)
            $start = $currentYear . '-01-01';
            $end = $currentYear . '-12-31';
            break;

        case 'custom':
            // Niestandardowy zakres (z modala)
            $start = ($customStart && preg_match('/^\d{4}-\d{2}-\d{2}$/', $customStart)) ? $customStart : date('Y-m-01');
            $end = ($customEnd && preg_match('/^\d{4}-\d{2}-\d{2}$/', $customEnd)) ? $customEnd : date('Y-m-t');
            break;

        case 'current_month':
        default:
            // Bieżący miesiąc (domyślnie)
            $start = date('Y-m-01');
            $end = date('Y-m-t');
            break;
    }

    return [
        'start' => $start,
        'end' => $end
    ];
}

/**
 * Pobiera sumę wydatków pogrupowaną po kategoriach dla danego użytkownika i przedziału dat
 */
function getExpensesByCategory(PDO $db, int $userId, string $startDate, string $endDate): array
{
    $stmt = $db->prepare('
        SELECT 
            c.name AS category_name,
            SUM(e.amount) AS total_amount
        FROM expenses e
        JOIN expenses_category_assigned_to_users c ON e.expense_category_assigned_to_user_id = c.id
        WHERE e.user_id = :userId 
          AND e.date_of_expense BETWEEN :startDate AND :endDate
        GROUP BY c.id, c.name
        ORDER BY total_amount DESC
    ');
    
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Pobiera sumę przychodów pogrupowaną po kategoriach dla danego użytkownika i przedziału dat
 */
function getIncomesByCategory(PDO $db, int $userId, string $startDate, string $endDate): array
{
    $stmt = $db->prepare('
        SELECT 
            c.name AS category_name,
            SUM(i.amount) AS total_amount
        FROM incomes i
        JOIN incomes_category_assigned_to_users c ON i.income_category_assigned_to_user_id = c.id
        WHERE i.user_id = :userId 
          AND i.date_of_income BETWEEN :startDate AND :endDate
        GROUP BY c.id, c.name
        ORDER BY total_amount DESC
    ');
    
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}


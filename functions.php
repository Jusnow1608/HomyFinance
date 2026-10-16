<?php

/**
 * Pomocnicza funkcja do bezpiecznego przekierowywania z komunikatem o błędzie.
 * 
 * @param string $errorMessage Komunikat błędu do wyświetlenia w sesji
 * @param string $redirectUrl Docelowy adres URL (domyślnie 'register.php')
 */
function redirectWithError(string $errorMessage, string $redirectUrl = 'register.php'): void {
    $_SESSION['e_register'] = $errorMessage;
    header("Location: $redirectUrl");
    exit();
}

/**
 * Pomocnicza funkcja do bezpiecznego przekierowywania z komunikatem o błędzie logowania.
 */
function redirectWithLoginError(string $errorMessage, string $redirectUrl = 'login.php'): void {
    $_SESSION['e_login'] = $errorMessage;
    header("Location: $redirectUrl");
    exit();
}

/**
 * Pomocnicza funkcja do bezpiecznego przekierowywania z komunikatem o sukcesie.
 * 
 * @param string $successMessage Komunikat sukcesu do wyświetlenia w sesji
 * @param string $redirectUrl Docelowy adres URL (domyślnie 'login.php')
 */
function redirectWithSuccess(string $successMessage, string $redirectUrl = 'login.php'): void {
    $_SESSION['register_success'] = $successMessage;
    header("Location: $redirectUrl");
    exit();
}

/**
 * Funkcja pomocnicza do przekierowania z błędem specyficznym dla wydatków
 */
function redirectWithExpenseError(string $errorMessage): void {
    $_SESSION['e_expense'] = $errorMessage;
    header('Location: expense.php');
    exit();
}

/**
 * Funkcja pomocnicza do przekierowania z błędem specyficznym dla przychodów
 */
function redirectWithIncomeError(string $errorMessage): void {
    $_SESSION['e_income'] = $errorMessage;
    header('Location: income.php');
    exit();
}

/**
 * Uniwersalna walidacja kwoty, daty i komentarza dla transakcji
 */
function validateTransactionData($amount, string $date, string $comment): ?string
{
    $amount = str_replace(',', '.', $amount);

    if (!is_numeric($amount) || floatval($amount) <= 0) {
        return "Please enter a valid amount greater than 0.";
    }

    if (floatval($amount) > 999999.99) {
        return "Amount is too large (maximum is 999,999.99).";
    }

    if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return "Please select a valid date.";
    }

    if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
        return "Amount can have a maximum of 2 decimal places.";
    }

    list($year, $month, $day) = explode('-', $date);

    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        return "Please select a valid calendar date.";
    }

    if (mb_strlen($comment) > 100) {
        return "Comment cannot exceed 100 characters.";
    }

    return null;
}
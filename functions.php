<?php

/**
 * Uniwersalna funkcja do bezpiecznego przekierowywania z komunikatem o błędzie w sesji.
 * 
 * @param string $errorMessage Treść komunikatu błędu
 * @param string $sessionKey Klucz sesji, pod którym zapisany będzie błąd (np. 'e_expense', 'e_login')
 * @param string $redirectUrl Adres URL, na który nastąpi przekierowanie
 */
function redirectWithError(string $errorMessage, string $sessionKey, string $redirectUrl): void {
    $_SESSION[$sessionKey] = $errorMessage;
    header("Location: $redirectUrl");
    exit();
}

/**
 * Uniwersalna funkcja do bezpiecznego przekierowywania z komunikatem o sukcesie.
 */
function redirectWithSuccess(string $successMessage, string $sessionKey, string $redirectUrl): void {
    $_SESSION[$sessionKey] = $successMessage;
    header("Location: $redirectUrl");
    exit();
}

/**
 * Uniwersalna walidacja kwoty, daty i komentarza dla transakcji
 */
function validateTransactionData($amount, string $date, string $comment): ?string
{
   
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

/**
 * Bezpieczne escapowanie danych przed wypisaniem do HTML (ochrona przed XSS)
 */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generuje lub zwraca istniejący token CSRF dla sesji użytkownika
 */
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Sprawdza czy przesłany token CSRF jest poprawny
 */
function isValidCsrf(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
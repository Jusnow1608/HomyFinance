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
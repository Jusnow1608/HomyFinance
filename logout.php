<?php

session_start();
require_once 'functions.php';

//Sprawdzenie tokena CSRF przy wylogowaniu
if (!isValidCsrf()) {
    header('Location: index.php');
    exit();
}

// Czyszczenie sesji i ciastek sesyjnych
session_unset();
session_destroy();

header('Location: login.php');
exit();
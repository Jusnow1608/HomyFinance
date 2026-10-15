<?php

session_start();

// Czyszczenie sesji i ciastek sesyjnych
session_unset();
session_destroy();

header('Location: login.php');
exit();
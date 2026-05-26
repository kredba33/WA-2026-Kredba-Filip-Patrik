<?php

// Zobrazení chyb při vývoji
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Spuštění session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konstanty
define('BASE_URL', 'http://localhost/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public');
define('APP_ROOT', dirname(__DIR__));

// Zavolání routeru (App rozsekne URL a spustí správný controller -> metoda -> view)
require_once APP_ROOT . '/core/App.php';
new App();

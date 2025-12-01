<?php
// Load .env
function loadEnv() {
    if (!file_exists('.env')) die('.env file not found');
    
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv();

// Get environment variable
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Database connection
function db() {
    static $conn = null;
    if ($conn === null) {
        $conn = new PDO(
            "mysql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_NAME'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $conn;
}

session_start();
?>

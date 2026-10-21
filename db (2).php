<?php
// db.php - database connection + shared helper functions
// Included by every page that needs the database.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = "localhost";
$DB_NAME = "pet_adoption";
$DB_USER = "root";      // change if your MySQL user is different
$DB_PASS = "";          // change if your MySQL has a password

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit("Database connection failed. Check db.php settings.");
}

// Short helper to safely print text in HTML
function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

// CSRF protection for forms
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_check() {
    $sent = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(400);
        exit("Invalid request. Please go back and try again.");
    }
}

// Convert "2 Years Old" / "8 Months Old" into baby / young / adult
// (matches the options used by pet_filter.js)
function age_group($age) {
    preg_match('/\d+/', $age, $m);
    $n = isset($m[0]) ? (int)$m[0] : 0;
    if (stripos($age, 'month') !== false) return 'baby';
    if ($n < 1)  return 'baby';
    if ($n <= 2) return 'young';
    return 'adult';
}

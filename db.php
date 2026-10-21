<?php
// Shared database connection + helpers. Include with: require 'db.php';
session_start();

$dbHost = '127.0.0.1';
$dbPort = '3307';
$dbName = 'pet_adoption';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    http_response_code(500);
    exit('Sorry, PawHome could not connect to the database. Please try again later.');
}

// Escape text before printing it in HTML
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// CSRF protection for forms
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_valid($token): bool {
    return !empty($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}

// Hidden CSRF input for admin forms
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

// Stop the request if the CSRF token is missing or wrong (used by admin pages)
function csrf_check(): void {
    if (!csrf_valid($_POST['csrf'] ?? null)) {
        http_response_code(400);
        exit('Invalid request. Please go back and try again.');
    }
}

// Pet photo path, or a simple placeholder if the file is missing
function pet_image(?string $path): string {
    if ($path && is_file(__DIR__ . '/' . $path)) {
        return $path;
    }
    $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'><rect width='400' height='300' fill='#efdeca'/>"
         . "<g fill='#976133' opacity='.6'><ellipse cx='200' cy='180' rx='48' ry='40'/><circle cx='135' cy='128' r='20'/>"
         . "<circle cx='178' cy='96' r='20'/><circle cx='222' cy='96' r='20'/><circle cx='265' cy='128' r='20'/></g></svg>";
    return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
}

// CSS-safe class for a status, e.g. "Available" -> "available"
function status_class(string $status): string {
    return strtolower(preg_replace('/[^a-zA-Z]/', '', $status));
}
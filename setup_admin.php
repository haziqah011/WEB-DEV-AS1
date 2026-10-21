<?php
// setup_admin.php - RUN ONCE in the browser, then DELETE THIS FILE.
// Creates the first administrator account.
require_once "db.php";

$username = "pluto";
$password = "pluto1234";     // change this before running if you want

$count = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();

if ($count > 0) {
    exit("An admin already exists. Delete setup_admin.php.");
}

$stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

echo "Admin created. Username: " . e($username) . " / Password: " . e($password) .
     "<br><strong>Now delete setup_admin.php.</strong> <a href='admin.php'>Go to login</a>";

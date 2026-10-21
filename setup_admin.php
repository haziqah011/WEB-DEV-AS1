<?php
// setup_admin.php - RUN ONCE in the browser, then DELETE THIS FILE.
require_once "db.php";

$username = "pluto";
$password = "pluto1234";

$pdo->exec("DELETE FROM admins");

$stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

echo "Admin created. Username: " . e($username) . " / Password: " . e($password) .
     "<br><strong>Now delete setup_admin.php.</strong> <a href='admin.php'>Go to login</a>";
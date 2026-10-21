<?php
// If the visitor is not logged in, send them to the login page.
require_once "db.php";

if (empty($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

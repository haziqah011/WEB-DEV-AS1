<?php
// admin_auth.php - put this at the top of every admin page.
// If the visitor is not logged in, send them to the login page.
require_once "db.php";

if (empty($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

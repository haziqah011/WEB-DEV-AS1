<?php
// admin_header.php - shared top part of admin pages
// Set $pageTitle and $activePage before including.
$pageTitle  = $pageTitle  ?? "Admin";
$activePage = $activePage ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | PawHome Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header>
    <h1>PawHome 🐾 <small class="admin-tag">Admin</small></h1>
    <nav>
        <a href="admin_dashboard.php" class="<?php echo $activePage==='dashboard'?'active':''; ?>">Dashboard</a>
        <a href="admin_pets.php" class="<?php echo $activePage==='pets'?'active':''; ?>">Pets</a>
        <a href="admin_applications.php" class="<?php echo $activePage==='applications'?'active':''; ?>">Applications</a>
        <a href="admin_reviews.php" class="<?php echo $activePage==='reviews'?'active':''; ?>">Reviews</a>
        <a href="admin_report.php" class="<?php echo $activePage==='report'?'active':''; ?>">Report</a>
        <a href="admin_logout.php">Logout</a>
    </nav>
</header>

<main class="admin-page">

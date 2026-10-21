<?php
require_once "admin_auth.php";

$totalPets    = $pdo->query("SELECT COUNT(*) FROM pets")->fetchColumn();
$available    = $pdo->query("SELECT COUNT(*) FROM pets WHERE status='Available'")->fetchColumn();
$adopted      = $pdo->query("SELECT COUNT(*) FROM pets WHERE status='Adopted'")->fetchColumn();
$pending      = $pdo->query("SELECT COUNT(*) FROM applications WHERE status='Pending'")->fetchColumn();
$totalApps    = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$totalReviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();

$recent = $pdo->query(
    "SELECT a.*, p.name AS pet_name FROM applications a
     JOIN pets p ON p.id = a.pet_id
     ORDER BY a.created_at DESC, a.id DESC LIMIT 5"
)->fetchAll();

$pageTitle  = "Dashboard";
$activePage = "dashboard";
include "admin_header.php";
?>

<h2>Welcome, <?php echo e($_SESSION['admin_name']); ?> 👋</h2>

<div class="stat-grid">
    <div class="stat-card"><span><?php echo $totalPets; ?></span>Total Pets</div>
    <div class="stat-card"><span><?php echo $available; ?></span>Available</div>
    <div class="stat-card"><span><?php echo $adopted; ?></span>Adopted</div>
    <div class="stat-card"><span><?php echo $pending; ?></span>Pending Applications</div>
    <div class="stat-card"><span><?php echo $totalApps; ?></span>Total Applications</div>
    <div class="stat-card"><span><?php echo $totalReviews; ?></span>Reviews</div>
</div>

<h3 class="admin-subtitle">Recent Applications</h3>
<div class="table-wrap">
<table class="admin-table">
    <tr><th>Applicant</th><th>Pet</th><th>Status</th><th>Date</th></tr>
    <?php foreach ($recent as $r): ?>
    <tr>
        <td><?php echo e($r['fullname']); ?></td>
        <td><?php echo e($r['pet_name']); ?></td>
        <td><span class="badge badge-<?php echo strtolower(e($r['status'])); ?>"><?php echo e($r['status']); ?></span></td>
        <td><?php echo e(substr($r['created_at'], 0, 10)); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

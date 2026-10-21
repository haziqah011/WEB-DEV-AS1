<?php
//simple adoption report generated from stored data
require_once "admin_auth.php";

// 1. Pets by type and status
$byType = $pdo->query(
    "SELECT type,
            COUNT(*) AS total,
            SUM(status='Available') AS available,
            SUM(status='Adopted')   AS adopted
     FROM pets GROUP BY type ORDER BY type"
)->fetchAll();

// 2. Applications by status
$byStatus = $pdo->query(
    "SELECT status, COUNT(*) AS total FROM applications GROUP BY status ORDER BY status"
)->fetchAll();

// 3. Most requested pets (with average rating)
$popular = $pdo->query(
    "SELECT p.name, p.type, p.status,
            COUNT(DISTINCT a.id) AS applications,
            ROUND(AVG(r.rating), 1) AS avg_rating
     FROM pets p
     LEFT JOIN applications a ON a.pet_id = p.id
     LEFT JOIN reviews r      ON r.pet_id = p.id
     GROUP BY p.id, p.name, p.type, p.status
     ORDER BY applications DESC, p.name
     LIMIT 10"
)->fetchAll();

$totalApps = array_sum(array_column($byStatus, 'total'));
$approved  = 0;
foreach ($byStatus as $s) { if ($s['status'] === 'Approved') $approved = $s['total']; }
$rate = $totalApps ? round($approved / $totalApps * 100) : 0;

$pageTitle  = "Report";
$activePage = "report";
include "admin_header.php";
?>

<div class="admin-top">
    <h2>Adoption Report</h2>
    <button onclick="window.print()" class="pet-btn report-print">🖨 Print Report</button>
</div>
<p class="report-date">Generated on <?php echo date('d M Y, H:i'); ?></p>

<h3 class="admin-subtitle">Pets by Type</h3>
<div class="table-wrap">
<table class="admin-table">
    <tr><th>Type</th><th>Total</th><th>Available</th><th>Adopted</th></tr>
    <?php foreach ($byType as $r): ?>
    <tr>
        <td><?php echo e($r['type']); ?></td>
        <td><?php echo $r['total']; ?></td>
        <td><?php echo (int)$r['available']; ?></td>
        <td><?php echo (int)$r['adopted']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<h3 class="admin-subtitle">Applications by Status</h3>
<div class="table-wrap">
<table class="admin-table">
    <tr><th>Status</th><th>Total</th></tr>
    <?php foreach ($byStatus as $r): ?>
    <tr><td><?php echo e($r['status']); ?></td><td><?php echo $r['total']; ?></td></tr>
    <?php endforeach; ?>
    <tr><th>Total</th><th><?php echo $totalApps; ?></th></tr>
</table>
</div>
<p><strong>Approval rate:</strong> <?php echo $rate; ?>%</p>

<h3 class="admin-subtitle">Most Requested Pets</h3>
<div class="table-wrap">
<table class="admin-table">
    <tr><th>Pet</th><th>Type</th><th>Status</th><th>Applications</th><th>Avg. Rating</th></tr>
    <?php foreach ($popular as $r): ?>
    <tr>
        <td><?php echo e($r['name']); ?></td>
        <td><?php echo e($r['type']); ?></td>
        <td><?php echo e($r['status']); ?></td>
        <td><?php echo $r['applications']; ?></td>
        <td><?php echo $r['avg_rating'] !== null ? e($r['avg_rating']) . ' / 5' : '-'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

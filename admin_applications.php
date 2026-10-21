<?php
// VIEW applications, UPDATE status, DELETE
require_once "admin_auth.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if ($id && $action === 'status') {
        $status = $_POST['status'] ?? '';
        if (in_array($status, ['Pending','Approved','Rejected'], true)) {
            $pdo->prepare("UPDATE applications SET status=? WHERE id=?")->execute([$status, $id]);

            // Approved application -> mark the pet as Adopted
            if ($status === 'Approved') {
                $pdo->prepare("UPDATE pets SET status='Adopted'
                               WHERE id=(SELECT pet_id FROM applications WHERE id=?)")
                    ->execute([$id]);
            }
            $msg = "Application status updated.";
        }
    }

    if ($id && $action === 'delete') {
        $pdo->prepare("DELETE FROM applications WHERE id=?")->execute([$id]);
        $msg = "Application deleted.";
    }
}

$filter = $_GET['status'] ?? 'all';
if (in_array($filter, ['Pending','Approved','Rejected'], true)) {
    $stmt = $pdo->prepare(
        "SELECT a.*, p.name AS pet_name FROM applications a
         JOIN pets p ON p.id=a.pet_id WHERE a.status=? ORDER BY a.created_at DESC, a.id DESC");
    $stmt->execute([$filter]);
} else {
    $filter = 'all';
    $stmt = $pdo->query(
        "SELECT a.*, p.name AS pet_name FROM applications a
         JOIN pets p ON p.id=a.pet_id ORDER BY a.created_at DESC, a.id DESC");
}
$apps = $stmt->fetchAll();

$pageTitle  = "Applications";
$activePage = "applications";
include "admin_header.php";
?>

<div class="admin-top">
    <h2>Adoption Applications</h2>
    <form method="GET" class="inline-filter">
        <select name="status" onchange="this.form.submit()">
            <?php foreach (['all'=>'All','Pending'=>'Pending','Approved'=>'Approved','Rejected'=>'Rejected'] as $v=>$l): ?>
                <option value="<?php echo $v; ?>" <?php echo $filter===$v?'selected':''; ?>><?php echo $l; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($msg): ?><div class="alert alert-success"><?php echo e($msg); ?></div><?php endif; ?>

<div class="table-wrap">
<table class="admin-table">
    <tr>
        <th>ID</th><th>Applicant</th><th>Contact</th><th>Pet</th>
        <th>Housing / Experience</th><th>Reason</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach ($apps as $a): ?>
    <tr>
        <td><?php echo $a['id']; ?></td>
        <td><?php echo e($a['fullname']); ?><br><small><?php echo e($a['address']); ?></small></td>
        <td><?php echo e($a['email']); ?><br><small><?php echo e($a['phone']); ?></small></td>
        <td><?php echo e($a['pet_name']); ?></td>
        <td><?php echo e($a['housing']); ?> / <?php echo e($a['experience']); ?></td>
        <td class="reason"><?php echo e($a['reason']); ?></td>
        <td><span class="badge badge-<?php echo strtolower(e($a['status'])); ?>"><?php echo e($a['status']); ?></span></td>
        <td class="actions">
            <form method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="status">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <select name="status">
                    <?php foreach (['Pending','Approved','Rejected'] as $s): ?>
                        <option <?php echo $a['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-small">Save</button>
            </form>
            <form method="POST" onsubmit="return confirm('Delete this application?');">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <button type="submit" class="btn-small btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$apps): ?><tr><td colspan="8">No applications found.</td></tr><?php endif; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>

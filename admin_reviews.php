<?php
//VIEW and DELETE user reviews/comments
require_once "admin_auth.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $pdo->prepare("DELETE FROM reviews WHERE id=?")->execute([$id]);
        $msg = "Review deleted.";
    }
}

$reviews = $pdo->query(
    "SELECT r.*, p.name AS pet_name FROM reviews r
     JOIN pets p ON p.id=r.pet_id ORDER BY r.created_at DESC, r.id DESC"
)->fetchAll();

$pageTitle  = "Reviews";
$activePage = "reviews";
include "admin_header.php";
?>

<h2>User Reviews</h2>

<?php if ($msg): ?><div class="alert alert-success"><?php echo e($msg); ?></div><?php endif; ?>

<div class="table-wrap">
<table class="admin-table">
    <tr><th>ID</th><th>Pet</th><th>Name</th><th>Rating</th><th>Comment</th><th>Date</th><th>Action</th></tr>
    <?php foreach ($reviews as $r): ?>
    <tr>
        <td><?php echo $r['id']; ?></td>
        <td><?php echo e($r['pet_name']); ?></td>
        <td><?php echo e($r['name']); ?></td>
        <td><?php echo str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']); ?></td>
        <td class="reason"><?php echo e($r['comment']); ?></td>
        <td><?php echo e(substr($r['created_at'], 0, 10)); ?></td>
        <td class="actions">
            <form method="POST" onsubmit="return confirm('Delete this review?');">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                <button type="submit" class="btn-small btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$reviews): ?><tr><td colspan="7">No reviews yet.</td></tr><?php endif; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>
